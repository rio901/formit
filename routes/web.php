<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});
Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::get('/dashboard', function () {
    return view('survey.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
use App\Http\Controllers\SurveyController;
Route::get('/guest', [SurveyController::class, 'guest'])->name('guest.index')->middleware('guest');
Route::get('/survey/search', [SurveyController::class, 'search'])->name('survey.search');
Route::view('/survey/complete', 'survey.complete')->name('survey.complete');
Route::post('/surveys/submit_answers', [SurveyController::class, 'storeAnswers'])->name('survey.store-answers');
Route::post('/submit-answers', [SurveyController::class, 'storeAnswers'])->name('submit_answers');
Route::view('/survey/complete', 'survey.complete')->name('survey.complete');

Route::middleware(['auth'])->group(function () {
    // アンケート一覧表示
    Route::get('/index', [SurveyController::class, 'index'])->name('survey.index');
    Route::get('/surveys/create', [SurveyController::class, 'create'])->name('survey.create');
    Route::get('/surveys', [SurveyController::class, 'index'])->name('dashboard');

    // アンケートフォーム表示
    Route::get('/surveys/{surveyId}/form', [SurveyController::class, 'form'])->name('survey.form');
    Route::get('/surveys/{surveyId}/data', [SurveyController::class, 'aggregate'])->name('survey.data');

    // アンケート作成画面表示
    Route::get('/surveys/create', [SurveyController::class, 'create'])->name('survey.create');
    Route::post('/surveys/store', [SurveyController::class, 'storeSurvey'])->name('store_survey');
    Route::view('/survey/questions', 'survey.questions')->name('survey.questions');

    // アンケートの保存（作成）
    Route::post('/store-questions', [SurveyController::class, 'storeQuestions'])->name('store-questions');
    Route::post('/surveys/store-survey', [SurveyController::class, 'storeSurvey'])->name('survey.store-survey');

    

    // フォームの更新
    Route::get('/surveys/{surveyId}/edit', [SurveyController::class, 'editSurvey'])->name('survey.edit');
    Route::put('/surveys/{id}/update', [SurveyController::class, 'update'])->name('survey.update');
    Route::delete('/survey/deleteQuestion', [SurveyController::class, 'deleteQuestion'])->name('survey.deleteQuestion');
    Route::delete('/surveys/{surveyId}', [SurveyController::class, 'deleteSurvey'])->name('survey.delete');

    // 回答状況
    Route::get('/surveys/{surveyId}/result', [SurveyController::class, 'viewResponses'])->name('survey.result');
    Route::get('/download-csv/{surveyId}', [SurveyController::class, 'downloadResponses'])->name('csv.download');
    Route::get('/survey/details/{questionId}', [SurveyController::class, 'showDetails'])->name('survey.details');
    Route::get('/answered-surveys', [SurveyController::class, 'answeredSurveys'])->name('answered.surveys');
    Route::get('/free_comment/{surveyId}', [SurveyController::class, 'showFreeComment'])->name('free_comment');

});