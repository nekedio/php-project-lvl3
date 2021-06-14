<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UrlController;

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

Route::get('/', [UrlController::class, 'create']);

Route::post('/add', [UrlController::class, 'storeUrl']);

Route::get('/urls', [UrlController::class, 'index']);

Route::get('/urls/{id}', [UrlController::class, 'show'])->where('id', '[0-9]+');

Route::post('/urls/{id}/checks', [UrlController::class, 'storeChecks'])->where('id', '[0-9]+');

Route::get('/dbconnect', function () {
    try {
        DB::connection()->getPdo();
        if (DB::connection()->getDatabaseName()) {
            echo "Yes! Successfully connected to the DB: " . DB::connection()->getDatabaseName();
        } else {
            die("Could not find the database. Please check your configuration.");
        }
    } catch (\Exception $e) {
        die("Could not open connection to database server.  Please check your configuration.");
    }
});
