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
            <h3 class="box-title">文章列表</h3>

            <div class="box-tools pull-right">
                @include('backend.article.includes.partials.header-buttons')
            </div>
        </div>

        <div class="box-body">
            <form method="POST" id="search-form" class="form-inline" role="form">

                <div class="form-group">
                    <label for="title">资讯标题</label>
                    <input type="text" class="form-control" name="title" id="title" placeholder="资讯标题">
                </div>
                <div class="form-group">
                    <label for="type">类型</label>
                    <input type="text" class="form-control" name="type" id="type" placeholder="资讯类型">
                </div>

                <button type="button" class="btn btn-primary" id="search">Search</button>
            </form>
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="article">
                    <thead>
                        <tr>
                            <th><input type="checkbox" name="select_all" value="1" id="example-select-all"></th>
                            <th>文章标题</th>
                            <th>文章作者</th>
                            <th>文章图片</th>
                            <th>文章类型</th>
                            <th>创建时间</th>
                            <th>更新时间</th>
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
        </div>
    </div>
    @section('after-scripts-end')
        @include('backend.includes.partials.jquerydatatable')
        <script>
            $(function () {
                var oTable = $('#article').DataTable({
                    lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                    searching: false,
                    processing: true,
                    serverSide: true,
                    dom: 'rt<"bottom"flp>',
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
                    language: {
                        "lengthMenu": "每页显示 _MENU_ 条数据",
                        "zeroRecords": "Nothing found - sorry",
                        "info": "Showing page _PAGE_ of _PAGES_",
                        "infoEmpty": "No records available",
                        "infoFiltered": "(filtered from _MAX_ total records)"
                    },
                    ajax: {
                        url: 'article/data',
                        data: function (d) {
                            d.title = $('input[name=title]').val();
                            d.type  = $('input[name=type]').val();
                        }
                    },
                    columns: [
                        {data: 'id'},
                        { data: 'title', name: 'title'},
                        { data: 'author', name: 'author'},
                        {
                            data: 'image',render: function(data, type, row) {
                            image = '<img src={{env('IMG_URL')}}'+data+'/>';
                            return image;
                        }
                        },
                        { data: 'name', name: 'type'},

                        { data: 'created_at', name: 'created_at' },
                        { data: 'updated_at', name: 'updated_at'},
                        {data: 'action', name: 'action', orderable: false, searchable: false}
                    ]
                });

                $("#search").click(function (){
                    oTable.$('input[type="checkbox"]').each(function(){
                        if(this.checked){
                            alert(this.value);
                        }
                    });
                });
//                $('#search-form').on('click', function(e) {
//                    oTable.$('input[type="checkbox"]').each(function(){
//                        alert('sfesf');
//                        if(this.checked){
//                            alert(this.value);
//                        }
//                    });
//                    //oTable.draw();
//                    //e.preventDefault();
//                });
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

        </script>
    @endsection
@endsection