@extends('layouts.app')

@section('title', 'Gestión de Usuarios')
@section('page-title', 'Módulo de Usuarios')

@section('content')
    <div class="card">
        <!-- Card Header with Search, Filter & New User Button -->
        <div class="card-header">
            <div>
                <h2 class="card-title">Usuarios del Sistema</h2>
                <p style="font-size: 0.825rem; color: var(--text-muted); margin-top: 2px;">
                    Administra las cuentas de acceso, roles y fotos de perfil de los usuarios.
                </p>
            </div>

            <div style="display: flex; align-items: center; gap: 14px; flex-wrap: wrap;">
                <!-- Filter & Search Form -->
                <form action="{{ route('users.index') }}" method="GET" class="search-filter-form">
                    <div class="search-input-group">
                        <span class="search-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="16" height="16">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                            </svg>
                        </span>
                        <input 
                            type="text" 
                            name="search" 
                            class="form-control" 
                            placeholder="Buscar por nombre o correo..." 
                            value="{{ request('search') }}"
                        >
                    </div>

                    <select name="role" class="filter-select" onchange="this.form.submit()">
                        <option value="">Todos los Roles</option>
                        <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Administradores</option>
                        <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>Usuarios Estándar</option>
                    </select>

                    @if(request('search') || request('role'))
                        <a href="{{ route('users.index') }}" class="btn-secondary" style="padding: 8px 12px;" title="Limpiar filtros">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="16" height="16">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                            </svg>
                        </a>
                    @endif
                </form>

                <!-- Create Button (Abre Modal) -->
                <button type="button" class="btn-primary" id="btnCreateUser" onclick="openCreateUserModal()">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor" width="16" height="16">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Nuevo Usuario
                </button>
            </div>
        </div>

        <!-- Users Table -->
        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th style="width: 70px;">Avatar</th>
                        <th>Nombre Completo</th>
                        <th>Correo Electrónico</th>
                        <th>Rol Asignado</th>
                        <th>Fecha Creación</th>
                        <th style="text-align: right; width: 120px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>
                                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="user-table-avatar">
                            </td>
                            <td>
                                <div style="font-weight: 700; color: var(--text-primary);">{{ $user->name }}</div>
                                @if($user->id === auth()->id())
                                    <span style="font-size: 0.725rem; background-color: #dbeafe; color: #1e40af; padding: 2px 6px; border-radius: 4px; font-weight: 600;">
                                        Tu cuenta (Tú)
                                    </span>
                                @endif
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="role-badge {{ $user->isAdmin() ? 'role-admin' : 'role-user' }}">
                                    {{ $user->isAdmin() ? '🛡️ Administrador' : '👤 Usuario' }}
                                </span>
                            </td>
                            <td>{{ $user->created_at ? $user->created_at->format('d/m/Y') : '-' }}</td>
                            <td>
                                <div class="action-buttons-group" style="justify-content: flex-end;">
                                    <!-- Edit Button (Abre Modal) -->
                                    <button type="button" 
                                        class="btn-action btn-edit" 
                                        onclick="openEditUserModal(this)"
                                        data-id="{{ $user->id }}"
                                        data-name="{{ $user->name }}"
                                        data-email="{{ $user->email }}"
                                        data-role="{{ $user->role }}"
                                        data-avatar="{{ $user->avatar_url }}"
                                        data-updateurl="{{ route('users.update', $user) }}"
                                        title="Editar usuario"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="16" height="16">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                        </svg>
                                    </button>

                                    <!-- Delete Button con SweetAlert2 (Protegido contra eliminar la propia cuenta) -->
                                    @if($user->id !== auth()->id())
                                        <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirmDelete(event, '¿Eliminar usuario?', 'Se eliminará permanentemente la cuenta de {{ $user->name }}.')" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action btn-delete" title="Eliminar usuario">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="16" height="16">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                </svg>
                                            </button>
                                        </form>
                                    @else
                                        <span class="btn-action" style="opacity: 0.35; cursor: not-allowed;" title="No puedes eliminar tu propia cuenta">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" width="16" height="16">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                                            </svg>
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 40px; color: var(--text-muted);">
                                No se encontraron usuarios con los criterios de búsqueda seleccionados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
            <div style="padding: 18px 24px; border-top: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center;">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    <!-- ==========================================================================
         MODAL CREAR USUARIO
         ========================================================================== -->
    <div class="modal-overlay" id="modalCreateUser" role="dialog" aria-modal="true" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-header">
                <div class="modal-title">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="22" height="22" style="color: var(--asesco-orange);">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                    </svg>
                    <span>Nuevo Usuario</span>
                </div>
                <button type="button" class="modal-close-btn" onclick="closeCreateUserModal()" title="Cerrar modal (Esc)">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor" width="20" height="20">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data" id="createUserForm">
                @csrf
                <div class="modal-body">
                    <!-- Foto de Perfil con Vista Previa -->
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label class="form-label">Foto de Perfil</label>
                        <div class="avatar-upload-container">
                            <div class="avatar-preview-box">
                                <img id="createAvatarPreview" src="{{ asset('images/logo_asesco.png') }}" alt="Vista previa" class="avatar-preview-img">
                            </div>
                            <div style="flex: 1;">
                                <input 
                                    type="file" 
                                    name="avatar" 
                                    id="createAvatarInput" 
                                    accept="image/png, image/jpeg, image/jpg, image/webp" 
                                    class="form-control" 
                                    style="padding: 8px 12px; height: auto;"
                                >
                                <p style="font-size: 0.75rem; color: var(--text-light); margin-top: 5px;">
                                    Formatos: JPG, PNG, WEBP (Máx. 4 MB).
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Nombre Completo -->
                    <div class="form-group">
                        <label for="create_name" class="form-label">Nombre Completo <span style="color: #ef4444;">*</span></label>
                        <input 
                            type="text" 
                            name="name" 
                            id="create_name" 
                            class="form-control" 
                            placeholder="Ej. Carlos Mendoza" 
                            required
                            style="padding-left: 14px;"
                        >
                    </div>

                    <!-- Correo Electrónico -->
                    <div class="form-group">
                        <label for="create_email" class="form-label">Correo Electrónico <span style="color: #ef4444;">*</span></label>
                        <input 
                            type="email" 
                            name="email" 
                            id="create_email" 
                            class="form-control" 
                            placeholder="usuario@asescobpo.com" 
                            required
                            style="padding-left: 14px;"
                        >
                    </div>

                    <!-- Contraseña -->
                    <div class="form-group">
                        <label for="create_password" class="form-label">Contraseña <span style="color: #ef4444;">*</span></label>
                        <input 
                            type="password" 
                            name="password" 
                            id="create_password" 
                            class="form-control" 
                            placeholder="Mínimo 6 caracteres" 
                            required
                            style="padding-left: 14px;"
                        >
                    </div>

                    <!-- Rol -->
                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="create_role" class="form-label">Rol del Usuario <span style="color: #ef4444;">*</span></label>
                        <select name="role" id="create_role" class="form-control" required style="padding-left: 14px; cursor: pointer;">
                            <option value="user">Usuario Estándar (Acceso a Soportes)</option>
                            <option value="admin">Administrador (Control total)</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closeCreateUserModal()">
                        Cancelar
                    </button>
                    <button type="submit" class="btn-primary" id="btnSubmitCreateUser">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="16" height="16">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                        Guardar Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ==========================================================================
         MODAL EDITAR USUARIO
         ========================================================================== -->
    <div class="modal-overlay" id="modalEditUser" role="dialog" aria-modal="true" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-header">
                <div class="modal-title">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="22" height="22" style="color: var(--asesco-magenta);">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                    </svg>
                    <span>Editar Usuario: <span id="editModalUserName" style="color: var(--asesco-orange);"></span></span>
                </div>
                <button type="button" class="modal-close-btn" onclick="closeEditUserModal()" title="Cerrar modal (Esc)">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor" width="20" height="20">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form action="" method="POST" enctype="multipart/form-data" id="editUserForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <!-- Foto de Perfil con Vista Previa -->
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label class="form-label">Foto de Perfil</label>
                        <div class="avatar-upload-container">
                            <div class="avatar-preview-box">
                                <img id="editAvatarPreview" src="{{ asset('images/logo_asesco.png') }}" alt="Vista previa" class="avatar-preview-img">
                            </div>
                            <div style="flex: 1;">
                                <input 
                                    type="file" 
                                    name="avatar" 
                                    id="editAvatarInput" 
                                    accept="image/png, image/jpeg, image/jpg, image/webp" 
                                    class="form-control" 
                                    style="padding: 8px 12px; height: auto;"
                                >
                                <p style="font-size: 0.75rem; color: var(--text-light); margin-top: 5px;">
                                    Deja vacío para conservar la foto actual.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Nombre Completo -->
                    <div class="form-group">
                        <label for="edit_name" class="form-label">Nombre Completo <span style="color: #ef4444;">*</span></label>
                        <input 
                            type="text" 
                            name="name" 
                            id="edit_name" 
                            class="form-control" 
                            required
                            style="padding-left: 14px;"
                        >
                    </div>

                    <!-- Correo Electrónico -->
                    <div class="form-group">
                        <label for="edit_email" class="form-label">Correo Electrónico <span style="color: #ef4444;">*</span></label>
                        <input 
                            type="email" 
                            name="email" 
                            id="edit_email" 
                            class="form-control" 
                            required
                            style="padding-left: 14px;"
                        >
                    </div>

                    <!-- Contraseña (Opcional) -->
                    <div class="form-group">
                        <label for="edit_password" class="form-label">
                            Nueva Contraseña <span style="font-size: 0.75rem; color: var(--text-light); font-weight: normal;">(Dejar en blanco para mantener la actual)</span>
                        </label>
                        <input 
                            type="password" 
                            name="password" 
                            id="edit_password" 
                            class="form-control" 
                            placeholder="••••••••" 
                            style="padding-left: 14px;"
                        >
                    </div>

                    <!-- Rol -->
                    <div class="form-group" style="margin-bottom: 0;">
                        <label for="edit_role" class="form-label">Rol del Usuario <span style="color: #ef4444;">*</span></label>
                        <select name="role" id="edit_role" class="form-control" required style="padding-left: 14px; cursor: pointer;">
                            <option value="user">Usuario Estándar (Acceso a Soportes)</option>
                            <option value="admin">Administrador (Control total)</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closeEditUserModal()">
                        Cancelar
                    </button>
                    <button type="submit" class="btn-primary" id="btnSubmitEditUser">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="16" height="16">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Modal Crear Usuario
    function openCreateUserModal() {
        document.getElementById('createUserForm').reset();
        document.getElementById('createAvatarPreview').src = "{{ asset('images/logo_asesco.png') }}";
        const modal = document.getElementById('modalCreateUser');
        modal.classList.add('active');
        modal.setAttribute('aria-hidden', 'false');
        setTimeout(() => document.getElementById('create_name').focus(), 100);
    }

    function closeCreateUserModal() {
        const modal = document.getElementById('modalCreateUser');
        modal.classList.remove('active');
        modal.setAttribute('aria-hidden', 'true');
    }

    // Modal Editar Usuario
    function openEditUserModal(btn) {
        const d = btn.dataset;
        const form = document.getElementById('editUserForm');
        form.action = d.updateurl;

        document.getElementById('editModalUserName').innerText = d.name;
        document.getElementById('edit_name').value = d.name;
        document.getElementById('edit_email').value = d.email;
        document.getElementById('edit_role').value = d.role;
        document.getElementById('edit_password').value = '';
        document.getElementById('editAvatarPreview').src = d.avatar;

        const modal = document.getElementById('modalEditUser');
        modal.classList.add('active');
        modal.setAttribute('aria-hidden', 'false');
        setTimeout(() => document.getElementById('edit_name').focus(), 100);
    }

    function closeEditUserModal() {
        const modal = document.getElementById('modalEditUser');
        modal.classList.remove('active');
        modal.setAttribute('aria-hidden', 'true');
    }

    // Live avatar preview for create & edit
    document.addEventListener('DOMContentLoaded', function() {
        const createInput = document.getElementById('createAvatarInput');
        const createPreview = document.getElementById('createAvatarPreview');
        if (createInput && createPreview) {
            createInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (event) => createPreview.src = event.target.result;
                    reader.readAsDataURL(file);
                }
            });
        }

        const editInput = document.getElementById('editAvatarInput');
        const editPreview = document.getElementById('editAvatarPreview');
        if (editInput && editPreview) {
            editInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (event) => editPreview.src = event.target.result;
                    reader.readAsDataURL(file);
                }
            });
        }

        // Auto open create modal if parameter ?crear=1 exists
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('crear') === '1') {
            openCreateUserModal();
        }

        @if(isset($errors) && $errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Atención',
                html: `{!! implode('<br>', $errors->all()) !!}`,
                confirmButtonText: 'Entendido',
                customClass: {
                    popup: 'asesco-swal-popup',
                    title: 'asesco-swal-title',
                    confirmButton: 'asesco-swal-confirm'
                },
                buttonsStyling: false
            });
        @endif
    });
</script>
@endpush
