<?php

declare(strict_types=1);

namespace App\Controlador\Validaciones;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validacion del formulario de acceso (RF-13).
 *
 * El Sprint 1 implementa el minimo necesario para que el control de acceso
 * por rol del padron sea verificable (CA-HU03-03 y CA-HU18-03). El flujo
 * completo de seguridad -bloqueo por intentos y recuperacion de clave-
 * corresponde al Sprint 4.
 */
class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'username' => ['required', 'string', 'min:4', 'max:40'],
            'password' => ['required', 'string', 'max:255'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'username.required' => 'Ingrese su nombre de usuario.',
            'username.min' => 'El nombre de usuario tiene al menos 4 caracteres.',
            'username.max' => 'El nombre de usuario no supera los 40 caracteres.',
            'password.required' => 'Ingrese su contrasena.',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'username' => 'nombre de usuario',
            'password' => 'contrasena',
        ];
    }

    /**
     * El username se guarda en minusculas (uq_usuario_username), asi que se
     * normaliza antes de validar para que un "JMendoza" tecleado con
     * mayusculas no impida el acceso.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'username' => mb_strtolower(trim((string) $this->input('username'))),
        ]);
    }
}
