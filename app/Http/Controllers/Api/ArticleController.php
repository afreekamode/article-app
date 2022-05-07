<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleLike;
use App\Models\Comment;
use App\Models\CommentLikes;
use App\Models\Tag;
use App\Models\User;
use App\Models\ViewCount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;

class ArticleController extends Controller
{
      /**
     * @OA\Get(
     *      path="/api/articles",
     *      operationId="getArticleList",
     *      tags={"Articles"},
     *      summary="Get list of articles",
     *      description="Returns list of articles",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *     )
     */
    public function index(Request $request)
    {
        //
        $articles = Article::where('posts.report', 0)
        ->orderBy('posts.created_at', 'desc')->paginate(10);

        //
        $show_comments = Article::rightJoin('post_comments', 'posts.id', '=', 'post_comments.post_id')->get();

        //
        $comments = Comment::all();

        $tags = Tag::all();

        //likes data
        $likes = ArticleLike::all();

        return response()->json([
            'articles'  => $articles,
            'comments'  => $show_comments,
            'likes'  => $likes,
            'Tags'  => $tags,
            'status'  => true
        ], 200);
 
}

//create new article
public function newArticle(Request $request)
{
    $this->validate($request, [
    'body'=> 'nullable|max:1200',
    'subject'=> 'nullable|max:200',
    'post_media'=> 'nullable|array',
    ]);

    if((empty($request->body)) && (empty($request->post_media))){
        $msg['success'] = false;
        $msg['message'] = 'Article is empty!';
        return response()->json($msg, 401);
    }
    
    $slug = Str::slug($request->subject);
    //create post
    $post = new Article();
    $post->date_added = date("Y-m-d");
    $post->body = $request->body;
    $post->media = json_encode($request->post_media);
    $post->subject = $request->subject;
    $post->post_slug = $slug;
    $post->save();
    $post->tags()->sync($request->input('tags', []));
    // dd($fileToStore);
    $msg['message'] = 'Artcile created succefully';
    $msg['article']    = $post->body;
    $msg['subject']    = $request->subject;
    $msg['status'] = 201;
    
}

  /**
     * @OA\Get(
     *      path="/api/article/{id}",
     *      operationId="getArticleById",
     *      tags={"Articles"},
     *      summary="Get Article information",
     *      description="Returns Article data",
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
     *          response=200,
     *          description="Successful operation",
     *          
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
    public function show($id)
    {
        
    $article = Article::where('posts.id', $id)
    ->where('posts.report', 0)
    ->orderBy('posts.created_at', 'desc')->get()->first();
    $article->load('tags');

    //
    $show_comments = Comment::where('post_id', $id)->get();

    //likes data
    $likes = ArticleLike::where('post_id', $id)->get();
    
    return response()->json([
        'articles'  => $article,
        'comments'  => $show_comments,
        'likes'  => $likes,
        'status'  => true
    ], 200);

    }

     /**
     * @OA\Post(
     *      path="/api/article/{id}/view",
     *      operationId="createArticleView",
     *      tags={"Articles"},
     *      summary="Store article view count",
     *      description="Store view after specific period on an article page",
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
    public function countView($article_id)
    {
        $id = $article_id;
        $existingView = ViewCount::where('post_id', $id)
        ->first();
        if (empty($existingView)) {
            // This user hasn't liked this post so we add it
            $view = new ViewCount();
            $view->post_id = $id;
            $view->save();
            ViewCount::where('post_id', $id)->increment('views_count');
            $viewData =  ViewCount::where('post_id', $id)->get('views_count');
            
            }else{
            // As existingView was not null we need to effectively increase the post view
            if (!empty($existingView)) {
                $existingView->increment('views_count');
                $viewData =  ViewCount::where('post_id', $id)->get('views_count');
            } else {
                $existingView->restore();
            }
        }
        return response()->json(['code'=>200, 'View Count' => $viewData, 'id'=>$id], 200);
    }
}
