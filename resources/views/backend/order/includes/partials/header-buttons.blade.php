    <div class="pull-right" style="margin-bottom:10px">
        <div class="btn-group">
          <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
              订单管理 <span class="caret"></span>
          </button>
          <ul class="dropdown-menu" role="menu">
              <li><a href="{{ route('admin.order.index') }}">所有订单</a></li>
            {{--创建文章--}}
            @permission('create-article')
                <li><a href="{{ route('admin.order.create') }}">新增订单</a></li>
            @endauth
            {{--end创建文章--}}

            <li class="divider"></li>
          </ul>
        </div><!--btn group-->
    </div><!--pull right-->

    <div class="clearfix"></div>