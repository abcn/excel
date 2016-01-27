<?php
/**
 * Created by PhpStorm.
 * User: zhouhaotong
 * Date: 16/1/25
 * Time: 下午3:36
 */

namespace App\Http\Controllers\Backend\Order;


use App\Http\Controllers\Controller;
use App\Models\Order\Order;
use App\Models\Order\OrderProduct;
use App\Models\Order\SubOrder;
use Carbon\Carbon;
use Guzzle\Tests\Service\Mock\Command\Sub\Sub;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Image;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\Datatables\Datatables;

class SubOrderController extends Controller
{
    protected $request;
    protected $subOrders;
    public function __construct(Request $request,SubOrder $subOrders)
    {
        $this->request      = $request;
        $this->subOrders   = $subOrders;
    }

    public function index($id)
    {
        $orderId = $id;
        return view('backend.order.sub.index',compact('orderId'));
    }

    public function detail($id)
    {
        $subOrder = $this->subOrders->with('order')->findOrFail($id);
        return view('backend.order.sub.edit',compact('subOrder','id'));
    }

    public function create()
    {

    }

    public function store()
    {


    }

    /**
     * @param $id
     * @param Request $request
     * @return mixed
     * 获取商品详情
     */
    public function productData($id)
    {
        $products = OrderProduct::select('name','count')->where('sub_order_id',$id)->get();
        //设置table
        $datatables = Datatables::of($products);

        return $datatables->make(true);
    }
    //获取分单数据
    public function data($id,Request $request)
    {
        //获取数据
        $orderId = $id;
        //$articles = $this->orders->getJoin();
        $articles = $this->subOrders->with('product')->where('order_id',$orderId);
        //设置table
        $datatables = Datatables::of($articles)
            ->editColumn('created_at', function ($articles) {
                return $articles->created_at ? with(new Carbon($articles->created_at))->format('m/d/Y') : '';
            })
            ->editColumn('updated_at', '{!! $updated_at->diffForHumans() !!}')
            //添加按钮
            ->addColumn('action', function ($articles) {
                return $articles->action_buttons;
            });
        //查询国外单号
        if ($fw_number = $datatables->request->get('fw_number')) {
            $datatables->where('fw_number', "$fw_number");
        }
        //查询手机号码
        if ($mobile = $datatables->request->get('mobile')) {
            $datatables->where('mobile', "$mobile");
        }
        //查询身份证号
        if ($id_number = $datatables->request->get('id_number')) {
            $datatables->where('id_number', "$id_number");
        }
        //查询国派送状态
        if ($send_state = $datatables->request->get('send_state')) {
            $datatables->where('send_state', "$send_state");
        }
        //查询缴税状态
        if ($tax_state = $datatables->request->get('tax_state')) {
            $datatables->where('tax_state', "$tax_state");
        }
        //查询缴税状态
        if ($clearance_state = $datatables->request->get('clearance_state')) {
            $datatables->where('clearance_state', "$clearance_state");
        }

        //查询资讯类型
        if ($name = $datatables->request->get('name')) {
            $datatables->where('name', 'like', "$name%");
        }

        return $datatables->make(true);
    }

    /**
     * 上传分单文件 身份证照片
     */
    public function upload(Request $request)
    {
        ini_set('max_execution_time', 0);
        ini_set("memory_limit", "1024M");

        //获取主单号
        $order_id = $request->get('order_id');
        $order = $this->orders->findOrFail($order_id);
        //默认返回无图片上传错误
        $data = array('success' => false,'errors' => array('没有上传文件'));
        //检查身份证是否上传
        if($request->hasFile('id_image')){
            $data = uploadID($request->file('id_image'));
            //检查文件是否已存在 插入数据库
            //替换身份证图片
            $order->id_image = $data['filename'];
            if(!$order->save()){
                //身份证上传失败
                return array('success' => false,'errors' => array('身份证上传失败'));
            }
        }
        if($request->hasFile('sub_order')){
            $data = uploadExcel($request->file('sub_order'));
            //解析文件 录入数据库
            Excel::filter('chunk')->selectSheets('Sheet1')->load($data['filename'])->chunk(300, function($results) use ($order_id)
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
            //变更订单导入状态
            $order->import_state = 1;
            $order->save();
        }

        return $data;
        //检查xlsx文件
        //检查身份证照片

    }
    //签收操作
    public function arrival(Request $request)
    {
        //获取id 判断是否为批量数据
        $id = $request->get('id');
        if(is_array($id)){
            if($this->subOrders->whereIn('id',$id)->update(array('send_state'=>'2','arrivaled_at'=>Carbon::now()))){
                return array('message'=>"批量签收成功");
            }
        }else{
            if($this->subOrders->where('id',$id)->update(array('send_state'=>'2','arrivaled_at'=>Carbon::now()))){
                return array('message'=>"签收成功");
            }
        }

        return array('message'=>"签收失败");
    }
    //派送操作
    public function send(Request $request)
    {
        //获取id 判断是否为批量数据
        $id = $request->get('id');
        if(is_array($id)){
            if($this->subOrders->whereIn('id',$id)->update(array('send_state'=>'1','sended_at'=>Carbon::now()))){
                return array('message'=>"批量派送成功");
            }
        }else{
            if($this->subOrders->where('id',$id)->update(array('send_state'=>'1','sended_at'=>Carbon::now(),'express_remark' => $request->get('remark')))){
                return array('message'=>"派送成功");
            }
        }
        return array('message'=>"派送失败");
    }
    //操作
    public function check(Request $request)
    {
        //获取id 判断是否为批量数据
        $id = $request->get('id');
        if(is_array($id)){
            if($this->subOrders->whereIn('id',$id)->update(array('clearance_state'=>'1','checked_at'=>Carbon::now()))){
                return array('message'=>"批量查验成功");
            }
        }else{
            if($this->subOrders->where('id',$id)->update(array('clearance_state'=>'1','checked_at'=>Carbon::now()))){
                return array('message'=>"查验成功");
            }
        }

        return array('message'=>"查验失败");
    }
    //操作
    public function pass(Request $request)
    {
        //获取id 判断是否为批量数据
        $id = $request->get('id');
        if(is_array($id)){
            if($this->subOrders->whereIn('id',$id)->update(array('clearance_state'=>'2','passed_at'=>Carbon::now()))){
                return array('message'=>"批量放行成功");
            }
        }else{
            //添加备注
            if($this->subOrders->where('id',$id)->update(array('clearance_state'=>'2','passed_at'=>Carbon::now(),'clear_remark' => $request->get('remark')))){
                return array('message'=>"放行成功");
            }
        }

        return array('message'=>"放行失败");
    }
}