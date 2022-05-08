<?php
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

  //Article Endpoits *******************************************************

Route::get('articles', 'Api\ArticleController@index');

Route::get('article/{id}', 'Api\ArticleController@show');

Route::get('article/getlikes', 'Api\LikeController@getLikes');

Route::post('article/{id}/view', 'Api\ArticleController@countView');

Route::post('create/article', 'Api\ArticleController@newArticle');

//Likes Endpoits *******************************************************

Route::post('article/{id}/like', 'Api\LikeController@actOnLike');//likes route

 //Comment Endpoits *******************************************************

 Route::post('/article/{id}/comment', 'Api\CommentController@comment');

