<?php
/**
 * Created by PhpStorm.
 * User: zhouhaotong
 * Date: 16/1/23
 * Time: 下午2:46
 */

namespace App\Models\Article;


use Illuminate\Database\Eloquent\Model;

class ArticleContent extends Model
{
    public function article()
    {
        return $this->belongsTo('App\Models\Article\Article');
    }
}