<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TransaccionController;
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/transacciones', [TransaccionController::class, 'store']);
    Route::patch('/transacciones/{transaccion}', [TransaccionController::class, 'update']);
});
