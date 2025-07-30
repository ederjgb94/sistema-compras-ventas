<?php

namespace App\Livewire\Forms;

use App\Models\Contacto;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ContactoForm extends Form
{
    public ?Contacto $contacto;

    #[Validate('required|in:cliente,proveedor,ambos')]
    public string $tipo = 'cliente';

    #[Validate('required|string|max:255')]
    public string $nombre = '';

    #[Validate('nullable|email|max:255')]
    public string $email = '';

    #[Validate('nullable|string|max:20')]
    public string $telefono = '';

    #[Validate('nullable|string|max:500')]
    public string $direccion = '';

    #[Validate('nullable|string|max:13|regex:/^[A-Z&Ñ]{3,4}[0-9]{6}[A-V1-9][A-Z1-9][0-9A]$/')]
    public string $rfc = '';

    #[Validate('boolean')]
    public bool $activo = true;

    public function rules(): array
    {
        $contactoId = $this->contacto ? $this->contacto->id : null;

        return [
            'tipo' => 'required|in:cliente,proveedor,ambos',
            'nombre' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:contactos,email,' . $contactoId,
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:500',
            'rfc' => [
                'nullable',
                'string',
                'max:13',
                'regex:/^[A-Z&Ñ]{3,4}[0-9]{6}[A-V1-9][A-Z1-9][0-9A]$/',
                'unique:contactos,rfc,' . $contactoId
            ],
            'activo' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'tipo.required' => 'El tipo de contacto es obligatorio.',
            'tipo.in' => 'El tipo debe ser cliente, proveedor o ambos.',
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.max' => 'El nombre no puede tener más de 255 caracteres.',
            'email.email' => 'El formato del email no es válido.',
            'email.unique' => 'Este email ya está registrado.',
            'telefono.max' => 'El teléfono no puede tener más de 20 caracteres.',
            'direccion.max' => 'La dirección no puede tener más de 500 caracteres.',
            'rfc.regex' => 'El formato del RFC no es válido.',
            'rfc.unique' => 'Este RFC ya está registrado.',
            'rfc.max' => 'El RFC no puede tener más de 13 caracteres.',
        ];
    }

    public function setContacto(Contacto $contacto): void
    {
        $this->contacto = $contacto;
        $this->tipo = $contacto->tipo;
        $this->nombre = $contacto->nombre;
        $this->email = $contacto->email ?? '';
        $this->telefono = $contacto->telefono ?? '';
        $this->direccion = $contacto->direccion ?? '';
        $this->rfc = $contacto->rfc ?? '';
        $this->activo = $contacto->activo;
    }

    public function store(): Contacto
    {
        $this->validate();

        $contacto = Contacto::create($this->only([
            'tipo',
            'nombre',
            'email',
            'telefono',
            'direccion',
            'rfc',
            'activo'
        ]));

        $this->reset();

        return $contacto;
    }

    public function update(): bool
    {
        $this->validate();

        $updated = $this->contacto->update($this->only([
            'tipo',
            'nombre',
            'email',
            'telefono',
            'direccion',
            'rfc',
            'activo'
        ]));

        return $updated;
    }
}
