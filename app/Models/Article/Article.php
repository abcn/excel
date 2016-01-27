<?php
/**
 * Created by PhpStorm.
 * User: zhouhaotong
 * Date: 16/1/22
 * Time: 上午9:22
 */

namespace App\Models\Article;


use App\Models\Article\Traits\Attribute\ArticleAttribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use ArticleAttribute,SoftDeletes;

    //按照时间返回
    public function scopeOrderByDate($query)
    {
        return $query->orderBy('created_at','desc');
    }

    public function content()
    {
        return $this->hasOne('App\Models\Article\ArticleContent');
    }

    public function type()
    {
        return $this->belongsTo('App\Models\Article\ArticleType');
    }

    //定义范围查询 并执行查询条件
    public function scopeWithFilter($query, $relation, Array $columns_filter)
    {
        return $query->with([$relation => function ($query) use ($columns_filter){
            $query->select(array_merge(['id'], $columns_filter['columns']))->where($columns_filter['filters']);
        }]);
    }
}