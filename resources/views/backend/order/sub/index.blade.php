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
            <h3 class="box-title">查看分单</h3>

            <div class="box-tools pull-right">
                @include('backend.order.includes.partials.header-buttons')
            </div>
        </div>

        <div class="box-body">
            <input type="hidden" value="{{$orderId}}" name="orderId" id="orderId">
            <form method="POST" id="search-form" class="form-inline" role="form">
                <div class="form-group">
                    <label for="fw_number">国外单号</label>
                    <input type="text" class="form-control" name="fw_number" id="fw_number" placeholder="国外单号">
                </div>
                <div class="form-group">
                    <label for="name">姓名</label>
                    <input type="text" class="form-control" name="name" id="name" placeholder="姓名">
                </div>
                <div class="form-group">
                    <label for="mobile">电话</label>
                    <input type="text" class="form-control" name="mobile" id="mobile" placeholder="电话">
                </div>
                <div class="form-group">
                    <label for="id_number">身份证号</label>
                    <input type="text" class="form-control" name="id_number" id="id_number" placeholder="身份证号">
                </div>
                <div class="form-group">
                    <label for="clearance_state">放行状态</label>
                    <select name="clearance_state" class="form-control">
                        <option value="">请选择</option>
                        <option value="0">未放行</option>
                        <option value="1">查验</option>
                        <option value="2">放行</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="tax_state">缴税状态</label>
                    <select name="tax_state" class="form-control">
                        <option value="">请选择</option>
                        <option value="0">免税</option>
                        <option value="1">需缴税</option>
                        <option value="2">已缴税</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="send_state">派送状态</label>
                    <select name="send_state" class="form-control">
                        <option value="">派送状态</option>
                        <option value="0">未派送</option>
                        <option value="1">已派送</option>
                        <option value="2">已签收</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary" id="search">Search</button>
            </form>
            <div class="table-responsive">
                <button type="button" class="btn btn-primary" id="fw_sync">国内外单号同步</button>
                <button type="button" class="btn btn-primary" id="pass">批量海关放行</button>
                <button type="button" class="btn btn-primary" id="check">批量海关查验</button>
                <button type="button" class="btn btn-primary" id="send">批量派送</button>
                <button type="button" class="btn btn-primary" id="arrival">批量签收</button>
                <table class="table table-striped table-bordered table-hover display nowrap" id="article" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th><input type="checkbox" name="select_all" value="1" id="example-select-all"></th>
                            <th>序号</th>
                            <th>国外单号</th>
                            <th>姓名</th>
                            <th>电话</th>
                            <th>省份证/护照号</th>
                            <th>地址</th>
                            <th>邮编</th>
                            <th>分单计费重量</th>
                            <th>放行状态</th>
                            <th>缴税金额</th>
                            <th>缴税状态</th>
                            <th>派送状态</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th></th>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- Modal Import-->
            <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" id="remark">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">新增</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="remark">备注信息</label>
                                <textarea class="form-control" name="remark" id="remark_text" placeholder="备注信息"></textarea>
                            </div>
                            <input type="hidden" name="type" id="type"/>
                            <input type="hidden" name="subOrder_id" id="subOrder_id"/>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal" id="remark_button">保存</button>
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
                var orderId = $("#orderId").val();
                var oTable = $('#article').DataTable({
                    lengthMenu: [[500, 1000, 1500, -1], [500, 1000, 1500, "All"]],
                    searching: false,
                    processing: true,
                    serverSide: true,
                    bDeferRender : true,//延迟加载
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
                        url: 'subData',
                        data: function (d) {
                            d.name = $('input[name=name]').val();
                            d.mobile  = $('input[name=mobile]').val();
                            d.fw_number  = $('input[name=fw_number]').val();
                            d.id_number  = $('input[name=id_number]').val();
                            d.clearance_state  = $('select[name=clearance_state]').val();
                            d.tax_state  = $('select[name=tax_state]').val();
                            d.send_state  = $('select[name=send_state]').val();
                        }
                    },
                    columns: [
                        {data: 'id'},
                        { data: 'excel_id', name: 'excel_id'},
                        { data: 'fw_number', name: 'fw_number'},
                        { data: 'name', name: 'name'},
                        { data: 'mobile', name: 'mobile'},
                        { data: 'id_number', name: 'id_number'},
                        { data: 'address', name: 'address'},
                        { data: 'zip_code', name: 'zip_code'},
                        { data: 'weight', name: 'weight'},
                        { data: 'clearance_state',render: function(data,type,row){
                            var clearance_state = '';
                            switch (data){
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
                                    port_state = '未派送';
                            }
                            return clearance_state;
                        }},
                        { data: 'tax_amount', name: 'tax_amount'},
                        { data: 'tax_state', name: 'tax_state'},
                        { data: 'send_state',render: function(data,type,row){
                            var send_state = '';
                            switch (data){
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
                        }},
                        { data: 'action', name: 'action', orderable: false, searchable: false}
                    ]
                });
                //搜索功能
                $('#search-form').on('submit', function(e) {
                    oTable.draw();
                    e.preventDefault();
                });
                //批量操作 签收
                $("#arrival").click(function (){
                    var arrival = new Array();
                    oTable.$('input[type="checkbox"]').each(function(){
                        if(this.checked){
                            arrival.push(this.value);
                        }
                    });
                    post_batch('{{route('admin.subOrder.arrival')}}',arrival);
                });
                //批量操作 派送
                $("#send").click(function (){
                    var send = new Array();
                    oTable.$('input[type="checkbox"]').each(function(){
                        if(this.checked){
                            send.push(this.value);
                        }
                    });
                    post_batch('{{route('admin.subOrder.send')}}',send);
                });
                //批量操作 查验
                $("#check").click(function (){
                    var check = new Array();
                    oTable.$('input[type="checkbox"]').each(function(){
                        if(this.checked){
                            check.push(this.value);
                        }
                    });
                    post_batch('{{route('admin.subOrder.check')}}',check);
                });
                //批量操作 放行
                $("#pass").click(function (){
                    var pass = new Array();
                    oTable.$('input[type="checkbox"]').each(function(){
                        if(this.checked){
                            pass.push(this.value);
                        }
                    });
                    post_batch('{{route('admin.subOrder.pass')}}',pass);
                });

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

        function check(id){

            post_batch('/admin/subOrder/check',id);
        }
        function pass(id){
            $('#remark').modal('show');
            $('#type').val('pass');
            $('#subOrder_id').val(id);
            ///post_batch('/admin/subOrder/pass',id);

        }
        $("#remark_button").click(function (){
            var type = $("#type").val();
            var id = $("#subOrder_id").val();
            var remark = $("#remark_text").val();
            $.ajax({
                type:'POST',
                url:'/admin/subOrder/'+ type,
                data:{id:id,type:type,remark:remark},
                success: function(result){
                    //初始化文本
                    $("#type").val('');
                    $("#subOrder_id").val('');
                    $("#remark_text").val('');
                    swal({
                        title: '提示',
                        text:  result.message,
                        type: "success",
                        timer: 3000,
                        showConfirmButton: false
                    });
                    window.setTimeout(function(){ } ,3000);
                    location.reload();
                }
            })
        });
        function send(id){
            $('#remark').modal('show');
            $('#type').val('send');
            $('#subOrder_id').val(id);
            //post_batch('/admin/subOrder/send',id);
        }
        function arrival(id){
            post_batch('/admin/subOrder/arrival',id);
        }

        //批量操作函数
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
            }else{
                //上传成功
                sweetAlert('上传成功');
                $('#import').modal('hide')
            }
        }
        </script>
    @endsection
@endsection