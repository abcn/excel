@extends ('backend.layouts.master')
@section ('title', '订单管理 | 新增订单')

@section('page-header')
    <h1>
        订单管理
        <small>新增订单</small>
    </h1>
@endsection

@section('content')
    {!! Form::open(['route' => 'admin.order.store', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'post', 'files' => true]) !!}
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">新增订单</h3>

            <div class="box-tools pull-right">
                @include('backend.order.includes.partials.header-buttons')
            </div>
        </div><!-- /.box-header -->

        <div class="box-body">
            <div class="form-group">
                {!! Form::label('transport_type', '航运类型', ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-10">
                    {!! Form::select('transport_type', array('1' => '空运单号', '2' => '海运单号'), '2') !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('transport_number', '航班号', ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-10">
                    {!! Form::text('transport_number', null, ['class' => 'form-control', 'placeholder' => '航运单号']) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('order_number', '主单号', ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-10">
                    {!! Form::text('order_number', null, ['class' => 'form-control', 'placeholder' => '主单号']) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('weight', '净重', ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-10">
                    {!! Form::text('weight', null, ['class' => 'form-control', 'placeholder' => '净重']) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('type' , '境内快递商', ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-10">
                    <select name="type" class="form-control">
                        <option value="">{{ trans('labels.general.none') }}</option>

                        @foreach ($expresses as $express)
                            <option value="{!! $express->id !!}">{!! $express->name !!}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="box box-info">
        <div class="box-body">
            <div class="pull-left">
                <a href="{{route('admin.article.index')}}" class="btn btn-danger btn-xs">{{ trans('buttons.general.cancel') }}</a>
            </div>

            <div class="pull-right">
                <input type="submit" class="btn btn-success btn-xs" value="{{ trans('buttons.general.crud.create') }}" />
            </div>
            <div class="clearfix"></div>
        </div><!-- /.box-body -->
    </div><!--box-->
    {!! Form::close() !!}
@endsection