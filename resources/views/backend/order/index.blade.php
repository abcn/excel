@extends('backend.layouts.master')

@section('page-header')
    <!-- Latest compiled and minified CSS -->
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
            <div class="form-inline" role="form">
                <div class="form-group">
                    <input name="company" class="form-control" type="text" placeholder="公司名称">
                </div>
                <div class="form-group">
                    <select class="form-control" name="transport_type">
                        <option value="">航运类型</option>
                        <option value="1">海运类型</option>
                        <option value="2">空运类型</option>
                    </select>
                </div>
                <button id="ok" type="submit" class="btn btn-default">搜索</button>
            </div>
        </div>
        <div class="box-body">
            <table id="table"
                   data-url="order/data"
                   data-toggle="table"
                   data-cookie="true"
                   data-cookie-id-table="order"
                   data-toolbar="#toolbar"
                   data-side-pagination="server"
                   data-pagination="true"
                   data-page-list="[5, 10, 20, 50, 100, 200]"
                   data-sort-order="desc"
                   data-show-refresh="true"
                   data-show-toggle="true"
                   data-show-columns="true"
                   data-click-to-select="true"
                   data-query-params="getQueryParams"
                   data-search-on-enter-key="true">
                <thead>
                <tr>
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-field="id">ID</th>
                    <th data-field="company">公司名称</th>
                    <th data-field="company_area">公司地区</th>
                    <th data-field="transport_type">航运类型</th>
                    <th data-field="transport_number">航班号</th>
                    <th data-field="order_number">主单号</th>
                    <th data-field="weight">净重</th>
                    <th data-field="expressName">境内快递商</th>
                    <th data-field="tax">税金金额</th>
                    <th data-field="sub_total">分单总数</th>
                    <th data-field="clearance">海关放行数</th>
                    <th data-field="inspection">海关查验数</th>
                    <th data-field="port_state" data-formatter="portState">到港状态</th>
                    <th data-field="declear_state" data-formatter="declearState">报关状态</th>
                    <th data-field="clearance_state" data-formatter="clearanceState">报关状态</th>
                    <th data-field="ported_at">到岗时间</th>
                    <th data-field="decleared_at">报关时间</th>
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
    <script src="{{bower('bootstrap-table/dist/extensions/cookie/bootstrap-table-cookie.js')}}"></script>
        <script>
            var $table = $('#table'),
                    $button = $('#button'),
                    $ok = $('#ok');
            $table.bootstrapTable({

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
            function getQueryParams(params) {
                $('#toolbar').find('input[name]').each(function () {
                    params[$(this).attr('name')] = $(this).val();
                });
                $('#toolbar').find('select[name]').each(function () {
                    params[$(this).attr('name')] = $(this).val();
                });
                return params; // body data
            }
            $(function () {
                $ok.click(function () {
                    $table.bootstrapTable('refresh');
                });
            });
        </script>
    @endsection
@endsection