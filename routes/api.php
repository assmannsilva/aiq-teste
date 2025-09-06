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
});
