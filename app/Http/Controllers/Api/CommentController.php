<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreArticleRequest;
use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class CommentController extends Controller
{
     
    /**
     * @OA\Post(
     *      path="/api/article/{id}/comment",
     *      operationId="Add comment",
     *      tags={"Articles"},
     *      summary="Store comment on an article",
     *      description="Add comment to an article by article id",
     * @OA\Parameter(
     *          name="id",
     *          description="Article id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *  @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/StoreArticleRequest")
     *      ),
     * 
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
    public function comment(Request $request, $article_id)
    {
        
        $id = $article_id;
        //create post
        $post = new Comment();
        $post->post_body = $request->comment;
        $post->post_id = $id;
        $post->save();
        
        Article::where('id', $id)->increment('commts_count');
        $comtData =  Article::where('id', $id)->get('commts_count');
        $show_comments = Article::rightJoin('post_comments', 'posts.id', '=', 'post_comments.post_id')
        ->where('post_comments.post_id', $id)
        ->get();
        
        return response()->json(['data'=> $show_comments, 'commentCount'=> $comtData, 'id'=>$id], 200);
    }

}
