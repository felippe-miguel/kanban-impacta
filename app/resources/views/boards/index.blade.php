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
                        <form id="delete-form-{{ $board->id }}" action="boards/{{ $board->id }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                        </form>
                        <button class="btn btn-danger btn-sm" onclick="confirmDelete({{ $board->id }})">Deletar</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Tem certeza?',
                text: "Essa ação não pode ser desfeita!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sim, deletar!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-form-${id}`).submit();
                }
            });
        }
    </script>
@endsection
