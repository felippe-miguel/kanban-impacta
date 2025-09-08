@extends('layouts.app')

@section('title', 'Lista de quadros')

@section('content')
    <h1>Quadros Cadastrados</h1>
    <button class="btn btn-primary mb-3 float-end" onclick="openAddBoardModal()">Adicionar Novo Quadro</button>
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

        <!-- Modal Adicionar Board -->
        <div class="modal fade" id="addBoardModal" tabindex="-1" aria-labelledby="addBoardModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form id="add-board-form" action="/boards" method="POST">
                        @csrf
                        <div class="modal-content bg-dark text-light">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addBoardModalLabel">Adicionar Novo Quadro</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="board-title" class="form-label">Título do Quadro</label>
                                    <input type="text" class="form-control" id="board-title" name="title" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Criar</button>
                            </div>
                        </div>
                </form>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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

            function openAddBoardModal() {
                var modal = new bootstrap.Modal(document.getElementById('addBoardModal'));
                modal.show();
            }
        </script>
@endsection
