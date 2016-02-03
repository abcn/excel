@extends('backend.layouts.master')

@section('page-header')
    <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="{{bower('bootstrap-table/dist/bootstrap-table.min.css')}}">
<link rel="stylesheet" href="{{bower('loaders.css/loaders.css')}}">
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
                    <input name="company" class="form-control" type="text" placeholder="公司名称">
                </div>
                <div class="form-group">
                    <input name="company_area" class="form-control" type="text" placeholder="公司地区">
                </div>
                <div class="form-group">
                    <select class="form-control" name="transport_type">
                        <option value="">航运类型</option>
                        <option value="1">海运类型</option>
                        <option value="2">空运类型</option>
                    </select>
                </div>
                <div class="form-group">
                    <input name="transport_number" class="form-control" type="text" placeholder="航班号">
                </div>
                <div class="form-group">
                    <input name="order_number" class="form-control" type="text" placeholder="主单号">
                </div>
                <button id="ok" type="submit" class="btn btn-default">搜索</button>
            </div>
        </div>
        {{--搜索--}}
        <div class="box-body">
            <table id="table"
                   data-url="order/data"
                   data-toggle="table"
                   data-cookie="true"
                   data-cookie-id-table="order"
                   data-toolbar="#toolbar"
                   data-side-pagination="server"
                   data-pagination="true"
                   data-page-size="500"
                   data-page-list="[500, 1000]"
                   data-pagination-first-text="第一页"
                   data-pagination-pre-text="上一页"
                   data-pagination-next-text="下一页"
                   data-pagination-last-text="最后一页"
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
        <!-- Modal Import-->
        <div class="modal fade" id="import" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">导入分单</h4>
                    </div>
                    <div class="modal-body">
                        <div id="validation-errors"></div>
                        {!! Form::open( [ 'url' => ['admin/order/upload'], 'method' => 'POST', 'id' => 'upload_form', 'files' => true ] ) !!}
                        <div class="form-group">
                            <label for="sub_order">分&nbsp;单&nbsp;文&nbsp;件</label>
                            <input type="file" class="form-control" name="sub_order" id="sub_order" placeholder="分单文件" multiple>
                        </div>
                        <div class="form-group">
                            <label for="id_image">身份证照片</label>
                            <input type="file" class="form-control" name="id_image" id="id_image" placeholder="分单文件" multiple>
                        </div>
                        <input type="hidden" name="order_id" id="order_id" />
                        {!! Form::close() !!}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                        <button type="button" id="upload_button" class="btn btn-primary">保存</button>
                    </div>
                </div>
            </div>
        </div>
        {{--end modal--}}
    </div>
    @section('after-scripts-end')
            <!-- Latest compiled and minified JavaScript -->
    <script src="{{bower('bootstrap-table/dist/bootstrap-table.min.js')}}"></script>

    <!-- Latest compiled and minified Locales -->
    <script src="{{bower('bootstrap-table/dist/locale/bootstrap-table-zh-CN.min.js')}}"></script>
    <script src="{{bower('bootstrap-table/dist/extensions/cookie/bootstrap-table-cookie.js')}}"></script>
    <script src="{{asset('js/backend/jquery.form.js')}}"></script>
    <script src="{{bower('loaders.css/loaders.css.js')}}"></script>
    <script>
            var $table = $('#table'),
                    $button = $('#button'),
                    $ok = $('#ok');
            //设置table
            $table.bootstrapTable({

            });
            $('.loader-inner').loaders();
            //点击执行搜索
            $ok.click(function () {
                $table.bootstrapTable('refresh');
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
            //到港状态
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
            //报关状态
            function declearState(value) {
                return value ? '已报关' : '未报关';
            }
            //获取操作button
            function actionFormatter(value, row, index) {

                if(row.import_state == 1){
                    var import_button =  '<a href="order/'+row['id']+'/sub" class="btn btn-xs btn-success"><i class="fa fa-play" data-toggle="tooltip" data-placement="top" title="查看分单">查看分单</i></a></i>';
                    var import_ID_button = '<a href="order/'+row['id']+'/ID" class="btn btn-xs btn-success"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="导出身份证" id="export">导出身份证</i></a> ';
                }else{
                    var import_button =  '<a href="javascript:void(0);" class="btn btn-xs btn-success" onclick="importSubOrder('+row['id']+')"><i class="fa fa-play" data-toggle="tooltip" data-placement="top" title="导入">导入</i></a></i>';
                    var import_ID_button = '';
                }
                var delete_button = '<a href="order/'+row['id']+'/destroy" data-method="delete" class="btn btn-xs btn-danger"><i class="fa fa-trash" data-toggle="tooltip" data-placement="top" title="删除">删除</i></a>';
                return import_button+import_ID_button+delete_button;
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
            //导入分单函数
            //
            function importSubOrder(id){
                $('#import').modal('show');
                //赋值订单ID
                $('#order_id').val(id);
            }
            //提交分单
            $(function (){
                //点
                var options = {
                    beforeSubmit:  showRequest,
                    success:       showResponse,
                    dataType: 'json'
                };
                $("#upload_button").click(function() {
                    var loader = '<div class="loader-inner ball-clip-rotate">'+
                            '<div></div>'+
                            '</div>';
                    $('#upload_button').html(loader);
                    //disable
                    $('#upload_button').attr('disabled','disabled');
                    $('#upload_form').ajaxForm(options).submit();
                    //执行loader css
                });
            })
            function showRequest() {
                $("#validation-errors").hide().empty();
                return true;
            }
            function showResponse(response)  {
                if(response.success == false)
                {
                    $('#upload_button').html('保存');
                    var responseErrors = response.errors;
                    $.each(responseErrors, function(index, value)
                    {
                        if (value.length != 0)
                        {
                            $("#validation-errors").append('<div class="alert alert-error"><strong>'+ value +'</strong><div>');
                        }
                    });
                    $("#validation-errors").show();
                    $('#upload_button').html('重新上传');
                    $('#upload_button').removeAttr('disabled');
//                window.setTimeout(function(){} ,4000);
//                location.reload();
                }else{
                    //上传成功
                    sweetAlert('上传成功');
                    window.setTimeout(function(){ } ,3000);
                    location.reload();
                }
            }
        </script>
    @endsection
@endsection