@extends('backend.layouts.master')

@section('page-header')
    <link rel="stylesheet" href="{{bower('bootstrap-table/dist/bootstrap-table.min.css')}}">
    <h1>
        {!! app_name() !!}
        <small>{{ trans('strings.backend.dashboard.title') }}</small>
    </h1>
@endsection

@section('content')
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">主订单列表</h3>

            <div class="box-tools pull-right">
                @include('backend.order.includes.partials.header-buttons')
            </div>
        </div>
        {{--搜索--}}
        <div id="toolbar" class="btn-group">
            <div class="form-inline" role="form">
                <div class="form-group">
                    <input name="fw_number" class="form-control" type="text" placeholder="国外单号">
                </div>
                <div class="form-group">
                    <input name="name" class="form-control" type="text" placeholder="姓名">
                </div>
                <div class="form-group">
                    <input name="ID_number" class="form-control" type="text" placeholder="身份证号">
                </div>
                <div class="form-group">
                    <select class="form-control" name="transport_type">
                        <option value="">航运类型</option>
                        <option value="1">海运类型</option>
                        <option value="2">空运类型</option>
                    </select>
                </div>
                <div class="form-group">
                    <select name="clearance_state" class="form-control">
                        <option value="">放行状态</option>
                        <option value="0">未放行</option>
                        <option value="1">查验</option>
                        <option value="2">放行</option>
                    </select>
                </div>
                <div class="form-group">
                    <select name="tax_state" class="form-control">
                        <option value="">缴税状态</option>
                        <option value="0">免税</option>
                        <option value="1">需缴税</option>
                        <option value="2">已缴税</option>
                    </select>
                </div>
                <div class="form-group">
                    <select name="send_state" class="form-control">
                        <option value="">派送状态</option>
                        <option value="0">未派送</option>
                        <option value="1">已派送</option>
                        <option value="2">已签收</option>
                    </select>
                </div>
                <button id="ok" type="submit" class="btn btn-default">搜索</button>
                <button id="pass" type="button" class="btn btn-primary">批量海关放行</button>
                <button type="button" class="btn btn-primary" id="check">批量海关查验</button>
                <button type="button" class="btn btn-primary" id="send">批量派送</button>
                <button type="button" class="btn btn-primary" id="arrival">批量签收</button>
            </div>
        </div>
        {{--搜索--}}
        <div class="box-body">
            <table id="table"
                   data-toggle="table"
                   data-cookie="true"
                   data-cookie-id-table="subOrder"
                   data-sort-order="desc"
                   data-show-refresh="true"
                   data-show-toggle="true"
                   data-show-columns="true"
                   data-click-to-select="true"
                   data-toolbar="#toolbar"
                   data-side-pagination="server"
                   data-pagination="true"
                   data-page-size="500"
                   data-page-list="[500, 1000]"
                   data-pagination-first-text="第一页"
                   data-pagination-pre-text="上一页"
                   data-pagination-next-text="下一页"
                   data-pagination-last-text="最后一页"
                   data-url="{{route('order.subData',$orderId)}}",
                   data-query-params="getQueryParams">
                <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-field="excel_id">序号</th>
                    <th data-field="fw_number">国外单号</th>
                    <th data-field="name">姓名</th>
                    <th data-field="mobile">电话</th>
                    <th data-field="id_number">省份证/护照号</th>
                    <th data-field="address">地址</th>
                    <th data-field="zip_code">邮编</th>
                    <th data-field="weight">分单计费重量</th>
                    <th data-filed="clearance_state" data-formatter="clearanceState">放行状态</th>
                    <th data-field="tax_amount">缴税金额</th>
                    <th data-field="tax_state" data-formatter="taxState">缴税状态</th>
                    <th data-field="send_state" data-formatter="sendState">派送状态</th>
                    <th data-field="action" data-formatter="actionFormatter">操作</th>
                </tr>
                </thead>
            </table>

        </div>
    </div>
    @section('after-scripts-end')
    <!-- Latest compiled and minified JavaScript -->
    <script src="{{bower('bootstrap-table/dist/bootstrap-table.min.js')}}"></script>

    <!-- Latest compiled and minified Locales -->
    <script src="{{bower('bootstrap-table/dist/locale/bootstrap-table-zh-CN.min.js')}}"></script>
    <script>
        var $table = $('#table'),
            $button = $('#button');
        $table.bootstrapTable({

        });
        //获取所有选中选项
        function getIdSelections() {
            return $.map($table.bootstrapTable('getSelections'), function (row) {
                return row.id
            });
        }
        //返回搜索参数值
        function getQueryParams(params) {
            $('#toolbar').find('input[name]').each(function () {
                params[$(this).attr('name')] = $(this).val();
            });
            $('#toolbar').find('select[name]').each(function () {
                params[$(this).attr('name')] = $(this).val();
            });
            return params; // body data
        }
        //批量操作 放行
        $("#pass").click(function (){
            var pass = getIdSelections();
            if(pass == ''){
                sweetAlert("批量放行失败", "未选中任何分单!", "error");
            }else{
                post_batch('{{route('admin.subOrder.pass')}}',pass);
            }
        });
        //批量操作 派送
        $("#send").click(function (){
            var send = getIdSelections();
            if(send == ''){
                sweetAlert("批量派送失败", "未选中任何分单!", "error");
            }else{
                post_batch('{{route('admin.subOrder.send')}}',send);
            }

        });
        //批量操作 查验
        $("#check").click(function (){
            var check = getIdSelections();
            if(check == ''){
                sweetAlert("批量查验失败", "未选中任何分单!", "error");
            }else{
                post_batch('{{route('admin.subOrder.check')}}',check);
            }
        });
        //获取所有选中选项
        function getIdSelections() {
            return $.map($table.bootstrapTable('getSelections'), function (row) {
                return row.id
            });
        }
        //批量操作ajax请求函数
        function post_batch(url,data){
            $.ajax({
                type:'POST',
                url: url,
                data: {id:data},
                success: function(result){
                    swal({
                        title: '提示',
                        text:  result.message,
                        type: "success",
                        timer: 3000,
                        showConfirmButton: false
                    });
                    window.setTimeout(function(){ } ,3000);
                    location.reload();
                    //location.reload();
                }
            });
        }
        function clearanceState(value) {
            var clearance_state = '';
            switch (value){
                case '0':
                    clearance_state = '未放行';
                    break;
                case '1':
                    clearance_state = '查验';
                    break;
                case '2':
                    clearance_state = '已放行';
                    break;
                default:
                    clearance_state = '未放行';
            }
            return clearance_state;
        }
        function sendState(value) {
            var send_state = '';
            switch (value){
                case '0':
                    send_state = '未派送';
                    break;
                case '1':
                    send_state = '已派送';
                    break;
                case '2':
                    send_state = '已签收';
                    break;
                default:
                    send_state = '未派送';
            }
            return send_state;
        }
        function taxState(value) {
            var tax_state = '';
            switch (value){
                case '0':
                    tax_state = '免税';
                    break;
                case '1':
                    tax_state = '需缴税';
                    break;
                default:
                    tax_state = '免税';
            }
            return tax_state;
        }
        function actionFormatter(value, row, index) {
            var delete_button = '<a href="order/'+row['id']+'/destroy" data-method="delete" class="btn btn-xs btn-danger"><i class="fa fa-trash" data-toggle="tooltip" data-placement="top" title="删除">删除</i></a>';
            var detail_button = '<a href="/admin/subOrder/'+row['id']+'/detail" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="查看详情">查看详情</i></a> ';
            return detail_button + delete_button;
        }
    </script>
@endsection
@endsection