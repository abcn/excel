<?php
/**
 * Created by PhpStorm.
 * User: zhouhaotong
 * Date: 16/1/26
 * Time: 上午9:33
 */

namespace App\Models\Order;



use Illuminate\Database\Eloquent\Model;

class OrderExpress extends Model
{
    protected $table = 'order_express';

    public function order()
    {
        return $this->hasMany('App\Models\Order\Order');
    }
}