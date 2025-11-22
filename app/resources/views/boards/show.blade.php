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

                                @if($card->tags && $card->tags->count())
                                    <div class="card-tags mt-2">
                                        @foreach($card->tags as $tag)
                                            <span class="tag-badge tag-{{ $tag->type }}">{{ $tag->name }}</span>
                                        @endforeach
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

                    <div class="mb-3">
                        <label class="form-label">Descrição</label>
                        <p id="modal-card-description"></p>
                    </div>

                    <div class="mb-3 d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label mb-0">Labels</label>
                            <button type="button" class="btn btn-sm btn-outline-light" onclick="openAddTagModal()">
                                <i class="fas fa-plus"></i> Adicionar
                            </button>
                        </div>
                        <div id="modal-tags-list" class="card-tags"></div>
                    </div>

                    <div class="mb-3 mt-4">
                        <label for="comment-content" class="form-label">Novo comentário</label>
                        <textarea class="form-control" id="comment-content" name="content" rows="2" required></textarea>
                        <button type="button" class="btn btn-primary mt-2" id="commentCardBtn">Comentar</button>
                    </div>

                    <!-- Tabbed Interface for Comments and History -->
                    <div class="mb-3">
                        <ul class="nav nav-tabs bg-dark" id="cardTabsNav" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active text-light" id="comments-tab" data-bs-toggle="tab" data-bs-target="#comments-panel" type="button" role="tab" aria-controls="comments-panel" aria-selected="true">
                                    <i class="fas fa-comments"></i> Comentários
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link text-light" id="history-tab" data-bs-toggle="tab" data-bs-target="#history-panel" type="button" role="tab" aria-controls="history-panel" aria-selected="false">
                                    <i class="fas fa-history"></i> Histórico
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content bg-dark p-3 border border-top-0 border-secondary" id="cardTabsContent">
                            <!-- Comments Tab -->
                            <div class="tab-pane fade show active" id="comments-panel" role="tabpanel" aria-labelledby="comments-tab">
                                <ul id="comments-list" class="list-group bg-dark"></ul>
                            </div>

                            <!-- History Tab -->
                            <div class="tab-pane fade" id="history-panel" role="tabpanel" aria-labelledby="history-tab">
                                <ul id="history-list" class="list-group bg-dark"></ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Adicionar Tag ao Card -->
    <div class="modal fade" id="addTagModal" tabindex="-1" aria-labelledby="addTagModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="add-tag-form">
                @csrf
                <div class="modal-content bg-dark text-light">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addTagModalLabel">Adicionar Tag ao Card</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="tag-name" class="form-label">Nome da Tag</label>
                            <input type="text" id="tag-name" name="name" class="form-control" required />
                        </div>
                        <div class="mb-3">
                            <label for="tag-type" class="form-label">Tipo</label>
                            <select id="tag-type" name="type" class="form-select">
                                <option value="warning" selected>warning</option>
                                <option value="success">success</option>
                                <option value="danger">danger</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Adicionar</button>
                    </div>
                </div>
            </form>
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
                    loadComments(currentCardId);
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
            loadComments(cardId);
            loadTags(cardId);
            loadHistory(cardId);
        }

        function loadComments(cardId) {
            const commentsList = document.getElementById('comments-list');
            commentsList.innerHTML = '<li class="list-group-item bg-dark text-light">Carregando...</li>';
            fetch(`/cards/${cardId}/comments`)
                .then(res => res.json())
                .then(comments => {
                    commentsList.innerHTML = '';
                    if (comments.length === 0) {
                        commentsList.innerHTML = '<li class="list-group-item bg-dark text-light">Nenhum comentário.</li>';
                    } else {
                        comments.forEach(comment => {
                            commentsList.innerHTML += `
                                <li class='list-group-item bg-dark text-light border-bottom d-flex justify-content-between align-items-start'>
                                    <div>
                                        <span class='small text-muted'>${comment.created_at}</span><br>${comment.content}
                                    </div>
                                    <button class='btn btn-link text-muted p-0 ms-2' onclick='deleteComment(${comment.id})' title='Deletar comentário'>
                                        <i class='fas fa-trash'></i>
                                    </button>
                                </li>
                            `;
                        });
                    }
                });
        }

        function loadTags(cardId) {
            const tagsContainer = document.getElementById('modal-tags-list');
            if (!tagsContainer) return;
            tagsContainer.innerHTML = '<div class="small text-muted">Carregando...</div>';
            fetch(`/cards/${cardId}/tags`)
                .then(res => res.json())
                .then(tags => {
                    tagsContainer.innerHTML = '';
                    if (!tags || tags.length === 0) {
                        tagsContainer.innerHTML = '<div class="small text-muted">Nenhuma label.</div>';
                        return;
                    }
                    tags.forEach(tag => {
                        // badge wrapper
                        const span = document.createElement('span');
                        span.className = `tag-badge tag-${tag.type}`;
                        span.textContent = tag.name;

                        // remove button only inside modal
                        const btn = document.createElement('button');
                        btn.type = 'button';
                        btn.className = 'tag-remove-btn';
                        btn.title = 'Remover tag deste card';
                        btn.innerHTML = '&times;';
                        btn.addEventListener('click', function(e) {
                            e.stopPropagation();
                            detachTagFromCard(cardId, tag.id);
                        });

                        span.appendChild(btn);
                        tagsContainer.appendChild(span);
                    });

                    // Also update the card element on the board so tags appear outside the modal
                    renderTagsOnCard(cardId, tags);
                }).catch(err => {
                    tagsContainer.innerHTML = '<div class="small text-muted">Erro ao carregar labels.</div>';
                    console.error('Error loading tags', err);
                });
        }

        function detachTagFromCard(cardId, tagId) {
            const token = document.querySelector('input[name="_token"]').value;
            Swal.fire({
                title: 'Remover tag?',
                text: 'Deseja remover esta tag deste card?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sim, remover',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (!result.isConfirmed) return;
                fetch(`/cards/${cardId}/tags/${tagId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Content-Type': 'application/json'
                    }
                }).then(res => {
                    if (res.ok) {
                        // Refresh modal tags and card tags
                        loadTags(cardId);
                        // Optionally, you can also remove and re-render tags on the card
                        // by fetching tags (loadTags will call renderTagsOnCard)
                        Swal.fire({
                            title: 'Tag removida com sucesso!',
                            icon: 'success',
                        });
                    } else {
                        Swal.fire({ title: 'Erro', text: 'Não foi possível remover a tag.', icon: 'error' });
                    }
                }).catch(err => {
                    console.error('Error detaching tag', err);
                    Swal.fire({ title: 'Erro', text: 'Não foi possível remover a tag.', icon: 'error' });
                });
            });
        }

        function renderTagsOnCard(cardId, tags) {
            try {
                const cardEl = document.querySelector(`.card[data-card-id="${cardId}"]`);
                if (!cardEl) return;
                // find existing tags container inside card
                let tagsContainer = cardEl.querySelector('.card-tags');
                if (!tagsContainer) {
                    // create container and insert after description if present, otherwise at end of card-body
                    tagsContainer = document.createElement('div');
                    tagsContainer.className = 'card-tags mt-2';
                    const cardText = cardEl.querySelector('.card-text');
                    if (cardText && cardText.parentNode) {
                        cardText.parentNode.insertBefore(tagsContainer, cardText.nextSibling);
                    } else {
                        const cardBody = cardEl.querySelector('.card-body');
                        cardBody.appendChild(tagsContainer);
                    }
                }
                // clear and render
                tagsContainer.innerHTML = '';
                tags.forEach(tag => {
                    const span = document.createElement('span');
                    span.className = `tag-badge tag-${tag.type}`;
                    span.textContent = tag.name;
                    tagsContainer.appendChild(span);
                });
            } catch (err) {
                console.error('Error rendering tags on card', err);
            }
        }

        function openAddTagModal() {
            // Require a currently selected card
            if (!currentCardId) {
                Swal.fire({ title: 'Erro', text: 'Nenhum card selecionado.', icon: 'error' });
                return;
            }
            // reset form
            document.getElementById('tag-name').value = '';
            document.getElementById('tag-type').value = 'warning';
            var modal = new bootstrap.Modal(document.getElementById('addTagModal'));
            modal.show();
        }

        document.getElementById('add-tag-form').addEventListener('submit', function(e) {
            e.preventDefault();
            if (!currentCardId) return;
            const name = document.getElementById('tag-name').value.trim();
            const type = document.getElementById('tag-type').value;
            if (!name) return;
            const token = document.querySelector('input[name="_token"]').value;
            fetch(`/cards/${currentCardId}/tags`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({ name, type })
            }).then(res => res.json())
              .then(data => {
                  // close modal
                  var modalEl = document.getElementById('addTagModal');
                  var modal = bootstrap.Modal.getInstance(modalEl);
                  if (modal) modal.hide();
                  // reload tags in card modal
                  loadTags(currentCardId);
                  Swal.fire({
                            title: 'Tag adicionada com sucesso!',
                            icon: 'success',
                        });
              }).catch(err => {
                  console.error('Error adding tag', err);
                  Swal.fire({ title: 'Erro', text: 'Não foi possível adicionar a tag.', icon: 'error' });
              });
        });

        function deleteComment(commentId) {
            Swal.fire({
                title: 'Tem certeza?',
                text: 'Essa ação irá deletar o comentário permanentemente!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sim, deletar!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                const token = document.querySelector('input[name="_token"]').value;
                fetch(`/comments/${commentId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': token
                    }
                }).then(res => {
                    if (res.ok) {
                        Swal.fire({
                            title: 'Comentário deletado com sucesso!',
                            icon: 'success',
                        });
                        loadComments(currentCardId);
                    }
                });
            });
        }

        function loadHistory(cardId) {
            const historyList = document.getElementById('history-list');
            if (!historyList) return;
            historyList.innerHTML = '<li class="list-group-item bg-dark text-light">Carregando...</li>';
            fetch(`/cards/${cardId}/history`)
                .then(res => res.json())
                .then(history => {
                    historyList.innerHTML = '';
                    if (!history || history.length === 0) {
                        historyList.innerHTML = '<li class="list-group-item bg-dark text-light">Nenhum histórico.</li>';
                        return;
                    }
                    history.forEach(entry => {
                        const actionText = getActionText(entry.action, entry.description);
                        const actionLabel = getActionLabel(entry.action);
                        const timeStr = new Date(entry.created_at).toLocaleString('pt-BR');
                        const li = document.createElement('li');
                        li.className = 'list-group-item bg-dark text-light border-bottom';

                        // Build HTML for history entry
                        let badgesHtml = '';
                        if (entry.action === 'moved' && entry.old_value && entry.new_value) {
                            badgesHtml = `
                                <div class="mt-2">
                                    <span class="badge bg-warning text-dark me-2">${entry.old_value}</span>
                                    <i class="fas fa-arrow-right text-muted"></i>
                                    <span class="badge bg-info text-dark ms-2">${entry.new_value}</span>
                                </div>
                            `;
                        }

                        li.innerHTML = `
                            <div class="d-flex justify-content-between align-items-start">
                                <div style="flex-grow: 1;">
                                    <span class="badge ${actionLabel.class}">${actionLabel.title}</span>
                                    <p class="mb-1 mt-2">${actionText}</p>
                                    ${badgesHtml}
                                    <span class="small text-muted">${timeStr}</span>
                                </div>
                            </div>
                        `;
                        historyList.appendChild(li);
                    });
                }).catch(err => {
                    historyList.innerHTML = '<li class="list-group-item bg-dark text-light">Erro ao carregar histórico.</li>';
                    console.error('Error loading history', err);
                });
        }

        function getActionText(action, description) {
            if (description) return description;
            const actionMap = {
                'created': 'Card foi criado',
                'moved': 'Card foi movido de coluna',
                'tag_added': 'Tag foi adicionada',
                'tag_removed': 'Tag foi removida'
            };
            return actionMap[action] || `Ação: ${action}`;
        }

        function getActionLabel(action) {
            const actionMap = {
                'created': {
                    title: 'Card criado',
                    class: 'bg-success text-light'
                },
                'moved': {
                    title: 'Card movido',
                    class: 'bg-secondary text-light'
                },
                'tag_added': {
                    title: 'Tag adicionada',
                    class: 'bg-success text-light'
                },
                'tag_removed': {
                    title: 'Tag removida',
                    class: 'bg-danger text-light'
                }
            };
            return actionMap[action] || `Ação: ${action}`;
        }
    </script>
    <style>
        .text-muted {
            color: #7b8691bf !important;
        }
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

        .card-tags {
            margin: 0.5rem;
        }

        .card-tags .tag-badge {
            padding: 0.15rem 0.5rem;
            font-size: 0.75rem;
            border-radius: 999px;
            display: inline-block;
            box-shadow: inset 0 -1px 0 rgba(0, 0, 0, 0.12);
            line-height: 1;
            border: 1px solid rgba(0,0,0,0.18);
            margin-right: 0.25rem;
        }

        .card-tags .tag-badge.tag-warning {
            background: #3b3221;
            color: #d29922;
            border-color: #634711;
        }

        .card-tags .tag-badge.tag-success {
            background: #213b24;
            color: #2bd222;
            border-color: #116328;
        }

        .card-tags .tag-badge.tag-danger {
            background: #3b2121;
            color: #d56262;
            border-color: #631111;
        }

        /* remove button shown inside modal badges */
        .tag-remove-btn {
            background: transparent;
            border: none;
            color: rgba(255,255,255,0.85);
            margin-left: 8px;
            padding: 0 0.25rem;
            font-size: 0.85rem;
            line-height: 1;
            cursor: pointer;
        }
        .tag-remove-btn:hover { color: #fff; }

        /* Tabs styling */
        .nav-tabs {
            border-bottom: 1px solid #495057;
        }

        .nav-tabs .nav-link {
            border: 1px solid transparent;
            border-bottom: none;
            color: rgba(255,255,255,0.7) !important;
            background-color: #2d2e37;
            border-radius: 0.375rem 0.375rem 0 0;
        }

        .nav-tabs .nav-link:hover {
            border-color: #495057;
            color: #fff !important;
        }

        .nav-tabs .nav-link.active {
            color: #fff !important;
            background-color: #212529;
            border-color: #495057 #495057 #212529;
        }

        .tab-content {
            border-radius: 0 0 0.375rem 0.375rem;
        }

        .tab-pane {
            min-height: 300px;
        }

    </style>
@endsection
