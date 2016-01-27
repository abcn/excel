@extends ('backend.layouts.master')
@section('title', '分单管理|分单详情')

@section('page-header')
    <h1>
        分单管理
        <small>分单详情</small>
    </h1>
@endsection

@section('content')
    <div class="box box-success">
        <div class="box box-header">
            <h3 class="box-title">分单详情</h3>

            <div class="box-tools pull-right">
                @include('backend.order.includes.partials.header-buttons')
            </div>
        </div>
    </div>
    <div class="box box-body" style="width: 70%">
        <div class="form-group">
            {!! Form::label('title', '境内快递商', ['class' => 'col-lg-2 control-label']) !!}
            <div class="col-lg-10">
                {!! Form::text('title', null, ['class' => 'form-control', 'placeholder' => '境内快递商']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('abstract', '国内单号', ['class' => 'col-lg-2 control-label']) !!}
            <div class="col-lg-10">
                {!! Form::text('abstract', null, ['class' => 'form-control', 'placeholder' => '国内单号']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('sended_at', '派送时间', ['class' => 'col-lg-2 control-label']) !!}
            <div class="col-lg-10">
                {!! Form::text('sended_at', $subOrder->sended_at, ['class' => 'form-control', 'placeholder' => '派送时间']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('arrivaled_at', '签收时间', ['class' => 'col-lg-2 control-label']) !!}
            <div class="col-lg-10">
                {!! Form::text('arrivaled_at', $subOrder->arrivaled_at, ['class' => 'form-control', 'placeholder' => '签收时间']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('arrivaled_at', '派送备注', ['class' => 'col-lg-2 control-label']) !!}
            <div class="col-lg-10">
                {!! Form::textarea('arrivaled_at', $subOrder->express_remark, ['class' => 'form-control', 'placeholder' => '派送备注']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('id_image', '身份证照片', ['class' => 'col-lg-2 control-label']) !!}
            <div class="col-lg-10">
                <img src={{env('IMG_URL').$subOrder->order->id_image}} class="form-control" />
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('id_image', '税单照片', ['class' => 'col-lg-2 control-label']) !!}
            <div class="col-lg-10">
                <img src={{env('IMG_URL').$subOrder->tax_image}} class="form-control"/>
            </div>
        </div>
    </div>
    <div class="box box-info">
        <div class="box-body">
            <div class="pull-left">
                <a href="{{route('order.sub',$subOrder->order_id)}}" class="btn btn-danger btn-xs">{{ trans('buttons.general.cancel') }}</a>
            </div>
            <div class="clearfix"></div>
        </div><!-- /.box-body -->
    </div><!--box-->
@endsection