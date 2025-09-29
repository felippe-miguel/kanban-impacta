<?php

namespace App\Http\Controllers;

use App\Http\Resources\BoardResource;
use App\Models\Board;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    public function index()
    {
        $boards = Board::all();
        return view('boards.index', ['boards' => BoardResource::collection($boards)]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $board = Board::create([
            'title' => $request->input('title'),
        ]);

        $board->columns()->create(['title' => 'To Do']);
        $board->columns()->create(['title' => 'In Progress']);
        $board->columns()->create(['title' => 'Done']);

        return redirect()->route('boards.index')->with('success', 'Quadro criado com sucesso.');
    }

    public function show($id)
    {
        $board = Board::findOrFail($id);
        $columns = $board->columns()->with(['cards' => function($query) {
            $query->orderBy('updated_at', 'desc');
        }])->get();
        return view('boards.show', compact('board', 'columns'));
    }

    public function update(Request $request, $id)
    {
        return response()->json(['message' => "Updating board with ID: $id"]);
    }

    public function destroy($id)
    {
        $board = Board::findOrFail($id);
        $board->delete();

        return redirect()->route('boards.index')->with('success', 'Quadro deletado com sucesso.');
    }
}
