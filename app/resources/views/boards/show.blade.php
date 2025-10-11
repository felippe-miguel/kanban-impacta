@extends('layouts.app')

@section('title', 'Quadro Kanban')

@section('content')
    <h1>{{ $board->title }}</h1>
    <button class="btn btn-secondary mb-3 float-end" onclick="backToBoards()">
        <i class="fas fa-arrow-left"></i> Voltar para a listagem
    </button>

    <div class="kanban-board container" style="gap: 20px;">
        @foreach ($columns as $column)
            <div class="col kanban-column bg-dark p-3 rounded position-relative" style="min-width: 250px;" data-column-id="{{ $column->id }}">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h4 class="text-light mb-0">{{ $column->title }}</h4>
                    <div class="dropdown">
                        <button class="btn btn-link text-light p-0" type="button" id="dropdownMenuButton{{ $column->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end bg-dark text-light" aria-labelledby="dropdownMenuButton{{ $column->id }}">
                            <li>
                                <form id="delete-column-form-{{ $column->id }}" action="/boards/{{ $board->id }}/columns/{{ $column->id }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="dropdown-item text-light bg-default " onclick="confirmDeleteColumn({{ $column->id }})">Deletar</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="kanban-cards" data-column-id="{{ $column->id }}">
                    @foreach ($column->cards as $card)
                        <div class="card mb-2 text-light" draggable="true" data-card-id="{{ $card->id }}" data-column-id="{{ $column->id }}" onclick="showCardModal(`{{ addslashes($card->title) }}`, `{{ addslashes($card->description) }}`, {{ $card->id }})">
                            <div class="card-body">
                                <div class="card-title d-flex justify-content-between align-items-center">
                                    <strong>{{ $card->title }}</strong>
                                    <div class="dropdown">
                                        <button class="btn btn-link text-light p-0" type="button" id="dropdownMenuButton{{ $card->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end bg-dark text-light" aria-labelledby="dropdownMenuButton{{ $card->id }}">
                                            <li>
                                                <form id="delete-card-form-{{ $card->id }}" action="/boards/{{ $board->id }}/cards/{{ $card->id }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="dropdown-item text-light bg-default " onclick="confirmDeleteCard({{ $card->id }})">Deletar</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                @if ($card->description)
                                    <div class="card-text">
                                        <p class="mb-0">{{ $card->description }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                    <button class="btn btn-primary addCardButton" onclick="openAddCardModal({{ $column->id }})">
                        <i class="fas fa-plus"></i> Novo card
                    </button>
                </div>
            </div>
        @endforeach
        <button class="btn btn-primary addColumnButton" onclick="openAddColumnModal()">
            <i class="fas fa-plus me-2"></i> Nova coluna
        </button>
    </div>
    <!-- Modal Adicionar Coluna -->
    <div class="modal fade align-content-center" id="addColumnModal" tabindex="-1" aria-labelledby="addColumnModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="add-column-form" action="/boards/{{ $board->id }}/columns" method="POST">
                @csrf
                <div class="modal-content bg-dark text-light">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addColumnModalLabel">Adicionar Nova Coluna</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="column-title" class="form-label">Título da Coluna</label>
                            <input type="text" class="form-control" id="column-title" name="title" required>
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

    <!-- Modal Adicionar Card -->
    <div class="modal fade align-content-center" id="addCardModal" tabindex="-1" aria-labelledby="addCardModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="add-card-form" action="/boards/{{ $board->id }}/cards" method="POST">
                @csrf
                <div class="modal-content bg-dark text-light">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addCardModalLabel">Adicionar Novo Card</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="card-title" class="form-label">Título do Card</label>
                            <input type="text" class="form-control" id="card-title" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="card-description" class="form-label">Descriçao do Card</label>
                            <textarea rows="3" class="form-control" id="card-description" name="description" ></textarea>
                        </div>
                        <input id="card_column_id" type="hidden" name="column_id" value="">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Criar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Exibir Card -->
    <div class="modal fade" id="showCardModal" tabindex="-1" aria-labelledby="showCardModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-dark text-light">
                <div class="modal-header">
                    <h5 class="modal-title" id="showCardModalLabel">Detalhes do Card</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h4 id="modal-card-title"></h4>
                    <p id="modal-card-description"></p>
                    <div class="mb-3 mt-4">
                        <label for="comment-content" class="form-label">Novo comentário</label>
                        <textarea class="form-control" id="comment-content" name="content" rows="2" required></textarea>
                        <button type="button" class="btn btn-primary mt-2" id="commentCardBtn">Comentar</button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js" crossorigin="anonymous"></script>

    <script>
        let currentCardId = null;

        document.addEventListener('DOMContentLoaded', function () {
            let draggedCard = null;
            let sourceColumnId = null;

            document.querySelectorAll('.card[draggable="true"]').forEach(card => {
                card.addEventListener('dragstart', function (e) {
                    draggedCard = this;
                    sourceColumnId = this.getAttribute('data-column-id');
                    e.dataTransfer.effectAllowed = 'move';
                    e.dataTransfer.setData('text/plain', this.getAttribute('data-card-id'));
                    setTimeout(() => this.classList.add('opacity-50'), 0);
                });
                card.addEventListener('dragend', function () {
                    this.classList.remove('opacity-50');
                });
            });

            document.querySelectorAll('.kanban-cards').forEach(column => {
                column.addEventListener('dragover', function (e) {
                    e.preventDefault();
                    this.classList.add('border-primary');
                });
                column.addEventListener('dragleave', function () {
                    this.classList.remove('border-primary');
                });
                column.addEventListener('drop', function (e) {
                    e.preventDefault();
                    this.classList.remove('border-primary');
                    if (draggedCard && this !== draggedCard.parentNode) {
                        this.insertBefore(draggedCard, this.firstChild);
                        const cardId = draggedCard.getAttribute('data-card-id');
                        const newColumnId = this.closest('.kanban-column').getAttribute('data-column-id') || this.parentNode.getAttribute('data-column-id');
                        console.log(`Card ${cardId} movido para a coluna ${newColumnId}`);
                        if (cardId && newColumnId) {
                            fetch(`/api/cards/${cardId}`, {
                                method: 'PUT',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                                },
                                body: JSON.stringify({ column_id: newColumnId })
                            });
                        }
                    }
                });
            });
        });

        document.getElementById('commentCardBtn').addEventListener('click', function() {
            const content = document.getElementById('comment-content').value;
            const token = document.querySelector('input[name="_token"]').value;
            console.log(currentCardId, content);
            if (!currentCardId || !content.trim()) return;
            fetch(`/cards/${currentCardId}/comments`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({ content })
            }).then(res => {
                if (res.ok) {
                    document.getElementById('comment-content').value = '';
                    // Aqui você pode atualizar a lista de comentários se quiser
                }
            });
        });

        function openAddColumnModal() {
            var modal = new bootstrap.Modal(document.getElementById('addColumnModal'));
            modal.show();
        }

        function openAddCardModal(columnId) {
            document.getElementById('card_column_id').value = columnId;
            var modal = new bootstrap.Modal(document.getElementById('addCardModal'));
            modal.show();
        }

        function confirmDeleteColumn(columnId) {
            Swal.fire({
                title: 'Tem certeza?',
                text: 'Essa ação irá deletar a coluna e todos os cards nela!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sim, deletar!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-column-form-${columnId}`).submit();
                }
            });
        }

        function confirmDeleteCard(cardId) {
            Swal.fire({
                title: 'Tem certeza?',
                text: 'Essa ação é irreversível!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sim, deletar!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-card-form-${cardId}`).submit();
                }
            });
        }

        function backToBoards() {
            window.location.href = "{{ route('boards.index') }}";
        }

        function showCardModal(title, description, cardId = null) {
            currentCardId = cardId;
            console.log('Current Card ID set to:', currentCardId);
            document.getElementById('modal-card-title').textContent = title;
            document.getElementById('modal-card-description').textContent = description || '';
            var modal = new bootstrap.Modal(document.getElementById('showCardModal'));
            modal.show();
        }
    </script>
    <style>
        .opacity-50 {
            opacity: 0.5;
        }
        .border-primary {
            border: 2px dashed #7066e0 !important;
        }
        .card-title {
            padding: 0.5rem 1rem;
            margin: 0;
        }
        .card-text {
            padding: 1rem;
            background-color: #282a36;
        }
        .addColumnButton {
            align-self: flex-start;
        }

        .addCardButton {
            width: 100%;
            margin-top: auto
        }

        .kanban-board {
            display: flex;
            flex-wrap: nowrap;
            overflow-x: auto;
            min-height: 600px;
        }
        .kanban-column {
            min-width: 250px;
            max-width: 300px;
            background: #21222c;
        }
        .kanban-cards {
            border: none;
            box-shadow: inset 0 0px 6px rgb(0 0 0 / 16%);
        }

        .kanban-cards {
            background-color: #282a36;
            padding: 0.5rem;
            border-radius: 0.5rem;
            min-height: 93%;
            margin-bottom: 1em;
            border: 1px solid #333;
            display: flex;
            flex-direction: column;
        }

        .card {
            border: 1px solid #333;
            background-color: #212529;
            box-shadow: 0 .125rem .25rem rgb(255 255 255 / 15%) !important;
        }
        .card-body {
            padding: 0;
        }

        .modal-dialog {
            min-width: 500px;
            align-self: center;
        }
    </style>
@endsection
