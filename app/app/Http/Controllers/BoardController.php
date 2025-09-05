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
        return response()->json(['message' => 'Board created']);
    }

    public function show($id)
    {
        return response()->json(['message' => "Showing board with ID: $id"]);
    }

    public function update(Request $request, $id)
    {
        return response()->json(['message' => "Updating board with ID: $id"]);
    }

    public function destroy($id)
    {
        return response()->json(['message' => "Deleting board with ID: $id"]);
    }
}
