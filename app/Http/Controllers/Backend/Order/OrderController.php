<?php
/**
 * Created by PhpStorm.
 * User: zhouhaotong
 * Date: 16/1/25
 * Time: 下午3:36
 */

namespace App\Http\Controllers\Backend\Order;


use App\Http\Controllers\Controller;
use App\Jobs\ImportExcel;
use App\Jobs\ZipArchive;
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
        return redirect()->back()->withFlashSuccess('创建失败');
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
        $order->order_number = $request->get('order_number');
        $order->weight = $request->get('weight');
        $order->declear_state = $request->get('declear_state');
        $order->port_state = $request->get('port_state');
        $order->inspection = $request->get('inspection');
        $order->clearance = $request->get('clearance');
        $order->express_id = $request->get('express_id');
        if($order->save()) {
            return redirect(route('admin.order.index'));
        }
        return redirect()->back()->withFlashSuccess('编辑失败');
    }
    //获取订单数据
    public function data()
    {
        $limit = $this->request->has('limit') ? $this->request->get('limit') : 10;
        $offset = $this->request->has('offset') ? $this->request->get('offset') : 0;
        $sort = $this->request->has('sort') ? $this->request->get('sort') : 'order.created_at';
        $order = $this->request->has('order') ? $this->request->get('order') : 'desc';
        //搜索参数获取
        $query = $this->orders
        ->leftJoin('order_express','express_id', '=', 'order_express.id')
        ->leftJoin('users','user_id', '=', 'users.id')
        ->select('order.*','order_express.name as expressName','users.company','users.company_area')
        ->orderBy($sort,$order);

        if($this->request->has('transport_type')){
            $query->where('transport_type',$this->request->get('transport_type'));
        }
        if($this->request->has('company')){
            $company = $this->request->get('company');
            $query->where('users.company','like',"$company%");
        }
        if($this->request->has('company_area')){
            $company_area = $this->request->get('company_area');
            $query->where('users.company_area','like',"$company_area%");
        }
        if($this->request->has('transport_number')){
            $transport_number = $this->request->get('transport_number');
            $query->where('order.transport_number',"$transport_number");
        }
        if($this->request->has('order_number')){
            $order_number = $this->request->get('order_number');
            $query->where('order.order_number',"$order_number");
        }
        $total = $query->count();
        //
        $orders  = $query
            ->skip($offset)
            ->take($limit)->get();
        return array('total' => $total, 'rows' => $orders);
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
        if($request->hasFile('sub_order')){
            $data = uploadExcel($request->file('sub_order'));
            //保存excel文件地址到数据库
            $order->sub_file = $data['filename'];
            if(!$order->save()){
                //身份证上传失败
                return array('success' => false,'errors' => array('分单文件上传失败'));
            }
            //将解析文件 加入至队列
            $job_excel = new ImportExcel($order);
            $this->dispatch($job_excel);
            //变更订单导入状态
            $order->import_state = 1;
            $order->save();
        }
        //默认返回无图片上传错误
        $data = array('success' => false,'errors' => array('没有上传文件'));
        //检查身份证是否上传
        if($request->hasFile('id_image')){
            $data = uploadID($request->file('id_image'));
            //检查文件是否已存在 插入数据库
            //替换身份证图片
            $order->id_image = $data['filename'];
            $order->id_image_dir = $data['extractDir'];
            if(!$order->save()){
                //身份证上传失败
                return array('success' => false,'errors' => array('身份证上传失败'));
            }
            //将解压文件任务添加到队列
            $job = (new ZipArchive($order,$data['extractDir']))->delay(5);
            $this->dispatch($job);
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