<?php
/**
 * Created by PhpStorm.
 * User: zhouhaotong
 * Date: 16/1/26
 * Time: 下午1:58
 */

namespace App\Models\Order;


use App\Models\Order\Traits\Attribute\SubOrderAttribute;
use Illuminate\Database\Eloquent\Model;

class SubOrder extends Model
{
    use SubOrderAttribute;
    protected $table = 'sub_order';

    public function product()
    {
        return $this->hasMany('App\Models\Order\OrderProduct');
    }

    public function order()
    {
        return $this->belongsTo('App\Models\Order\Order');
    }
}