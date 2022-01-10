<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\QuestionController;
use App\Http\Controllers\API\ReactionController;
use App\Http\Controllers\API\QuestionAnswerController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::post('login', [AuthController::class, 'signin']);
Route::post('register', [AuthController::class, 'signup']);

Route::middleware('auth:sanctum')->group( function () {
    // Question Routes
    Route::resource('questions', QuestionController::class);

    // Question's Answer Routes
    Route::prefix('question')->group( function () {
        Route::get('{question}/answers', [QuestionAnswerController::class, 'index']);
        Route::post('{question}/answers', [QuestionAnswerController ::class, 'store']);
        Route::get('answers/{answer}', [QuestionAnswerController::class, 'show']);
        Route::match(['put', 'patch'], 'answers/{answer}', [QuestionAnswerController::class, 'update']);
        Route::delete('answers/{answer}',[QuestionAnswerController::class, 'destroy']);
    });

    // Manage User Reaction on question & answer
    Route::post('reactions/manage', [ReactionController::class, 'manageReaction']);
});
