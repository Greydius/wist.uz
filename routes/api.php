<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PassportAuthController;
use App\Http\Controllers\SchoolYearController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentClassroomController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('register', [PassportAuthController::class, 'register']);
Route::post('login', [PassportAuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::put('check', [PassportAuthController::class, 'check']);
});

Route::resource('school-years', SchoolYearController::class);

Route::resource('classrooms', ClassroomController::class);

Route::resource('students', StudentController::class);

Route::prefix('classrooms')->group(function () {
    Route::get('/{id}/students', [ClassroomController::class, 'students']);
});

Route::prefix('students')->group(function () {
    Route::post('/{id}/classrooms/{classroom_id}', [StudentController::class, 'attachClassroom']);
    Route::put('/{id}/classrooms/{classroom_id}', [StudentController::class, 'updateAttachedClassroom']);
    Route::delete('/{id}/classrooms/{classroom_id}', [StudentController::class, 'detachClassroom']);
});

Route::prefix('student-classrooms')->group(function () {
    Route::get('/{id}', [StudentClassroomController::class, 'show']);
    Route::get('/{id}/invoice', [StudentClassroomController::class, 'invoice']);
    Route::post('/{id}/payments', [StudentClassroomController::class, 'addPayment']);
    Route::put('/{id}/payments/{payment_id}', [StudentClassroomController::class, 'updatePayment']);
    Route::delete('/{id}/payments/{payment_id}', [StudentClassroomController::class, 'deletePayment']);
});