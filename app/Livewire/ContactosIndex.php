<?php

namespace App\Livewire;

use App\Models\Contacto;
use Livewire\Component;
use Livewire\WithPagination;

class ContactosIndex extends Component
{
    use WithPagination;

    // Propiedades para filtros y búsqueda
    public string $search = '';
    public string $tipoFiltro = '';
    public string $estadoFiltro = '';
    public string $sortField = 'nombre';
    public string $sortDirection = 'asc';

    // Propiedades para modales
    public bool $showCreateModal = false;
    public bool $showEditModal = false;
    public bool $showDeleteModal = false;
    public ?Contacto $selectedContacto = null;

    // Propiedades para el formulario de creación
    public string $form_tipo = 'cliente';
    public bool $form_activo = true;
    public string $form_nombre = '';
    public string $form_email = '';
    public string $form_telefono = '';
    public string $form_rfc = '';
    public string $form_direccion = '';

    public function mount()
    {
        // Leer filtros desde la URL
        $this->search = request('search', '');
        $this->tipoFiltro = request('tipo', '');
        $this->estadoFiltro = request('estado', '');
        $this->sortField = request('sort', 'nombre');
        $this->sortDirection = request('direction', 'asc');
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }

        // Redirigir con los nuevos parámetros de ordenamiento
        return redirect()->to(request()->fullUrlWithQuery([
            'sort' => $this->sortField,
            'direction' => $this->sortDirection,
            'search' => $this->search ?: null,
            'tipo' => $this->tipoFiltro ?: null,
            'estado' => $this->estadoFiltro ?: null,
        ]));
    }

    public function openCreateModal()
    {
        // Resetear campos del formulario
        $this->form_tipo = 'cliente';
        $this->form_activo = true;
        $this->form_nombre = '';
        $this->form_email = '';
        $this->form_telefono = '';
        $this->form_rfc = '';
        $this->form_direccion = '';

        $this->showCreateModal = true;
    }

    public function closeCreateModal()
    {
        $this->showCreateModal = false;
    }

    public function saveContacto()
    {
        $this->validate([
            'form_nombre' => 'required|string|max:255',
            'form_email' => 'nullable|email|max:255',
            'form_telefono' => 'nullable|string|max:20',
            'form_rfc' => 'nullable|string|max:13',
            'form_direccion' => 'nullable|string|max:500',
            'form_tipo' => 'required|in:cliente,proveedor,ambos',
            'form_activo' => 'boolean',
        ]);

        try {
            Contacto::create([
                'nombre' => $this->form_nombre,
                'email' => $this->form_email,
                'telefono' => $this->form_telefono,
                'rfc' => $this->form_rfc,
                'direccion' => $this->form_direccion,
                'tipo' => $this->form_tipo,
                'activo' => $this->form_activo,
            ]);

            session()->flash('message', 'Contacto creado exitosamente.');
            $this->closeCreateModal();
        } catch (\Exception $e) {
            session()->flash('error', 'Error al crear el contacto: ' . $e->getMessage());
        }
    }

    public function openEditModal(Contacto $contacto)
    {
        $this->selectedContacto = $contacto;
        $this->showEditModal = true;
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->selectedContacto = null;
    }

    public function confirmDelete(Contacto $contacto)
    {
        $this->selectedContacto = $contacto;
        $this->showDeleteModal = true;
    }

    public function deleteContacto()
    {
        if ($this->selectedContacto) {
            $this->selectedContacto->delete();

            session()->flash('message', 'Contacto eliminado exitosamente.');

            $this->showDeleteModal = false;
            $this->selectedContacto = null;
        }
    }

    public function cancelDelete()
    {
        $this->showDeleteModal = false;
        $this->selectedContacto = null;
    }

    // Listener para refrescar la lista cuando se crea/edita un contacto
    protected function getListeners()
    {
        return [
            'contacto-created' => '$refresh',
            'contacto-updated' => '$refresh',
            'close-create-modal' => 'closeCreateModal',
            'close-edit-modal' => 'closeEditModal',
        ];
    }

    public function render()
    {
        $query = Contacto::query();

        // Aplicar búsqueda
        if ($this->search) {
            $query->buscar($this->search);
        }

        // Aplicar filtro por tipo
        if ($this->tipoFiltro) {
            if ($this->tipoFiltro === 'cliente') {
                $query->clientes();
            } elseif ($this->tipoFiltro === 'proveedor') {
                $query->proveedores();
            } else {
                $query->where('tipo', $this->tipoFiltro);
            }
        }

        // Aplicar filtro por estado
        if ($this->estadoFiltro !== '') {
            $query->where('activo', $this->estadoFiltro === '1');
        }

        // Aplicar ordenamiento
        $query->orderBy($this->sortField, $this->sortDirection);

        return view('livewire.contactos-index', [
            'contactos' => $query->paginate(10)->appends(request()->query()),
        ]);
    }
}
