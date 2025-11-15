<?php

namespace App\Http\Controllers;

use App\Events\CardUpdated;
use App\Models\Board;
use App\Models\Card;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function index()
    {
        return response()->json(['message' => "Listing all cards"]);
    }

    public function store(Request $request, $boardId)
    {
        $card = Card::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'column_id' => $request->input('column_id'),
        ]);

        CardUpdated::dispatch(
            $card,
            'created',
            "Card '{$card->title}' foi criado."
        );

        $board = Board::findOrFail($boardId);

        $columns = $board->columns()->with('cards')->get();

        return redirect()
            ->route('boards.show', compact('board', 'columns'))
            ->with('success', 'Card criado com sucesso.');
    }

    public function show($id)
    {
        return response()->json(['message' => "Showing card with ID: $id"]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'column_id' => 'sometimes|exists:columns,id',
        ]);

        $card = Card::findOrFail($id);
        $oldColumnId = $card->column_id;

        $card->update($request->only(['title', 'description', 'column_id']));

        if ($oldColumnId !== $card->column_id) {
            $oldColumn = $card->column()->first();
            $newColumn = $card->column()->first();
            CardUpdated::dispatch(
                $card,
                'moved',
                "Card '{$card->title}' foi movido da coluna.",
                $oldColumn->title,
                $newColumn->title
            );
        }

        return response()->json(['message' => "Card with ID: $id updated successfully"]);
    }

    public function destroy($boardId, $cardId)
    {
        $card = Card::findOrFail($cardId);
        $board = Board::findOrFail($boardId);
        $columns = $board->columns()->with('cards')->get();
        $card->delete();

        return redirect()
            ->route('boards.show', compact('board', 'columns'))
            ->with('success', 'Card deletado com sucesso.');
    }
}
