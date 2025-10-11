<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, $cardId)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        Comment::create([
            'card_id' => $cardId,
            'content' => $request->input('content'),
        ]);

        return redirect()->back()->with('success', 'Comentário adicionado com sucesso.');
    }

    public function list($cardId)
    {
        $comments = Comment::where('card_id', $cardId)->orderBy('created_at', 'desc')->get();
        return response()->json($comments);
    }

    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();
        return redirect()->back()->with('success', 'Comentário deletado com sucesso.');
    }
}
