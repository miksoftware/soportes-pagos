@extends('layouts.app')

@section('title', 'Crear Usuario')
@section('page-title', 'Registrar Nuevo Usuario')

@section('content')
    <div style="max-width: 800px; margin: 0 auto;">
        <div class="card">
            <div class="card-header">
                <div>
                    <h2 class="card-title">Formulario de Registro</h2>
                    <p style="font-size: 0.825rem; color: var(--text-muted); margin-top: 2px;">
                        Ingresa la información básica y asigna el rol correspondiente para el nuevo acceso.
                    </p>
                </div>
                <a href="{{ route('users.index') }}" class="btn-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="16" height="16">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                    </svg>
                    Volver al Listado
                </a>
            </div>

            <div class="card-body">
                <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Foto de Perfil con Vista Previa -->
                    <div class="form-group" style="margin-bottom: 26px;">
                        <label class="form-label">Foto de Perfil</label>
                        <div class="avatar-upload-container">
                            <div class="avatar-preview-box">
                                <img id="avatarPreview" src="{{ asset('images/logo_asesco.png') }}" alt="Vista previa" class="avatar-preview-img">
                            </div>
                            <div style="flex: 1;">
                                <input 
                                    type="file" 
                                    name="avatar" 
                                    id="avatarInput" 
                                    accept="image/png, image/jpeg, image/jpg, image/webp" 
                                    class="form-control" 
                                    style="padding-left: 16px; height: auto; padding: 10px;"
                                >
                                <p style="font-size: 0.775rem; color: var(--text-light); margin-top: 6px;">
                                    Formatos permitidos: JPG, PNG, WEBP. Tamaño máximo: 4 MB.
                                </p>
                                @error('avatar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <!-- Nombre Completo -->
                        <div class="form-group">
                            <label for="name" class="form-label">Nombre Completo <span style="color: #ef4444;">*</span></label>
                            <input 
                                type="text" 
                                name="name" 
                                id="name" 
                                class="form-control @error('name') is-invalid @enderror" 
                                placeholder="Ej. Carlos Mendoza" 
                                value="{{ old('name') }}" 
                                required
                                style="padding-left: 16px;"
                            >
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Correo Electrónico -->
                        <div class="form-group">
                            <label for="email" class="form-label">Correo Electrónico <span style="color: #ef4444;">*</span></label>
                            <input 
                                type="email" 
                                name="email" 
                                id="email" 
                                class="form-control @error('email') is-invalid @enderror" 
                                placeholder="ejemplo@asescobpo.com" 
                                value="{{ old('email') }}" 
                                required
                                style="padding-left: 16px;"
                            >
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <!-- Contraseña -->
                        <div class="form-group">
                            <label for="password" class="form-label">Contraseña <span style="color: #ef4444;">*</span></label>
                            <input 
                                type="password" 
                                name="password" 
                                id="password" 
                                class="form-control @error('password') is-invalid @enderror" 
                                placeholder="Mínimo 6 caracteres" 
                                required
                                style="padding-left: 16px;"
                            >
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Rol -->
                        <div class="form-group">
                            <label for="role" class="form-label">Rol del Usuario <span style="color: #ef4444;">*</span></label>
                            <select name="role" id="role" class="form-control @error('role') is-invalid @enderror" required style="padding-left: 16px; cursor: pointer;">
                                <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>Usuario Estándar (Sin acceso a módulo de usuarios)</option>
                                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Administrador (Control total del sistema)</option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Actions -->
                    <div style="margin-top: 30px; display: flex; justify-content: flex-end; gap: 12px; border-top: 1px solid var(--border-color); padding-top: 20px;">
                        <a href="{{ route('users.index') }}" class="btn-secondary">
                            Cancelar
                        </a>
                        <button type="submit" class="btn-primary" id="btnSaveUser">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="16" height="16">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                            </svg>
                            Crear Usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('avatarInput');
        const preview = document.getElementById('avatarPreview');

        if (input && preview) {
            input.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        preview.src = event.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    });
</script>
@endpush
