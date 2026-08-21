/**
 * SISARST - Motor de validacion de formularios (capa Vista).
 *
 * Es la contraparte en el navegador de las reglas que ya aplican los
 * FormRequest del servidor (app/Controlador/Validaciones). El servidor sigue
 * siendo la autoridad: esto solo evita que el usuario envie datos que
 * seguro van a ser rechazados, y le explica el error mientras escribe.
 *
 * --------------------------------------------------------------------------
 * COMO SE USA
 * --------------------------------------------------------------------------
 * Se declara en el propio campo con atributos data-*:
 *
 *   <input data-valida="dni" required>
 *   <input data-valida="letras" maxlength="100" required>
 *   <input data-valida="telefono" required>
 *   <input data-valida="entero" min="0" max="60" required>
 *   <input data-valida="hora" data-mayor-que="#hora_entrada" required>
 *   <input data-valida="fecha" data-no-futura required>
 *   <textarea data-valida="texto" minlength="10" maxlength="255" required>
 *
 * Modificadores opcionales:
 *   data-mayor-que="#otroCampo"      valor debe ser mayor que el de ese campo
 *   data-desde="#otroCampo"          valor no puede ser anterior a ese campo
 *   data-no-futura                   la fecha no puede ser posterior a hoy
 *   data-igual-a="#otroCampo"        debe coincidir (confirmacion de clave)
 *   data-msg="..."                   mensaje propio para el formato
 *
 * Cualquier <form> que contenga campos con data-valida queda protegido: no
 * se envia mientras haya un error, y el foco salta al primer campo invalido.
 */

/* ── Tipos de campo ───────────────────────────────────────────────────── */

// Bloque latino extendido, para aceptar tildes, dieresis y ñ.
const L = 'A-Za-z\\u00C0-\\u00D6\\u00D8-\\u00F6\\u00F8-\\u00FF';

const TIPOS = {
    // Solo letras y espacios. Sin numeros, guiones ni simbolos.
    letras: {
        permitido: new RegExp(`[${L} ]`),
        completo: new RegExp(`^[${L}][${L}\\s]*$`),
        limpiar: new RegExp(`[^${L}\\s]`, 'g'),
        mensaje: 'Solo se permiten letras y espacios. Sin numeros ni simbolos.',
    },
    // Documento nacional de identidad: exactamente 8 digitos.
    dni: {
        permitido: /\d/,
        completo: /^\d{8}$/,
        limpiar: /\D/g,
        maxlength: 8,
        mensaje: 'El DNI debe tener exactamente 8 digitos numericos.',
    },
    // Telefono: digitos, "+", "-" y espacios; de 6 a 15 caracteres.
    telefono: {
        permitido: /[0-9+\- ]/,
        completo: /^[0-9+\- ]{6,15}$/,
        limpiar: /[^0-9+\- ]/g,
        mensaje: 'El telefono admite de 6 a 15 digitos. No se permiten letras.',
    },
    // Numero entero sin signo.
    entero: {
        permitido: /\d/,
        completo: /^\d+$/,
        limpiar: /\D/g,
        mensaje: 'Ingrese un numero entero.',
    },
    // Nombre de usuario del sistema: minusculas, digitos, . _ -
    usuario: {
        permitido: /[a-z0-9._-]/,
        completo: /^[a-z0-9._-]{4,40}$/,
        limpiar: /[^a-z0-9._-]/g,
        mensaje: 'Solo minusculas, numeros, punto, guion y guion bajo (4 a 40 caracteres).',
    },
    // Correo electronico.
    correo: {
        completo: /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/,
        mensaje: 'Ingrese un correo valido. Ejemplo: nombre@dominio.gob.pe',
    },
    // Hora en formato HH:MM (24 horas).
    hora: {
        completo: /^([01]\d|2[0-3]):[0-5]\d$/,
        mensaje: 'La hora debe tener el formato HH:MM (por ejemplo 08:15).',
    },
    // Fecha ISO AAAA-MM-DD (es el formato que entrega flatpickr).
    fecha: {
        completo: /^\d{4}-\d{2}-\d{2}$/,
        mensaje: 'La fecha debe tener el formato AAAA-MM-DD.',
    },
    // Texto libre: solo se controlan longitud y obligatoriedad.
    texto: {
        mensaje: 'El texto ingresado no es valido.',
    },
    // Contrasena: minimo 8 caracteres, con letras y numeros (RNF-02).
    clave: {
        completo: /^(?=.*[A-Za-z])(?=.*\d).{8,}$/,
        mensaje: 'La contrasena debe tener al menos 8 caracteres, con letras y numeros.',
    },
};

