@extends('layouts.app')

@section('title', 'Lista de Boards')

@section('content')
    <h1>Boards Cadastrados</h1>
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Criado em</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($boards as $board)
                <tr>
                    <td>{{ $board->id }}</td>
                    <td>{{ $board->title }}</td>
                    <td>{{ $board->created_at }}</td>
                    <td>
                        <form action="boards/{{ $board->id }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja deletar este board?')">Deletar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
