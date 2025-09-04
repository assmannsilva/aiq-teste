<?php

use App\Http\Controllers\ClientsController;
use Illuminate\Support\Facades\Route;


Route::post("/clients", [ClientsController::class, "store"]);

Route::middleware("auth:sanctum")->group(function () {
    Route::get("/clients/me", [ClientsController::class, "show"]);
    Route::patch("/clients/me", [ClientController::class, "update"]);
    Route::delete("/clients/me", [ClientController::class, "destroy"]);
});
