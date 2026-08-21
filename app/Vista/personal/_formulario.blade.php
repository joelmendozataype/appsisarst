{{--
    Capa VISTA - Campos compartidos por HU-01 (alta) y HU-02 (edicion).
    Los campos obligatorios son los que fija la historia HU-01
    (CA-HU01-01 y CA-HU01-02).

    Validaciones (declaradas con data-valida y aplicadas por
    app/Vista/recursos/js/validacion.js; el servidor las repite en
    App\Controlador\Validaciones\PersonalRequest):
      - DNI                 : exactamente 8 digitos numericos (CA-HU01-03)
      - Nombres / Apellidos : solo letras, tildes, ñ y espacios
      - Cargo               : solo letras y espacios, minimo 3 caracteres
      - Telefono            : solo digitos, +, - y espacios (6 a 15)
      - Correo              : formato de correo electronico
      - Fecha de ingreso    : formato AAAA-MM-DD y no puede ser futura
--}}
@php($p = $personal ?? null)

<div class="row g-3">

    {{-- Datos de identificacion --}}
    <div class="col-12">
        <h2 class="h6 text-primary border-bottom pb-2 mb-0">
            <i class="bi bi-person-vcard me-1"></i> Datos personales
        </h2>
    </div>

    {{-- DNI: solo 8 digitos --}}
    <div class="col-12 col-md-3">
        <label for="dni" class="form-label obligatorio">DNI</label>
        <input type="text" inputmode="numeric" maxlength="8"
               data-valida="dni"
               title="El DNI debe tener exactamente 8 digitos numericos."
               class="form-control font-monospace @error('dni') is-invalid @enderror"
               id="dni" name="dni" value="{{ old('dni', $p?->dni) }}"
               autocomplete="off" required>
        @error('dni')
            <div class="invalid-feedback">{{ $message }}</div>
        @else
            <div class="form-text">8 digitos numericos.</div>
        @enderror
    </div>

    {{-- Nombres: solo letras, tildes, ñ, espacios y guiones --}}
    <div class="col-12 col-md-4">
        <label for="nombres" class="form-label obligatorio">Nombres</label>
        <input type="text" maxlength="100"
               data-valida="letras"
               title="Solo letras y espacios. Sin guiones ni simbolos."
               class="form-control @error('nombres') is-invalid @enderror"
               id="nombres" name="nombres" value="{{ old('nombres', $p?->nombres) }}"
               autocomplete="off" required>
        @error('nombres')
            <div class="invalid-feedback">{{ $message }}</div>
        @else
            <div class="form-text">Solo letras y espacios.</div>
        @enderror
    </div>

    {{-- Apellidos: solo letras, tildes, ñ, espacios y guiones --}}
    <div class="col-12 col-md-5">
        <label for="apellidos" class="form-label obligatorio">Apellidos</label>
        <input type="text" maxlength="100"
               data-valida="letras"
               title="Solo letras y espacios. Sin guiones ni simbolos."
               class="form-control @error('apellidos') is-invalid @enderror"
               id="apellidos" name="apellidos" value="{{ old('apellidos', $p?->apellidos) }}"
               autocomplete="off" required>
        @error('apellidos')
            <div class="invalid-feedback">{{ $message }}</div>
        @else
            <div class="form-text">Solo letras y espacios.</div>
        @enderror
    </div>

    {{-- Telefono: solo digitos, +, - y espacios --}}
    <div class="col-12 col-md-6">
        <label for="telefono" class="form-label obligatorio">Telefono</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-telephone"></i></span>
            <input type="tel" inputmode="numeric" maxlength="15" minlength="6"
                   data-valida="telefono"
                   title="Solo digitos (0-9). Ejemplo: 987654321"
                   class="form-control @error('telefono') is-invalid @enderror"
                   id="telefono" name="telefono" value="{{ old('telefono', $p?->telefono) }}"
                   placeholder="Ej: 987654321" autocomplete="tel" required>
        </div>
        @error('telefono')
            <div class="text-danger small mt-1">
                <i class="bi bi-exclamation-circle"></i> {{ $message }}
            </div>
        @else
            <div class="form-text">Solo numeros (6 a 15 digitos). Ejemplo: 987654321</div>
        @enderror
    </div>

    {{-- Correo electronico --}}
    <div class="col-12 col-md-6">
        <label for="correo" class="form-label obligatorio">Correo electronico</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
            <input type="email" maxlength="120"
                   data-valida="correo"
                   title="Ingrese un correo electronico valido. Ejemplo: nombre@dominio.com"
                   class="form-control @error('correo') is-invalid @enderror"
                   id="correo" name="correo" value="{{ old('correo', $p?->correo) }}"
                   placeholder="nombre@dominio.com" autocomplete="email" required>
        </div>
        @error('correo')
            <div class="text-danger small mt-1">
                <i class="bi bi-exclamation-circle"></i> {{ $message }}
            </div>
        @else
            <div class="form-text">Ejemplo: juan.perez@redsaludtayacaja.gob.pe</div>
        @enderror
    </div>

    {{-- Datos laborales --}}
    <div class="col-12 mt-4">
        <h2 class="h6 text-primary border-bottom pb-2 mb-0">
            <i class="bi bi-briefcase me-1"></i> Datos laborales
        </h2>
    </div>

    <div class="col-12 col-md-6">
        <label for="area_id" class="form-label obligatorio">Area de trabajo</label>
        <select class="form-select @error('area_id') is-invalid @enderror"
                id="area_id" name="area_id" required>
            <option value="">-- Seleccione --</option>
            @foreach ($areas as $area)
                <option value="{{ $area->area_id }}"
                    @selected((string) old('area_id', $p?->area_id) === (string) $area->area_id)>
                    {{ $area->nombre }} — {{ $area->establecimiento?->nombre }}
                </option>
            @endforeach
        </select>
        @error('area_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-12 col-md-6">
        <label for="cargo" class="form-label obligatorio">Cargo</label>
        <input type="text" minlength="3" maxlength="80"
               data-valida="letras"
               data-msg="El cargo solo debe contener letras y espacios. Ej: Medico Cirujano."
               title="El cargo solo debe contener letras y espacios. Ej: Medico Cirujano"
               class="form-control @error('cargo') is-invalid @enderror"
               id="cargo" name="cargo" value="{{ old('cargo', $p?->cargo) }}"
               autocomplete="off" required>
        @error('cargo')
            <div class="invalid-feedback">{{ $message }}</div>
        @else
            <div class="form-text">Ej: Medico Cirujano, Enfermero Tecnico, Administrativo.</div>
        @enderror
    </div>

    <div class="col-12 col-md-4">
        <label for="condicion_laboral" class="form-label obligatorio">Condicion laboral</label>
        <select class="form-select @error('condicion_laboral') is-invalid @enderror"
                id="condicion_laboral" name="condicion_laboral" required>
            <option value="">-- Seleccione --</option>
            @foreach ($condiciones as $condicion)
                <option value="{{ $condicion }}"
                    @selected(old('condicion_laboral', $p?->condicion_laboral) === $condicion)>
                    {{ $condicion }}
                </option>
            @endforeach
        </select>
        @error('condicion_laboral') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-12 col-md-4">
        <label for="fecha_ingreso" class="form-label obligatorio">Fecha de ingreso</label>
        <input type="text"
               data-valida="fecha" data-no-futura
               class="form-control js-fecha @error('fecha_ingreso') is-invalid @enderror"
               id="fecha_ingreso" name="fecha_ingreso"
               value="{{ old('fecha_ingreso', $p?->fecha_ingreso?->format('Y-m-d')) }}"
               placeholder="AAAA-MM-DD" required>
        <div class="form-text">No puede ser una fecha futura.</div>
        @error('fecha_ingreso') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-12 col-md-4">
        <label for="horario_id" class="form-label">Horario asignado</label>
        <select class="form-select @error('horario_id') is-invalid @enderror"
                id="horario_id" name="horario_id">
            <option value="">Sin horario asignado</option>
            @foreach ($horarios as $horario)
                <option value="{{ $horario->horario_id }}"
                    @selected((string) old('horario_id', $p?->horario_id) === (string) $horario->horario_id)>
                    {{ $horario->etiqueta }}
                </option>
            @endforeach
        </select>
        <div class="form-text">Opcional. Sin horario asignado el sistema no evalua tardanzas ni faltas.</div>
        @error('horario_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

{{--
    La validacion en el navegador la resuelve el motor comun
    app/Vista/recursos/js/validacion.js, declarado en cada campo con
    los atributos data-valida / data-msg. Ya no hay script propio aqui:
    las mismas reglas se aplican en todos los formularios del sistema.
--}}
