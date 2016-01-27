<?php
/**
 * Created by PhpStorm.
 * User: zhouhaotong
 * Date: 16/1/23
 * Time: 上午11:32
 */

namespace App\Http\Requests\Backend\Article\Type;


use App\Http\Requests\Request;

class CreateArticleTypeRequest extends Request
{
    public function authorize()
    {
        return access()->allow('create-article-type');
    }
    public function rules()
    {
        return [
            //
        ];
    }
}