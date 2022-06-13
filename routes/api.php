<?php


use App\Http\Controllers\AlbumController;
use App\Http\Controllers\ArtistController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\SongController;
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
const ARTIST_ROUTE = '/artists';
const ALBUM_ROUTE = '/album';
const MUSIC_ROUTE = '/music';
const RATING_ROUTE = '/rating';

Route::group(['middleware' => ['web']], function () {
    Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect']);
    Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback']);
});


//Artist
Route::get(ARTIST_ROUTE, [ArtistController::class, 'getArtist'])->middleware(['auth:sanctum', 'abilities:artist']);
Route::put(ARTIST_ROUTE, [ArtistController::class, 'updateArtist'])->middleware(['auth:sanctum', 'abilities:artist']);
Route::delete(ARTIST_ROUTE, [ArtistController::class, 'deleteArtist'])->middleware(['auth:sanctum', 'abilities:artist']);
Route::post(ARTIST_ROUTE, [ArtistController::class, 'createArtist'])->middleware(['auth:sanctum', 'abilities:artist']);

//Album
Route::get(ALBUM_ROUTE, [AlbumController::class, 'getAlbum'])->middleware(['auth:sanctum', 'abilities:album']);
Route::put(ALBUM_ROUTE, [AlbumController::class, 'updateAlbum'])->middleware(['auth:sanctum', 'abilities:album']);
Route::delete(ALBUM_ROUTE, [AlbumController::class, 'deleteAlbum'])->middleware(['auth:sanctum', 'abilities:album']);
Route::post(ALBUM_ROUTE, [AlbumController::class, 'createAlbum'])->middleware(['auth:sanctum', 'abilities:album']);

//Music
Route::get(MUSIC_ROUTE, [SongController::class, 'getSong'])->middleware(['auth:sanctum', 'abilities:music']);
Route::get(MUSIC_ROUTE . "/search", [SongController::class, 'searchSong'])->middleware(['auth:sanctum', 'abilities:music']);
//Route::put(ALBUM_ROUTE, [SongController::class, 'updateAlbum'])->middleware(['auth:sanctum', 'abilities:music']);
Route::delete(MUSIC_ROUTE, [SongController::class, 'deleteSong'])->middleware(['auth:sanctum', 'abilities:music']);
Route::post(MUSIC_ROUTE, [SongController::class, 'uploadSong'])->middleware(['auth:sanctum', 'abilities:music']);

Route::post(RATING_ROUTE, [RatingController::class, 'like'])->middleware(['auth:sanctum', 'abilities:music']);
Route::get(RATING_ROUTE, [RatingController::class, 'getRating'])->middleware(['auth:sanctum', 'abilities:music']);
Route::get(RATING_ROUTE . '/test', [RatingController::class, 'like']);

