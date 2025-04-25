<!-- /////////////////////////create.blade.php////////////////////////// -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Crear Usuario</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.0/dist/tailwind.min.css">
</head>

<body class="bg-gray-100">
    <div class="container mx-auto p-8">
        <h1 class="text-3xl font-bold mb-6">Crear Nuevo Usuario</h1>
        <form action="{{ route('users.store') }}" method="POST" class="bg-white p-6 rounded-lg shadow-md">
            @csrf
            <div class="mb-4">
                <label for="name" class="block text-gray-700 font-bold">Nombre</label>
                <input type="text" name="name" id="name" class="border border-gray-300 p-2 w-full rounded" value="{{ old('name') }}" required>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-bold">Correo Electrónico</label>
                <input type="email" name="email" id="email" class="border border-gray-300 p-2 w-full rounded" value="{{ old('email') }}" required>
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-gray-700 font-bold">Teléfono</label>
                <input type="text" name="phone" id="phone" class="border border-gray-300 p-2 w-full rounded" value="{{ old('phone') }}">
            </div>
            <div class="mb-4">
                <label for="address" class="block text-gray-700 font-bold">Dirección</label>
                <input type="text" name="address" id="address" class="border border-gray-300 p-2 w-full rounded" value="{{ old('address') }}">
            </div>
            <div class="mb-4">
                <label for="usertype" class="block text-gray-700 font-bold">Rol</label>
                <select name="usertype" id="usertype" class="border border-gray-300 p-2 w-full rounded" required>
                    <option value="user" {{ old('usertype') == 'user' ? 'selected' : '' }}>Usuario</option>
                    <option value="admin" {{ old('usertype') == 'admin' ? 'selected' : '' }}>Administrador</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="password" class="block text-gray-700 font-bold">Contraseña</label>
                <input type="password" name="password" id="password" class="border border-gray-300 p-2 w-full rounded" required>
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="block text-gray-700 font-bold">Confirmar Contraseña</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="border border-gray-300 p-2 w-full rounded" required>
            </div>

            <div class="flex items-center">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-4">Crear Usuario</button>
                <a href="{{ route('users.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Cancelar</a>
            </div>
        </form>
    </div>
</body>

</html>

<!-- /////////////////////////index.blade.php////////////////////////// -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Gestión de Usuarios</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.0/dist/tailwind.min.css">
</head>

<body class="bg-gray-100">
    <div class="container mx-auto p-8">
        <h1 class="text-3xl font-bold mb-6">Gestión de Usuarios</h1>
        <a href="{{ route('users.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 inline-block">Añadir Usuario</a>
        <table class="table-auto w-full bg-white rounded-lg shadow-md">
            <thead>
                <tr class="bg-gray-200 text-gray-700">
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Nombre</th>
                    <th class="px-4 py-2">Email</th>
                    <th class="px-4 py-2">Teléfono</th>
                    <th class="px-4 py-2">Dirección</th>
                    <th class="px-4 py-2">Rol</th>
                    <th class="px-4 py-2">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td class="border px-4 py-2">{{ $user->id }}</td>
                        <td class="border px-4 py-2">{{ $user->name }}</td>
                        <td class="border px-4 py-2">{{ $user->email }}</td>
                        <td class="border px-4 py-2">{{ $user->phone ?? 'N/A' }}</td>
                        <td class="border px-4 py-2">{{ $user->address ?? 'N/A' }}</td>
                        <td class="border px-4 py-2">{{ ucfirst($user->usertype) }}</td>
                        <td class="border px-4 py-2">
                            <a href="{{ route('users.edit', $user) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Editar</a>
                            <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="return confirm('¿Estás seguro de eliminar este usuario?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center border px-4 py-2">No hay usuarios registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>

</html>
<!--//////////////////////////end index.blade.php////////////////////////// -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Editar Usuario</h1>
    <form action="{{ route('users.update', $user) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Nombre</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required>
            @error('name')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required>
            @error('email')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Nueva Contraseña (Opcional)</label>
            <input type="password" name="password" id="password" class="form-control">
            @error('password')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirmar Nueva Contraseña</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">Teléfono</label>
            <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
        </div>

        <div class="mb-3">
            <label for="address" class="form-label">Dirección</label>
            <textarea name="address" id="address" class="form-control">{{ old('address', $user->address) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="usertype" class="form-label">Tipo de Usuario</label>
            <select name="usertype" id="usertype" class="form-control">
                <option value="user" {{ $user->usertype === 'user' ? 'selected' : '' }}>Usuario</option>
                <option value="admin" {{ $user->usertype === 'admin' ? 'selected' : '' }}>Administrador</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Actualizar</button>
    </form>
</div>
@endsection
