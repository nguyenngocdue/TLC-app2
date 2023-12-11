<?php

use App\Http\Controllers\ExamQuestion\ExamQuestionController;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['auth', 'impersonate'],
], function () {
    Route::post('exam-question', [ExamQuestionController::class, 'update'])->name('exam-question.update');
});
