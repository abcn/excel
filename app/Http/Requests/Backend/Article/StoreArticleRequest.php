<?php
/**
 * Created by PhpStorm.
 * User: zhouhaotong
 * Date: 16/1/22
 * Time: 上午11:28
 */

namespace App\Http\Requests\Backend\Article;


use App\Http\Requests\Request;

class StoreArticleRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('create-users');
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
            'title'     =>  'required',
            'abstract'  =>  'required|min:6'
        ];
    }

    public function messages()
    {
        return [
            'title.required' => '标题不能为空',
            'abstract.required' => '摘要不能为空',
            'abstract.min'      => '摘要内容大于6个字',
        ];
    }
}