<?php

use App\Http\Controllers\StepController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\QuestionnaireController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () {
//    return view('welcome');
//});

//excel转换格式队列
//Route::get('/', [UserController::class, 'index']);
//队列监控页面
//Route::get('/queue-monitor', [QueueMonitorController::class, 'index']);




Auth::routes();
// 首页
Route::get('/', [StepController::class, 'currentStep'])->name('home');
Route::get('/complete', [StepController::class, 'complete'])->name('complete');

// 登录/注册路由
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// 保护路由
Route::middleware(['auth'])->group(function () {
    Route::get('/step/{step}', [StepController::class, 'show'])->name('step.show');
});
Route::post('/questionnaires/{step}/submit', [QuestionnaireController::class, 'store'])
    ->name('questionnaire.submit');
// 在受保护路由组内
Route::middleware(['auth'])->group(function () {
    Route::post('/video-progress', [StepController::class, 'updateVideoProgress'])->name('video-progress.update');
    Route::get('/progress/{step}', [StepController::class, 'getProgress'])->name('progress.get');
});

// 管理员路由
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('progress', [AdminController::class, 'progressList'])->name('admin.progress');
    Route::put('progress/{userId}', [AdminController::class, 'updateRole'])->name('admin.updateRole');
});

