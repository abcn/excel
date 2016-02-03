<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Models\Order\Order;
use App\Models\Order\OrderProduct;
use App\Models\Order\SubOrder;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Facades\Excel;

class ImportExcel extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $order;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        //
        $this->order = $order;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $order_id = $this->order->id;
        //
        try{
            //解析文件 录入数据库
            Excel::filter('chunk')->selectSheets('Sheet1')->load(public_path($this->order->sub_file))->chunk(300, function($results) use ($order_id)
            {
                foreach($results as $row)
                {
                    if($row['序号'] != null){
                        $subOrder = new SubOrder();
                        $subOrder->order_id = $order_id;
                        $subOrder->excel_id = (int)$row['序号'];
                        $subOrder->fw_number = $row['国外运单号'];
                        $subOrder->name = $row['姓名'];
                        $subOrder->mobile = $row['电话'];
                        $subOrder->address = $row['地址'];
                        $subOrder->zip_code = $row['邮编'];
                        $subOrder->weight = $row['重量'];
                        $subOrder->id_number = $row['身份证号'];
                        try {
                            $subOrder->save();
                        }catch (\Exception $exception){
                            return array('success' => false,'errors' => array($exception->getMessage()));
                        }
                        //存入订单产品
                        $product = new OrderProduct();
                        $product->sub_order_id = $subOrder->id;
                        $product->name = $row['品名'];
                        $product->count = $row['数量'];
                        try {
                            $product->save();
                        }catch (\Exception $exception){
                            return array('success' => false,'errors' => array($exception->getMessage()));
                        }
                    }else{
                        //存入子订单
                        $subOrder = SubOrder::where('excel_id',(int)$row['子序号'])->where('order_id',$order_id)->first();
                        //TODO 检查子订单是否存在
                        //存入订单产品
                        $product = new OrderProduct();
                        $product->sub_order_id = $subOrder['id'];
                        $product->name = $row['品名'];
                        $product->count = $row['数量'];
                        try {
                            $product->save();
                        }catch (\Exception $exception){
                            return array('success' => false,'errors' => array($exception->getMessage()));
                        }
                    }
                }
            });
        }catch (\Exception $e){
            return array('success' => false,'errors' => array($e->getMessage()));
        }
        //文件解析成功 更新子订单数量
        //获取分单数量
        $sub_total = SubOrder::where('order_id',$this->order->id)->count();
        $this->order->sub_total = $sub_total;
        $this->order->save();
    }
}
