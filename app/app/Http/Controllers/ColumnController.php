<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\Column;
use Illuminate\Http\Request;

class ColumnController extends Controller
{
    public function index()
    {
        return response()->json(['message' => "Listing all columns"]);
    }

    public function store(Request $request, $boardId)
    {
        Column::create([
            'title' => $request->input('title'),
            'board_id' => $boardId,
        ]);

        $board = Board::findOrFail($boardId);

        $columns = $board->columns()->with('cards')->get();

        return redirect()->route('boards.show', compact('board', 'columns'))->with('success', 'Coluna criada com sucesso.');
    }

    public function show($id)
    {
        return response()->json(['message' => "Showing column with ID: $id"]);
    }

    public function update(Request $request, $id)
    {
        return response()->json(['message' => "Updating column with ID: $id"]);
    }

    public function destroy($boardId, $columnId)
    {
        $column = Column::findOrFail($columnId);
        $board = Board::findOrFail($boardId);
        $columns = $board->columns()->with('cards')->get();
        $column->delete();

        return redirect()->route('boards.show', compact('board', 'columns'))->with('success', 'Coluna deletada com sucesso.');
    }
}
