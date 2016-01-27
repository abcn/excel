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
            <h3 class="box-title">轮播列表</h3>
        </div>

        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                    <thread>
                        <tr>
                            <th>轮播id</th>
                            <th>轮播标题</th>
                            <th>轮播图片</th>
                            <th>创建时间</th>
                            <th>操作</th>
                        </tr>
                    </thread>
                    <tbody>
                        @foreach($carousels as $carousel)
                            <tr>
                                <td>{{$carousel->id}}</td>
                                <td>{!! $carousel->title !!}</td>
                                <td><img src="{{$carousel->image_src}}" width="50" height="50"></td>
                                <td>{{$carousel->created_at->diffForHumans()}}</td>
                                <td>{!! $carousel->action_buttons !!}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection