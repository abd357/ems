<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ManagerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get('/', function (Request $request) {
    return "API".$request->user() ."  s" ;
})->middleware('auth:sanctum');


Route::apiResource('employee', EmployeeController::class)->middleware('auth:sanctum');
Route::apiResource('manager', ManagerController::class)->middleware('auth:sanctum');
Route::apiResource('department', DepartmentController::class)->middleware('auth:sanctum');

Route::post('/create', [AdminController::class, 'createEmployee'])->middleware('auth:sanctum');
Route::put('/edit/employee/{employee}', [AdminController::class, 'editEmployee'])->middleware('auth:sanctum');
Route::post('/employee-count', [AdminController::class, 'getEmployeeCount']);
Route::post('/manager-count', [AdminController::class, 'getManagerCount']);
Route::post('/department-count', [AdminController::class, 'getDepartmentCount']);
Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
    });
});
