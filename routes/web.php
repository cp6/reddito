<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

//Fetch new posts
Route::get('/call/get/new/{sub?}/{amount?}/{loops?}', [App\Http\Controllers\FetchController::class, 'getNew'])->name('call.get.new');
Route::get('/call/get/hot/{sub?}/{amount?}/{loops?}', [App\Http\Controllers\FetchController::class, 'getHot'])->name('call.get.hot');

//Update a post
Route::get('/call/update/post/{post}', [App\Http\Controllers\PostController::class, 'update'])->name('call.update.post');

//Post process queue for post counts
Route::get('/call/post-queue/author/{amount?}', [App\Http\Controllers\PostProcessQueueController::class, 'doAuthorCounts'])->name('call.post-queue.author');
Route::get('/call/post-queue/sub/{amount?}', [App\Http\Controllers\PostProcessQueueController::class, 'doSubCounts'])->name('call.post-queue.sub');
Route::get('/call/post-queue/domain/{amount?}', [App\Http\Controllers\PostProcessQueueController::class, 'doDomainCounts'])->name('call.post-queue.domain');

//Get total comment counts
Route::get('/call/count/comments/author/{author}', [App\Http\Controllers\AuthorController::class, 'countComments'])->name('call.count.comments.author');
Route::get('/call/count/comments/sub/{sub}', [App\Http\Controllers\SubController::class, 'countComments'])->name('call.count.comments.sub');
Route::get('/call/count/comments/domain/{domain}', [App\Http\Controllers\DomainController::class, 'countComments'])->name('call.count.comments.domain');

//Get total score (up votes)
Route::get('/call/count/score/author/{author}', [App\Http\Controllers\AuthorController::class, 'countScore'])->name('call.count.score.author');
Route::get('/call/count/score/sub/{sub}', [App\Http\Controllers\SubController::class, 'countScore'])->name('call.count.score.sub');
Route::get('/call/count/score/domain/{domain}', [App\Http\Controllers\DomainController::class, 'countScore'])->name('call.count.score.domain');

//Unique subs posted to
Route::get('/call/count/unique/subs/author/{author}', [App\Http\Controllers\AuthorController::class, 'countUniqueSubs'])->name('call.count.unique.subs.author');
Route::get('/call/count/unique/subs/domain/{domain}', [App\Http\Controllers\DomainController::class, 'countUniqueSubs'])->name('call.count.unique.subs.domain');
