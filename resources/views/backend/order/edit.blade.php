@extends ('backend.layouts.master')
@section ('title', '订单管理 | 新增订单')

@section('page-header')
    <h1>
        订单管理
        <small>新增订单</small>
    </h1>
@endsection

@section('content')
    {!! Form::model($order, ['route' => ['admin.order.update', $order->id], 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'PATCH', 'files' => true]) !!}
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
                {!! Form::label('express_id' , '境内快递商', ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-10">
                    <select name="express_id" class="form-control">
                        <option value="">{{ trans('labels.general.none') }}</option>

                        @foreach ($expresses as $express)
                            <option value="{!! $express->id !!}">{!! $express->name !!}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('company', '公司名称', ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-10">
                    {!! Form::text('company', $order->user->company, ['class' => 'form-control', 'placeholder' => '公司名称']) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('company_area', '公司地区', ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-10">
                    {!! Form::text('company_area', $order->user->company_area, ['class' => 'form-control', 'placeholder' => '公司地区']) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('express_name', '境内快递商', ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-10">
                    {!! Form::text('express_name', $order->express->name, ['class' => 'form-control', 'placeholder' => '境内快递商']) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('express_name', '税金金额', ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-10">
                    {!! Form::text('express_name', $order->tax, ['class' => 'form-control', 'placeholder' => '税金金额']) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('sub_total', '分单总数', ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-10">
                    {!! Form::text('sub_total', $order->tax, ['class' => 'form-control', 'placeholder' => '分单总数']) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('clearance', '海关放行数', ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-10">
                    {!! Form::text('clearance', $order->clearance, ['class' => 'form-control', 'placeholder' => '海关放行数']) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('inspection', '海关查验数', ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-10">
                    {!! Form::text('inspection', $order->inspection, ['class' => 'form-control', 'placeholder' => '海关查验数']) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('port_state', '到港状态', ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-10">
                    {!! Form::select('port_state', array('1' => '未到港', '2' => '清关中', '3' => '已到港'), $order->port_state) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('declear_state', '报关状态', ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-10">
                    {!! Form::select('declear_state', array('0' => '未报关', '1' => '已报关'), $order->declear_state) !!}
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('ported_at', '到岗时间', ['class' => 'col-lg-2 control-label']) !!}
                <div class="col-lg-10">
                    {!! Form::text('ported_at', $order->ported_at, ['class' => 'form-control', 'placeholder' => '到岗时间']) !!}
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