/* ── Pintado del estado del campo ─────────────────────────────────────── */

function contenedor(campo) {
    return campo.closest('.input-group')?.parentElement
        || campo.closest('.input-group')
        || campo.parentElement;
}

function bloqueMensaje(campo) {
    const cont = contenedor(campo);
    let fb = cont.querySelector(':scope > .js-fb');
    if (!fb) {
        fb = document.createElement('div');
        fb.className = 'invalid-feedback js-fb';
        fb.style.display = 'block';
        cont.appendChild(fb);
    }
    return fb;
}

function marcarError(campo, mensaje) {
    campo.classList.add('is-invalid');
    campo.classList.remove('is-valid');
    campo.setAttribute('aria-invalid', 'true');
    bloqueMensaje(campo).textContent = mensaje;
}

function marcarOk(campo) {
    campo.classList.remove('is-invalid');
    campo.classList.add('is-valid');
    campo.removeAttribute('aria-invalid');
    bloqueMensaje(campo).textContent = '';
}

function limpiar(campo) {
    campo.classList.remove('is-invalid', 'is-valid');
    campo.removeAttribute('aria-invalid');
    bloqueMensaje(campo).textContent = '';
}

function parpadear(campo) {
    campo.classList.add('border-danger');
    setTimeout(() => campo.classList.remove('border-danger'), 250);
}

function etiqueta(campo) {
    const lbl = campo.form?.querySelector(`label[for="${campo.id}"]`);
    return (lbl?.textContent || campo.name || 'Este campo').replace(/\s+/g, ' ').trim();
}

function otroCampo(campo, atributo) {
    const sel = campo.dataset[atributo];
    return sel ? document.querySelector(sel) : null;
}

/* ── Reglas de validacion de un campo ─────────────────────────────────── */

/**
 * Devuelve el mensaje de error del campo, o null si es valido.
 */
function revisar(campo) {
    const valor = (campo.value ?? '').trim();
    const tipo = TIPOS[campo.dataset.valida] || TIPOS.texto;
    const obligatorio = campo.hasAttribute('required');

    if (valor === '') {
        return obligatorio ? `${etiqueta(campo)} es obligatorio.` : null;
    }

    // Longitudes declaradas en el HTML (espejo de las reglas del servidor).
    const min = parseInt(campo.getAttribute('minlength'), 10);
    const max = parseInt(campo.getAttribute('maxlength'), 10);
    if (!Number.isNaN(min) && valor.length < min) {
        return `Debe tener al menos ${min} caracteres (van ${valor.length}).`;
    }
    if (!Number.isNaN(max) && valor.length > max) {
        return `No puede superar los ${max} caracteres.`;
    }

    // Formato propio del tipo.
    if (tipo.completo && !tipo.completo.test(valor)) {
        return campo.dataset.msg || tipo.mensaje;
    }

    // Rango numerico.
    if (campo.dataset.valida === 'entero') {
        const n = parseInt(valor, 10);
        const minN = campo.getAttribute('min');
        const maxN = campo.getAttribute('max');
        if (minN !== null && n < parseInt(minN, 10)) {
            return `El valor minimo es ${minN}.`;
        }
        if (maxN !== null && n > parseInt(maxN, 10)) {
            return `El valor maximo es ${maxN}.`;
        }
    }

    // La fecha no puede ser posterior a hoy.
    if (campo.hasAttribute('data-no-futura')) {
        const hoy = new Date();
        const iso = `${hoy.getFullYear()}-${String(hoy.getMonth() + 1).padStart(2, '0')}-${String(hoy.getDate()).padStart(2, '0')}`;
        if (valor > iso) {
            return 'La fecha no puede ser posterior a hoy.';
        }
    }

    // Comparaciones entre campos.
    const mayor = otroCampo(campo, 'mayorQue');
    if (mayor && mayor.value.trim() !== '' && valor <= mayor.value.trim()) {
        return `Debe ser posterior a ${etiqueta(mayor).toLowerCase()}.`;
    }

    const desde = otroCampo(campo, 'desde');
    if (desde && desde.value.trim() !== '' && valor < desde.value.trim()) {
        return `No puede ser anterior a ${etiqueta(desde).toLowerCase()}.`;
    }

    const igual = otroCampo(campo, 'igualA');
    if (igual && valor !== igual.value.trim()) {
        return 'Los valores no coinciden.';
    }

    return null;
}

