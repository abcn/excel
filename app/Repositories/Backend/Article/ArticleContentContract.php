<?php
/**
 * Created by PhpStorm.
 * User: zhouhaotong
 * Date: 16/1/22
 * Time: 下午12:39
 */

namespace App\Repositories\Backend\Article;


interface ArticleContentContract
{

    public function findOrThrowException($id);

    //TODO 发布状态
    public function getArticlesPaginated($per_page, $active = 1, $order_by = 'id', $sort = 'asc');

    public function getDeletedArticlesPaginated($per_page);

    //获的资讯
    public function getAll($order_by = 'id', $sort = 'asc');

    //新建资讯
    public function create($input);

    //mark 审核状态
    public function mark($id, $status);

    public function update($id, $input);

    public function destory($id);

    public function restory($id);
}