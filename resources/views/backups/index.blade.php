@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Gestión de Backups de Base de Datos</h4>
                    <form action="{{ route('backups.create') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary">Crear Nuevo Backup</button>
                    </form>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Archivo</th>
                                <th>Tamaño</th>
                                <th>Fecha de creación</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($backups as $backup)
                                <tr>
                                    <td>{{ $backup['file_name'] }}</td>
                                    <td>{{ round($backup['file_size'] / 1024 / 1024, 2) }} MB</td>
                                    <td>{{ date('d/m/Y H:i:s', $backup['created_at']) }}</td>
                                    <td class="d-flex">
                                        <a href="{{ route('backups.download', ['fileName' => $backup['file_name']]) }}" class="btn btn-sm btn-info mr-2">Descargar</a>

                                        <form action="{{ route('backups.destroy', ['fileName' => $backup['file_name']]) }}" method="POST" class="ml-2">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar este backup?')">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No hay backups disponibles</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection