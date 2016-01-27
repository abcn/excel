<?php
/**
 * Created by PhpStorm.
 * User: zhouhaotong
 * Date: 16/1/23
 * Time: 上午11:49
 */

namespace App\Http\Requests\Backend\Article\Type;


use App\Http\Requests\Request;

class UpdateArticleTypeRequest extends Request
{
    public function authorize()
    {
        return access()->allow('update-article-type');
    }

    public function rules()
    {
        return [
            'name'  => 'required'
        ];
    }
}