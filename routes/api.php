<?php
require __DIR__.'/../vendor/autoload.php';
use App\Http\Controllers\CourseController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('api')->group(function () {
    Route::apiResource('/courses', CourseController::class);
    
    Route::get('test404', function () {
        throw new ModelNotFoundException();
    });
});