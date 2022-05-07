<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
  /**
 * @OA\Schema()
 */
class StoreArticleRequest extends FormRequest
{
 
    /**
     * @OA\Property(format="string", default="Test article comment", description="comment", property="comment"),
     */
    public function rules()
    {
        return [
            'comment' => ['required', 'string', 'max:1500'],
        ];
    }
}
