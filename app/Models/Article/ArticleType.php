<?php
/**
 * Created by PhpStorm.
 * User: zhouhaotong
 * Date: 16/1/22
 * Time: 下午5:37
 */

namespace App\Models\Article;


use App\Models\Article\Traits\Attribute\ArticleTypeAttribute;
use Illuminate\Database\Eloquent\Model;

class ArticleType extends Model
{
    use ArticleTypeAttribute;

    public function article()
    {
        return $this->hasMany('App\Models\Article\Article','type_id');
    }
}