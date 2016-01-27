<?php
/**
 * Created by PhpStorm.
 * User: zhouhaotong
 * Date: 16/1/23
 * Time: 上午2:15
 */

namespace App\Http\Requests\Backend\Article;


use App\Http\Requests\Request;

class UpdateArticleRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('edit-article');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required',
        ];
    }
}