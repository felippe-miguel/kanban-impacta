<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CardController extends Controller
{
    public function index()
    {
        return response()->json(['message' => "Listing all cards"]);
    }

    public function store(Request $request)
    {
        return response()->json(['message' => "Storing new card"]);
    }

    public function show($id)
    {
        return response()->json(['message' => "Showing card with ID: $id"]);
    }

    public function update(Request $request, $id)
    {
        return response()->json(['message' => "Updating card with ID: $id"]);
    }

    public function destroy($id)
    {
        return response()->json(['message' => "Deleting card with ID: $id"]);
    }
}
