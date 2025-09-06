<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFavoriteFormRequest;
use App\Services\FavoriteService;
use Illuminate\Http\Request;

class FavoritesController extends Controller
{

    public function __construct(private FavoriteService $favoriteService) {}
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $client = $request->user();
        $perPage = $request->input("per_page");

        $products = $this->favoriteService->listClientFavorites($client, $perPage);
        return \response()->json($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFavoriteFormRequest $request)
    {
        $client = $request->user();
        $product = $this->favoriteService->addFavoriteIntoClient($client, $request->input("product_id"));
        return \response()->json($product, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $productId)
    {
        $deleted = $this->favoriteService->deleteFavorite($productId);
        if ($deleted) return \response()->json(null, 204);
        return \response()->json(["message" => "Favorite not found"], 404);
    }
}
