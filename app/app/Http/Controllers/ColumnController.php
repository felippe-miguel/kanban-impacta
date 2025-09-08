<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ColumnController extends Controller
{
    public function index()
    {
        return response()->json(['message' => "Listing all columns"]);
    }

    public function store(Request $request)
    {
        return response()->json(['message' => "Storing new column"]);
    }

    public function show($id)
    {
        return response()->json(['message' => "Showing column with ID: $id"]);
    }

    public function update(Request $request, $id)
    {
        return response()->json(['message' => "Updating column with ID: $id"]);
    }

    public function destroy($id)
    {
        return response()->json(['message' => "Deleting column with ID: $id"]);
    }
}
