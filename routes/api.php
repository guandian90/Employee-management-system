<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuestionnaireController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\StepController;

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

// 问卷提交路由
Route::post('questionnaires/{questionnaire}/submit', [QuestionnaireController::class, 'submit'])->name('questionnaires.submit');

// 管理员路由（需权限验证）
Route::get('admin/progress', function () {
    return view('admin.progress');
})->middleware('can:view-progress');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
