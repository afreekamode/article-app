<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleLike;
use Illuminate\Http\Request;

class LikeController extends Controller
{

    /**
     * @OA\Post(
     *      path="/api/article/{id}/like",
     *      operationId="LikeArticle",
     *      tags={"Articles"},
     *      summary="Store article likes",
     *      description="Method to like and umlike an article by article id",
     *      @OA\Parameter(
     *          name="id",
     *          description="Article id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */
    public function actOnLike($article_id)
    {
        $id = $article_id;
        $existingLike = ArticleLike::where('post_id', $id)
        ->first();
        if (empty($existingLike)) {
            // This user hasn't liked this post so we add it
            $post = new ArticleLike();
            $post->post_id = $id;
            $post->save();
            Article::where('id', $id)->increment('likes_count');
            $likeData =  Article::where('id', $id)->get('likes_count');
            
            }else{
            // As existingLike was not null we need to effectively un-like this post
            if (!empty($existingLike)) {
                $existingLike->delete();
                Article::where('id', $id)->decrement('likes_count');
                $likeData =  Article::where('id', $id)->get('likes_count');
            } else {
                $existingLike->restore();
            }
        }
        return response()->json(['code'=>200, 'New Like' => $likeData, 'id'=>$id], 200); 
    }
   
}
