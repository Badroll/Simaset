<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['cors']], function(){
    Route::post("login",		"Api\ApiController@login");
	Route::post("master",		"Api\ApiController@master");
    Route::post("barang-get",		"Api\ApiController@barangGet");
    Route::post("barang-insert",		"Api\ApiController@barangInsert");
    Route::post("barang-update",		"Api\ApiController@barangUpdate");
	Route::post("barang-delete",		"Api\ApiController@barangDelete");
    Route::post("kritik-insert",		"Api\ApiController@kritikInsert");

    Route::get('files/{filename}', function ($filename){
	    $path = public_path('storage/' . $filename);
	    if (!File::exists($path)) {
	        abort(404);
	    }
	    $file = File::get($path);
	    $type = File::mimeType($path);
	    $response = Response::make($file, 200);
	    $response->header("Content-Type", $type);
	    return $response;
	});
	Route::get("qr",		"Api\ApiController@generateQrCode");
});
