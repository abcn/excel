<?php
/**
 * Created by PhpStorm.
 * User: zhouhaotong
 * Date: 16/1/22
 * Time: 下午11:44
 */

namespace App\Http\Requests\Backend\Article;


use App\Http\Requests\Request;

class EditArticleRequest extends Request
{
    public function authorize()
    {
        return access()->allow('edit-article');
    }

    public function rules()
    {
        return [

        ];
    }
}