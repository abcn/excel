@extends('backend.layouts.master')

@section('page-header')
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

        <div class="box-body">

            <form method="POST" id="search-form" class="form-inline" role="form">
                <div class="form-group">
                    <label for="company">公司名称</label>
                    <input type="text" class="form-control" name="company" id="company" placeholder="公司名称">
                </div>
                <div class="form-group">
                    <label for="company_area">公司地区</label>
                    <input type="text" class="form-control" name="company_area" id="company_area" placeholder="公司地区">
                </div>
                <div class="form-group">
                    <label for="transport_type">航运类型</label>
                    <select name="transport_type" class="form-control">
                        <option value="">请选择</option>
                        <option value="1">空运单号</option>
                        <option value="2">海运单号</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="type">航班号</label>
                    <input type="text" class="form-control" name="transport_number" id="transport_number" placeholder="航班号">
                </div>

                <div class="form-group">
                    <label for="type">&nbsp;&nbsp;主 单 号</label>
                    <input type="text" class="form-control" name="order_number" id="order_number" placeholder="主单号">
                </div>

                <button type="submit" class="btn btn-primary" id="search">Search</button>
            </form>
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover display nowrap" id="article" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th><input type="checkbox" name="select_all" value="1" id="example-select-all"></th>
                        <th>公司名称</th>
                        <th>公司地区</th>
                        <th>航运类型</th>
                        <th>航班号</th>
                        <th>主单号</th>
                        <th>净重</th>
                        <th>境内快递商</th>
                        <th>税金金额</th>
                        <th>分单总数</th>
                        <th>海关放行数</th>
                        <th>海关查验数</th>
                        <th>到港状态</th>
                        <th>报关状态</th>
                        <th>到岗时间</th>
                        <th>报关时间</th>
                        <th>操作</th>
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
        </div>
    </div>
    @section('after-scripts-end')
        @include('backend.includes.partials.jquerydatatable')
        {{--ajax form--}}
        <script src="{{asset('js/backend/jquery.form.js')}}"></script>
        <script>
            $(function () {
                var oTable = $('#article').DataTable({
                    lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                    searching: false,
                    processing: true,
                    serverSide: true,
                    bDeferRender : true,//延迟加载
//                    autoWidth: false,
                    columnDefs: [{
                        'targets': 0,
                        'searchable':false,
                        'orderable':false,
                        'className': 'dt-body-center',
                        'render': function (data, type, full, meta){
                            return '<input type="checkbox" name="id[]" value="'
                                    + $('<div/>').text(data).html() + '">';
                        }
                    }],
                    oLanguage : {
                        "sLengthMenu" : "每页显示 _MENU_ 条记录",
                        "sZeroRecords" : "对不起，没有匹配的数据",
                        "sInfo" : "第 _START_ - _END_ 条 / 共 _TOTAL_ 条数据",
                        "sInfoEmpty" : "没有匹配的数据",
                        "sInfoFiltered" : "(数据表中共 _MAX_ 条记录)",
                        "sProcessing" : "正在加载中...",
                        "sSearch" : "全文搜索：",
                        "oPaginate" : {
                            "sFirst" : "第一页",
                            "sPrevious" : " 上一页 ",
                            "sNext" : " 下一页 ",
                            "sLast" : " 最后一页 "
                        }
                    },
                    ajax: {
                        url: 'order/data',
                        data: function (d) {
                            d.company = $('input[name=company]').val();
                            d.company_area  = $('input[name=company_area]').val();
                            d.transport_type  = $('select[name=transport_type]').val();
                            d.transport_number  = $('input[name=transport_number]').val();
                            d.order_number  = $('input[name=order_number]').val();
                        }
                    },
                    columns: [
                        {data: 'id'},
                        { data: 'company', name: 'company'},
                        { data: 'company_area', name: 'company_area'},
                        { data: 'transport_type', name: 'transport_type'},
                        { data: 'transport_number', name: 'transport_number'},
                        { data: 'order_number', name: 'order_number'},
                        { data: 'weight', name: 'weight'},
                        { data: 'expressName', name: 'expressName'},
                        { data: 'tax', name: 'tax'},
                        { data: 'sub_total', name: 'sub_total'},
                        { data: 'clearance', name: 'clearance'},
                        { data: 'inspection', name: 'inspection'},
                        { data: 'port_state',render: function(data,type,row){
                            var port_state = '';
                            switch (data){
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
                        }},
                        { data: 'declear_state', render: function(data,type,row){
                            return data ? '已报关' : '未报关';
                        }},

                        { data: 'ported_at', name: 'ported_at' },
                        { data: 'decleared_at', name: 'decleared_at'},
                        { data: 'action', name: 'action', orderable: false, searchable: false}
                    ]
                });
                //搜索功能
                $('#search-form').on('submit', function(e) {
                    oTable.draw();
                    e.preventDefault();
                });

                // Handle click on "Select all" control
                $('#example-select-all').on('click', function(){
                    // Check/uncheck all checkboxes in the table
                    var rows = oTable.rows({ 'search': 'applied' }).nodes();
                    $('input[type="checkbox"]', rows).prop('checked', this.checked);
                });

                // Handle click on checkbox to set state of "Select all" control
                $('#example tbody').on('change', 'input[type="checkbox"]', function(){
                    // If checkbox is not checked
                    if(!this.checked){
                        var el = $('#example-select-all').get(0);
                        // If "Select all" control is checked and has 'indeterminate' property
                        if(el && el.checked && ('indeterminate' in el)){
                            // Set visual state of "Select all" control
                            // as 'indeterminate'
                            el.indeterminate = true;
                        }
                    }
                });

            });

        $(function (){
            //点
            var options = {
                beforeSubmit:  showRequest,
                success:       showResponse,
                dataType: 'json'
            };
            $("#upload_button").click(function() {
                $('#upload_button').html('正在上传...');
                //disable
                $('#upload_button').attr('disabled','disabled');
                $('#upload_form').ajaxForm(options).submit();
            });
        })
        function showModal(id){
            $('#import').modal('show');
            //为隐藏字段赋值
            $('#order_id').val(id);
        }
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