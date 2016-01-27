<?php
/**
 * Created by PhpStorm.
 * User: zhouhaotong
 * Date: 16/1/22
 * Time: 下午11:34
 */

namespace App\Http\Requests\Backend\Article;


use App\Http\Requests\Request;

class DeleteArticleRequest extends Request
{
    public function authorize()
    {
        return access()->allow('delete-article');
    }

    public function rules()
    {
        return [

        ];
    }
}