<?php

use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FacilityController;
use App\Http\Controllers\Api\RateController;

//Accessing the login route without authentication gives token if credentials are correct
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,0.5');

// Routes that needs token authentication to access
Route::group(['middleware' => 'auth:sanctum'], function () {
    
    //Displays the authenticated user details
    Route::get('/user', [UserController::class, 'profile']);

    //Logout route to revoke the token and end the session of the logged in user
    Route::post('/logout', [AuthController::class, 'logout']);


    //User CRUD Routes
    Route::get('/users', [UserController::class, 'getAllUsers']);
    Route::post('/users', [UserController::class, 'addUser']);
    Route::put('/users/{id}', [UserController::class, 'editUser']);
    Route::delete('/users/{id}', [UserController::class, 'deactivateUser']);
    Route::patch('/users/{id}', [UserController::class, 'activateUser']);
    Route::get('/users/deactivated', [UserController::class, 'getDeactivatedUsers']);

    //Facility CRUD Routes
    Route::get('/facilities', [FacilityController::class, 'getAllFacilities']);
    Route::get('/facility-types', [FacilityController::class, 'getAllFacilityTypes']);
    Route::post('/facilities', [FacilityController::class, 'addFacility']);
    Route::post('/facility-types', [FacilityController::class, 'addFacilityType']);
    Route::put('/facilities/{id}', [FacilityController::class, 'editFacility']);
    Route::delete('/facilities/{id}', [FacilityController::class, 'archiveFacility']);
    Route::get('/facilities/archived', [FacilityController::class, 'viewArchivedFacilities']);
    Route::patch('/facilities/{id}/restore', [FacilityController::class, 'restoreFacility']);
    
    //Rate Management CRUD Routes
    Route::get('/rates', [RateController::class, 'getAllRates']);
    Route::get('/rates/entrance-fees', [RateController::class, 'getEntranceFees']);
    Route::get('/rates/exclusive', [RateController::class, 'getExclusiveRates']);
    Route::post('/rates', [RateController::class, 'addRate']);
    Route::patch('/rates/{id}', [RateController::class, 'archiveRate']);
    Route::put('/rates/{id}', [RateController::class, 'editRate']);
    Route::delete('/rates/{id}/restore', [RateController::class, 'restoreRate']);
    Route::get('/rates/archived', [RateController::class, 'viewArchivedRates']);
});
    