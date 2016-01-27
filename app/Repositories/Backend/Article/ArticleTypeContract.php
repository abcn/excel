<?php
/**
 * Created by PhpStorm.
 * User: zhouhaotong
 * Date: 16/1/22
 * Time: 下午12:39
 */

namespace App\Repositories\Backend\Article;


interface ArticleTypeContract
{
    public function findOrThrowException($id);

    //新建资讯
    public function create($input);

    //获取资讯类型
    public function getAll();

    //获取单个资讯通过id
    public function getById($id);

    public function update($id, $input);
}