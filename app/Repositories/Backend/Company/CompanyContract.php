<?php
/**
 * Created by PhpStorm.
 * User: zhouhaotong
 * Date: 16/1/24
 * Time: 上午12:14
 */

namespace App\Repositories\Backend\Company;


interface CompanyContract
{
    public function findOrThrowException($id);
    //获取带有详情的资讯
    public function findWithContent($id);

    //TODO 发布状态
    public function getPaginated($per_page, $active = 1, $order_by = 'id', $sort = 'asc');

    public function getDeletedPaginated($per_page);

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