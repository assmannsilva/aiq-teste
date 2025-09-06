<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchFavoritesFormRequest;
use App\Http\Requests\StoreFavoriteFormRequest;
use App\Services\FavoriteService;
use Illuminate\Http\Request;

class FavoritesController extends Controller
{

    public function __construct(private FavoriteService $favoriteService) {}

    /**
     * @OA\Get(
     *   path="/favorites",
     *   summary="Lista os produtos favoritos do cliente autenticado",
     *   tags={"Favorites"},
     *   security={{"sanctum": {}}},
     *   @OA\Parameter(
     *     name="per_page",
     *     in="query",
     *     description="Quantidade de itens por página (máximo 100)",
     *     required=false,
     *     @OA\Schema(type="integer", example=10)
     *   ),
     *   @OA\Parameter(
     *     name="page",
     *     in="query",
     *     description="Número da página",
     *     required=false,
     *     @OA\Schema(type="integer", example=1)
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Lista de favoritos paginada",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/FavoriteProduct")),
     *       @OA\Property(property="current_page", type="integer", example=1),
     *       @OA\Property(property="per_page", type="integer", example=10),
     *       @OA\Property(property="total", type="integer", example=50),
     *       @OA\Property(property="last_page", type="integer", example=5),
     *       @OA\Property(property="path", type="string", example="http://localhost:8000/api/favorites"),
     *       @OA\Property(property="query", type="object", example={"per_page": 10})
     *     )
     *   ),
     *   @OA\Response(response=401, description="Não autenticado")
     * )
     */
    public function index(SearchFavoritesFormRequest $request)
    {
        $client = $request->user();
        $perPage = $request->input("per_page");

        $products = $this->favoriteService->listClientFavorites($client, $perPage);
        return \response()->json($products);
    }

    /**
     * @OA\Post(
     *   path="/favorites",
     *   summary="Adiciona um produto aos favoritos do cliente autenticado",
     *   tags={"Favorites"},
     *   security={{"sanctum": {}}},
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       required={"product_id"},
     *       @OA\Property(property="product_id", type="integer", example=999)
     *     )
     *   ),
     *   @OA\Response(
     *     response=201,
     *     description="Produto adicionado aos favoritos",
     *     @OA\JsonContent(ref="#/components/schemas/FavoriteProduct")
     *   ),
     *   @OA\Response(response=422, description="Erro de validação"),
     *   @OA\Response(response=401, description="Não autenticado")
     * )
     */
    public function store(StoreFavoriteFormRequest $request)
    {
        $client = $request->user();
        $product = $this->favoriteService->addFavoriteIntoClient($client, $request->input("product_id"));
        return \response()->json($product, 201);
    }

    /**
     * @OA\Get(
     *   path="/favorites/{id}",
     *   summary="Exibe um produto favorito específico",
     *   tags={"Favorites"},
     *   security={{"sanctum": {}}},
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     description="ID do produto favorito",
     *     @OA\Schema(type="integer", example=999)
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Produto favorito encontrado",
     *     @OA\JsonContent(ref="#/components/schemas/FavoriteProduct")
     *   ),
     *   @OA\Response(response=404, description="Favorito não encontrado"),
     *   @OA\Response(response=401, description="Não autenticado")
     * )
     */
    public function show(Request $request, string $productId)
    {
        $client = $request->user();
        $product = $this->favoriteService->getFavoriteProduct($client, $productId);
        if ($product) return \response()->json($product);
        return \response()->json(["message" => "Favorite not found"], 404);
    }

    /**
     * @OA\Delete(
     *   path="/favorites/{id}",
     *   summary="Remove um produto da lista de favoritos",
     *   tags={"Favorites"},
     *   security={{"sanctum": {}}},
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     description="ID do produto favorito",
     *     @OA\Schema(type="integer", example=999)
     *   ),
     *   @OA\Response(response=204, description="Removido com sucesso (sem conteúdo)"),
     *   @OA\Response(response=404, description="Favorito não encontrado"),
     *   @OA\Response(response=401, description="Não autenticado")
     * )
     */

    public function destroy(Request $request, string $productId)
    {
        $client = $request->user();
        $deleted = $this->favoriteService->deleteFavorite($client, $productId);
        if ($deleted) return \response()->json(null, 204);
        return \response()->json(["message" => "Favorite not found"], 404);
    }
}
