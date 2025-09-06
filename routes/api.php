<?php

use App\Http\Controllers\ClientsController;
use App\Http\Controllers\FavoritesController;
use Illuminate\Support\Facades\Route;


Route::post("/clients", [ClientsController::class, "store"]);

Route::middleware("auth:sanctum")->group(function () {
    Route::get("/clients/me", [ClientsController::class, "show"]);
    Route::patch("/clients/me", [ClientsController::class, "update"]);
    Route::delete("/clients/me", [ClientsController::class, "destroy"]);

    Route::post("/favorites", [FavoritesController::class, "store"]);
    Route::get("/favorites", [FavoritesController::class, "index"]);

    // As seguintes rotas não estão no enunciado do desafio, mas considerei adiciona-las por fazer sentido,
    // E num cenário real, dificilmente não seriam usadas

    Route::delete("/favorites/{id}", [FavoritesController::class, "destroy"]);
    Route::get("/favorites/{id}", [FavoritesController::class, "show"]);
});
