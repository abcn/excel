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
        <div id="toolbar" class="btn-group">
            <button id="button" class="btn btn-default">getAllSelections</button>
            <button type="button" id="add" class="btn btn-default">
                <i class="glyphicon glyphicon-plus"></i>
            </button>
            <button type="button" class="btn btn-default">
                <i class="glyphicon glyphicon-heart"></i>
            </button>
            <button type="button" class="btn btn-default">
                <i class="glyphicon glyphicon-trash"></i>
            </button>
        </div>
        <div class="box-body">
            <table id="table"
                   data-toggle="table"
                   data-search="true"
                   data-sort-name="order_number"
                   data-sort-order="desc"
                   data-show-refresh="true"
                   data-show-toggle="true"
                   data-show-columns="true"
                   data-click-to-select="true"
                   data-toolbar="#toolbar"
                   data-side-pagination="server"
                   data-pagination="true"
                   data-page-list="[5, 10, 20, 50, 100, 200]">
                <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-field="excel_id">序号</th>
                    <th data-field="fw_number">国外单号</th>
                    <th data-field="name">姓名</th>
                    <th data-field="mobile">电话</th>
                    <th data-field="id_number">省份证/护照号</th>
                    <th data-field="address">地址</th>
                    <th data-field="">邮编</th>
                    <th>分单计费重量</th>
                    <th>放行状态</th>
                    <th>缴税金额</th>
                    <th>缴税状态</th>
                    <th>派送状态</th>
                    <th>操作</th>
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
            url: '{{route('order.subData',$orderId)}}'
        });
        //获取所有选中选项
        function getIdSelections() {
            return $.map($table.bootstrapTable('getSelections'), function (row) {
                return row.id
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
        function portState(value) {
            var port_state = '';
            switch (value){
                case '1':
                    port_state = '未到港';
                    break;
                case '2':
                    port_state = '清关中';
                    break;
                case '3':
                    port_state = '已到港';
                    break;
                default:
                    port_state = '未到港';
            }
            return port_state;
        }
        function declearState(value) {
            return value ? '已报关' : '未报关';
        }
        function actionFormatter(value, row, index) {
            if(row.import_state == 1){
                return '<a href="order/'+row['id']+'/sub" class="btn btn-xs btn-success"><i class="fa fa-play" data-toggle="tooltip" data-placement="top" title="查看分单">查看分单</i></a></i>';
            }
            return '<a href="order/'+row['id']+'/sub" class="btn btn-xs btn-success"><i class="fa fa-play" data-toggle="tooltip" data-placement="top" title="导入">导入</i></a></i>';

        }
    </script>
@endsection
@endsection