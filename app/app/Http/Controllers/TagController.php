<?php

namespace App\Http\Controllers;

use App\Events\CardUpdated;
use App\Models\Card;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function store(Request $request, $cardId)
    {
        $request->validate([
            'name' => 'required|string|max:100',
        ]);

        $card = Card::findOrFail($cardId);

        $tag = Tag::firstOrCreate([
            'name' => $request->input('name'),
            'type' => $request->input('type', 'success'),
        ]);

        $card->tags()->syncWithoutDetaching([$tag->id]);

        CardUpdated::dispatch(
            $card,
            'tag_added',
            "Tag '{$tag->name}' foi adicionada ao card.",
            null,
            $tag->name
        );

        return response()->json($tag, 201);
    }

    // Lista tags associadas a um card
    public function list($cardId)
    {
        $card = Card::findOrFail($cardId);
        $tags = $card->tags()->get();
        return response()->json($tags);
    }

    // Deleta uma tag (desassocia e remove a tag)
    public function destroy($id)
    {
        $tag = Tag::findOrFail($id);

        // desassocia de quaisquer cards
        $tag->cards()->detach();

        $tag->delete();

        return response()->json(['message' => 'Tag deletada com sucesso.']);
    }

    // Remove a associação de uma tag de um card, sem deletar a tag
    public function detach($cardId, $tagId)
    {
        $card = Card::findOrFail($cardId);
        $tag = Tag::findOrFail($tagId);

        $card->tags()->detach($tag->id);

        CardUpdated::dispatch(
            $card,
            'tag_removed',
            "Tag '{$tag->name}' foi removida do card.",
            $tag->name,
            null
        );

        return response()->json(['message' => 'Tag desassociada do card com sucesso.']);
    }
}
