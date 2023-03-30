<?php

namespace App\Http\Controllers;

use App\Models\Lot;
use Illuminate\Http\Request;

class LotController extends Controller
{
    public function index(Request $request)
    {
        $lots = Lot::with('categories')->when($request->category, function ($query, $category) {
            return $query->whereHas('categories', function ($query) use ($category) {
                $query->where('name', $category);
            });
        })->get();

        return response()->json($lots);
    }

    public function show(Lot $lot)
    {
        $lot->load('categories');
        return response()->json($lot);
    }

    public function store(Request $request)
    {
        $lot = Lot::create($request->all());
        $lot->categories()->sync($request->categories);
        return response()->json($lot, 201);
    }

    public function update(Request $request, Lot $lot)
    {
        $lot->update($request->all());
        $lot->categories()->sync($request->categories);
        return response()->json($lot);
    }

    public function destroy(Lot $lot)
    {
        $lot->delete();
        return response()->json(null, 204);
    }
}