/* ── Enganche de eventos ──────────────────────────────────────────────── */

function conectar(campo) {
    if (campo.dataset.validaListo === '1') return;
    campo.dataset.validaListo = '1';

    const tipo = TIPOS[campo.dataset.valida] || TIPOS.texto;

    if (tipo.maxlength && !campo.hasAttribute('maxlength')) {
        campo.setAttribute('maxlength', String(tipo.maxlength));
    }

    // Bloqueo tecla a tecla de los caracteres que el tipo no admite.
    if (tipo.permitido) {
        campo.addEventListener('keypress', (e) => {
            if (e.key.length === 1 && !tipo.permitido.test(e.key)) {
                e.preventDefault();
                parpadear(campo);
            }
        });
    }

    // Al pegar, se limpia el texto en lugar de rechazarlo entero.
    if (tipo.limpiar) {
        campo.addEventListener('paste', (e) => {
            e.preventDefault();
            const texto = (e.clipboardData || window.clipboardData).getData('text');
            let limpio = texto.replace(tipo.limpiar, '');
            const max = parseInt(campo.getAttribute('maxlength'), 10);
            if (!Number.isNaN(max)) limpio = limpio.slice(0, max);
            try {
                document.execCommand('insertText', false, limpio);
            } catch (_) {
                campo.value += limpio;
            }
        });
    }

    // El usuario del sistema siempre va en minusculas.
    if (campo.dataset.valida === 'usuario' || campo.dataset.valida === 'correo') {
        campo.addEventListener('input', () => {
            const pos = campo.selectionStart;
            campo.value = campo.value.toLowerCase();
            if (pos !== null) campo.setSelectionRange(pos, pos);
        });
    }

    campo.addEventListener('blur', () => {
        if (campo.value.trim() === '' && !campo.hasAttribute('required')) {
            limpiar(campo);
            return;
        }
        const error = revisar(campo);
        if (error) marcarError(campo, error);
        else marcarOk(campo);
    });

    // Mientras se corrige, el error desaparece en cuanto el valor es valido.
    campo.addEventListener('input', () => {
        if (!campo.classList.contains('is-invalid')) return;
        if (revisar(campo) === null) marcarOk(campo);
    });

    // Si el campo participa en una comparacion, revalidar al cambiar el otro.
    ['mayorQue', 'desde', 'igualA'].forEach((rel) => {
        const otro = otroCampo(campo, rel);
        otro?.addEventListener('change', () => {
            if (campo.value.trim() === '') return;
            const error = revisar(campo);
            if (error) marcarError(campo, error);
            else marcarOk(campo);
        });
    });
}

/**
 * Selects y textareas obligatorios que no declaran data-valida:
 * se controla unicamente que tengan valor.
 */
function conectarObligatorio(campo) {
    if (campo.dataset.validaListo === '1') return;
    campo.dataset.validaListo = '1';

    const revisarVacio = () => {
        if (campo.value === '' || campo.value === null) {
            marcarError(campo, `${etiqueta(campo)} es obligatorio.`);
            return false;
        }
        marcarOk(campo);
        return true;
    };

    campo.addEventListener('change', revisarVacio);
    campo.addEventListener('blur', () => {
        if (campo.value === '') revisarVacio();
    });
}

