<?php
/**
 * Created by PhpStorm.
 * User: zhouhaotong
 * Date: 16/1/25
 * Time: 下午5:01
 */

namespace App\Models\Order;


use App\Models\Order\Traits\Attribute\OrderAttribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use OrderAttribute,SoftDeletes;
    protected $table = 'order';

    protected $dates = ['deleted_at'];

    public function product()
    {
        return $this->hasMany('App\Models\Order\OrderProduct');
    }

    public function express()
    {
        return $this->belongsTo('App\Models\Order\OrderExpress');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\Access\User\User')->select('id','company','company_area');
    }
}