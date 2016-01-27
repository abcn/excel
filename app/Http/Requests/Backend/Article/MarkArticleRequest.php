<?php
/**
 * Created by PhpStorm.
 * User: zhouhaotong
 * Date: 16/1/22
 * Time: 下午10:07
 */

namespace App\Http\Requests\Backend\Article;



use App\Http\Requests\Request;

class MarkArticleRequest extends Request
{
    public function authorize()
    {
        //Get the 'mark' id
        switch ((int) request()->segment(6)) {
            case 0:
                return access()->allow('deactivate-article');
                break;

            case 1:
                return access()->allow('reactivate-article');
                break;
        }

        return false;
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
        ];
    }
}