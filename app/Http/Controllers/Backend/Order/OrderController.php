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
use App\Models\Order\OrderExpress;
use App\Models\Order\OrderProduct;
use App\Models\Order\SubOrder;
use Carbon\Carbon;
use Guzzle\Tests\Service\Mock\Command\Sub\Sub;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Image;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\Datatables\Datatables;

class OrderController extends Controller
{
    protected $request;
    protected $orders;
    public function __construct(Request $request,Order $orders)
    {
        $this->request      = $request;
        $this->orders   = $orders;
    }

    public function index()
    {
        return view('backend.order.index');
    }

    public function show()
    {

    }

    public function create()
    {
        //获取快递列表
        $expresses = OrderExpress::all();

        return view('backend.order.create',compact('expresses'));
    }

    public function store(Request $request)
    {
        $order = new Order();
        $order->user_id = Auth::user()->id;
        $order->transport_type = $request->get('transport_type');
        $order->transport_number = $request->get('transport_number');
        $order->order_number = $request->get('order_number');
        $order->weight = $request->get('weight');
        $order->express_id = $request->get('express_id');
        if($order->save()) {
            return redirect(route('admin.order.index'));
        }

    }
    public function edit($id)
    {
        //获取订单信息
        $order = $this->orders->with('user')->with('express')->findOrFail($id);
        //获取快递列表
        $expresses = OrderExpress::all();

        return view('backend.order.edit',compact('order','expresses'));
    }
    public function update($id,Request $request)
    {
        $order = $this->orders->findOrFail($id);
        $order->transport_type = $request->get('transport_type');
        $order->transport_number = $request->get('transport_number');
        $order->save();
    }
    //获取订单数据
    public function data()
    {
        //获取数据
        $orders = $this->orders->leftJoin('order_express','express_id', '=', 'order_express.id')
            ->leftJoin('users','user_id', '=', 'users.id')
            ->select('order.*','order_express.name as expressName','users.company','users.company_area');
        //设置table
        $datatables = Datatables::of($orders)
            ->editColumn('created_at', function ($orders) {
                return $orders->created_at ? with(new Carbon($orders->created_at))->format('m/d/Y') : '';
            })
            ->editColumn('updated_at', '{!! $updated_at->diffForHumans() !!}')
            //添加按钮
            ->addColumn('action', function ($orders) {
                return $orders->action_buttons;
            });
        //查询公司名称
        if ($company = $datatables->request->get('company')) {
            $datatables->where('company', 'like', "$company%");
        }

        //查询主单号
        if ($order_number = $datatables->request->get('order_number')) {
            $datatables->where('order_number', "$order_number");
        }
        //查询航运类型
        if ($transport_type = $datatables->request->get('transport_type')) {
            $datatables->where('transport_type', "$transport_type");
        }
        //查询航运单号
        if ($transport_number = $datatables->request->get('transport_number')) {
            $datatables->where('transport_number', "$transport_number");
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
            //保存excel文件地址到数据库
            $order->sub_file = $data['filename'];
            if(!$order->save()){
                //身份证上传失败
                return array('success' => false,'errors' => array('分单文件上传失败'));
            }
            try{
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
            }catch (\Exception $e){
                return array('success' => false,'errors' => array($e->getMessage()));
            }
            //获取分单数量
            $sub_total = SubOrder::where('order_id',$order_id)->count();
            //变更订单导入状态
            $order->import_state = 1;
            $order->sub_total = $sub_total;
            $order->save();
        }

        return $data;
        //检查xlsx文件
        //检查身份证照片

    }
    /**
     * 导出分单
     */
    public function export($id)
    {
        //获取主订单信息
        $order = $this->orders->findOrFail($id);
        return response()->download($order->sub_file);
    }
    //导出申报单
    public function report($id)
    {
        Excel::create('Filename', function($excel) {

            $excel->sheet('Sheetname', function($sheet) {
                //合并第一行
                $sheet->mergeCells('A1:K1');
                //添加第一行数据
                $firstRow = array('经营人:中远', '进口口岸:天津港', '进口口岸:天津港', '进口口岸:天津港');
                $sheet->appendRow(1,array(implode(" ",$firstRow)));
                // Sheet manipulation

            });

        })->export('xls');
    }
    //导出身份证
    public function exportID($id)
    {
        //获取订单信息
        //TODO 检查订单是否存在
        $order = Order::findOrFail($id);
        return response()->download($order->id_image);
    }

    //申报海关
    public function declear($id)
    {
        //获取订单信息
        //TODO 检查订单是否存在
        $order = Order::findOrFail($id);
        $order->declear_state = 1;
        $order->decleared_at = Carbon::now();
        $order->save();
        return redirect()->back()->withFlashSuccess('申报成功');
    }
    //到港
    public function port($id)
    {
        //获取订单信息
        //TODO 检查订单是否存在
        $order = Order::findOrFail($id);
        $order->port_state = 2;
        $order->ported_at = Carbon::now();
        $order->save();
        return redirect()->back()->withFlashSuccess('清关中');
    }

    //清关
    public function clear($id)
    {
        //获取订单信息
        //TODO 检查订单是否存在
        $order = Order::findOrFail($id);
        $order->port_state = 3;
        $order->ported_at = Carbon::now();
        $order->save();
        return redirect()->back()->withFlashSuccess('已到港变更成功');
    }

    //删除
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete($id);
        return redirect()->back()->withFlashSuccess('订单删除成功');
    }

}