/* ── Grupos de casillas (roles, permisos, asignacion masiva) ──────────── */

/**
 * Contenedor con data-grupo-min="1" y data-grupo-nombre="roles[]":
 * exige que al menos N casillas del grupo esten marcadas.
 */
function revisarGrupo(cont) {
    const minimo = parseInt(cont.dataset.grupoMin || '1', 10);
    const nombre = cont.dataset.grupoNombre;
    const marcadas = cont.querySelectorAll(
        `input[type="checkbox"][name="${CSS.escape(nombre)}"]:checked`
    ).length;

    let fb = cont.querySelector(':scope > .js-fb-grupo');
    if (!fb) {
        fb = document.createElement('div');
        fb.className = 'invalid-feedback js-fb-grupo';
        fb.style.display = 'block';
        cont.appendChild(fb);
    }

    if (marcadas < minimo) {
        cont.classList.add('sisarst-grupo-invalido');
        fb.textContent = cont.dataset.grupoMsg
            || `Seleccione al menos ${minimo} opcion${minimo > 1 ? 'es' : ''}.`;
        return false;
    }

    cont.classList.remove('sisarst-grupo-invalido');
    fb.textContent = '';
    return true;
}

function conectarGrupo(cont) {
    if (cont.dataset.validaListo === '1') return;
    cont.dataset.validaListo = '1';

    cont.querySelectorAll('input[type="checkbox"]').forEach((chk) => {
        chk.addEventListener('change', () => {
            // Solo se repinta si ya se habia senalado el error.
            if (cont.classList.contains('sisarst-grupo-invalido')) revisarGrupo(cont);
        });
    });
}

/* ── Proteccion del envio del formulario ──────────────────────────────── */

function protegerFormulario(form) {
    if (form.dataset.validaListo === '1') return;
    form.dataset.validaListo = '1';

    form.setAttribute('novalidate', 'novalidate'); // los mensajes los damos nosotros

    form.addEventListener('submit', (e) => {
        let primerError = null;

        form.querySelectorAll('[data-valida]').forEach((campo) => {
            if (campo.disabled || campo.offsetParent === null) return; // campo oculto
            const error = revisar(campo);
            if (error) {
                marcarError(campo, error);
                if (!primerError) primerError = campo;
            } else {
                marcarOk(campo);
            }
        });

        form.querySelectorAll('select[required], textarea[required], input[required]')
            .forEach((campo) => {
                if (campo.dataset.valida || campo.disabled || campo.offsetParent === null) return;
                if (campo.type === 'checkbox' || campo.type === 'radio') return;
                if ((campo.value ?? '').trim() === '') {
                    marcarError(campo, `${etiqueta(campo)} es obligatorio.`);
                    if (!primerError) primerError = campo;
                }
            });

        form.querySelectorAll('[data-grupo-min]').forEach((cont) => {
            if (!revisarGrupo(cont) && !primerError) primerError = cont;
        });

        if (primerError) {
            e.preventDefault();
            e.stopPropagation();
            primerError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            if (typeof primerError.focus === 'function') {
                primerError.focus({ preventScroll: true });
            }
        }
    });
}

/* ── Arranque ─────────────────────────────────────────────────────────── */

export function iniciarValidacion(raiz = document) {
    raiz.querySelectorAll('[data-valida]').forEach(conectar);

    raiz.querySelectorAll('select[required], textarea[required]').forEach((campo) => {
        if (!campo.dataset.valida) conectarObligatorio(campo);
    });

    raiz.querySelectorAll('[data-grupo-min]').forEach(conectarGrupo);

    raiz.querySelectorAll('form').forEach((form) => {
        if (form.querySelector('[data-valida], [required], [data-grupo-min]')) {
            protegerFormulario(form);
        }
    });
}

export default iniciarValidacion;
