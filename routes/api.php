<?php

use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FacilityController;
use App\Http\Controllers\Api\RateController;
use App\Http\Controllers\Api\DiscountController;
use App\Http\Controllers\Api\GuestMonitoringController;

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
    Route::put('/facility-types/{id}', [FacilityController::class, 'editFacilityType']);
    Route::delete('/facilities/{id}', [FacilityController::class, 'archiveFacility']);
    Route::delete('/facility-types/{id}', [FacilityController::class, 'archiveFacilityType']);
    Route::get('/facilities/archived', [FacilityController::class, 'viewArchivedFacilities']);
    Route::get('/facility-types/archived', [FacilityController::class, 'viewArchivedFacilityTypes']);
    Route::patch('/facilities/{id}/restore', [FacilityController::class, 'restoreFacility']);
    Route::patch('/facility-types/{id}/restore', [FacilityController::class, 'restoreFacilityType']);
    
    //Rate Management CRUD Routes
    Route::get('/rates', [RateController::class, 'getAllRates']);
    Route::post('/rates', [RateController::class, 'addRate']);
    Route::patch('/rates/{id}/restore', [RateController::class, 'restoreRate']);
    Route::put('/rates/{id}', [RateController::class, 'editRate']);
    Route::delete('/rates/{id}', [RateController::class, 'archiveRate']);
    Route::get('/rates/archived', [RateController::class, 'viewArchivedRates']);

    //Discount CRUD Routes
    Route::get('/discounts/{id}',  [DiscountController::class, 'show']);
    Route::get('/discounts',  [DiscountController::class, 'getAllDiscounts']);
    Route::post('/discounts',  [DiscountController::class, 'addDiscount']);
    Route::put('/discounts/{id}',  [DiscountController::class, 'editDiscount']);
    Route::delete('/discounts/{id}',  [DiscountController::class, 'archiveDiscount']);
    Route::patch('/discounts/{id}/restore',  [DiscountController::class, 'restoreDiscount']);
    Route::get('/discounts/archived', [DiscountController::class, 'viewArchivedDiscounts']);

    //Guest Monitoring CRUD Routes
    Route::get('/guest-monitoring', [GuestMonitoringController::class, 'getAllGuestMonitoring']);
    Route::get('/guest-monitoring/{id}', [GuestMonitoringController::class, 'show']);
    Route::post('/guest-monitoring', [GuestMonitoringController::class, 'addGuestMonitoring']);
    Route::put('/guest-monitoring/{id}', [GuestMonitoringController::class, 'editGuestMonitoring']);
    Route::delete('/guest-monitoring/{id}', [GuestMonitoringController::class, 'deleteGuestMonitoring']);
    Route::patch('/guest-monitoring/{id}/restore', [GuestMonitoringController::class, 'restoreGuestMonitoring']);
    Route::get('/guest-monitoring/archived', [GuestMonitoringController::class, 'viewArchivedGuestMonitoring']);
    Route::patch('/guest-monitoring/{id}/status', [GuestMonitoringController::class, 'statusChangeGuestMonitoring']);

});
    