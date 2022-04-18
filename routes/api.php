<?php


use App\Http\Controllers\AlbumController;
use App\Http\Controllers\ArtistController;
use App\Http\Controllers\GoogleAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::group(['middleware' => ['web']], function () {
    Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect']);
    Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback']);
});


//Artist
Route::get('/artist', [ArtistController::class, 'getArtist'])->middleware(['auth:sanctum', 'abilities:artist']);
Route::put('/artist', [ArtistController::class, 'updateArtist'])->middleware(['auth:sanctum', 'abilities:artist']);
Route::delete('/artist', [ArtistController::class, 'deleteArtist'])->middleware(['auth:sanctum', 'abilities:artist']);
Route::post('/artist', [ArtistController::class, 'createArtist'])->middleware(['auth:sanctum', 'abilities:artist']);

//Album
Route::get('/album', [AlbumController::class, 'getAlbum'])->middleware(['auth:sanctum', 'abilities:album']);
Route::put('/album', [AlbumController::class, 'updateAlbum'])->middleware(['auth:sanctum', 'abilities:album']);
Route::delete('/album', [AlbumController::class, 'deleteAlbum'])->middleware(['auth:sanctum', 'abilities:album']);
Route::post('/album', [AlbumController::class, 'createAlbum'])->middleware(['auth:sanctum', 'abilities:album']);
