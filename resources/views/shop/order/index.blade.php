@extends("layouts.shop.default")

@section("content")
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <a href="" class="btn btn-primary btn-sm disabled">添加</a>

                    <div class="box-tools">

                        <form action="" class="form-inline">
                            <select name="status" class="form-control">
                                <option value="">订单状态</option>
                                @foreach(\App\Models\Order::$statusText as $k=>$v)
                                    <option value="{{$k}}">{{$v}}</option>
                                @endforeach
                                {{--  <option value="0">待支付</option>
                                  <option value="1">待发货</option>
                                  <option value="2">待确认</option>
                                  <option value="3">已完成</option>--}}
                            </select>
                            <input type="text" name="keyword" class="form-control" placeholder="订单编号"
                                   value="{{request()->input('keyword')}}">
                            <input type="submit" value="搜索" class="btn btn-success">
                        </form>


                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <tbody>
                        <tr>
                            <th>ID</th>
                            <th>订单编号</th>
                            <th>地址</th>
                            <th>姓名</th>
                            <th>电话</th>
                            <th>状态</th>
                            <th>金额</th>
                            <th>操作</th>
                        </tr>
                        @foreach($orders as $order)
                            <tr>
                                <td>{{$order->id}}</td>
                                <td>{{$order->sn}}</td>
                                <td>{{$order->detail_address}}</td>
                                <td>{{$order->name}}</td>
                                <td>{{$order->tel}}</td>
                                <td>{{$order->OrderStatus}}</td>
                                <td>{{$order->total}}</td>
                                <td>
                                    <a href="{{route('order.detail',$order->id)}}"
                                       class="btn btn-sm btn-warning">查看</a>
                                    {{--    <a href="{{route('order.edit',$order->id)}}" class="btn btn-sm btn-info">编辑</a>--}}
                                    @if($order->status===0)
                                        <a href="{{route('order.changeStatus',[$order->id,1])}}"
                                           class="btn btn-sm btn-info">发货</a>
                                    @endif
                                    @if($order->status===1)
                                        <a href="{{route('order.changeStatus',[$order->id,2])}}"
                                           class="btn btn-sm btn-primary">确认</a>
                                    @endif
                                    @if($order->status===2)
                                        <a href="{{route('order.changeStatus',[$order->id,3])}}"
                                           class="btn btn-sm btn-success">完成</a>
                                    @endif
                                    @if($order->status!==-1 && $order->status!==3)
                                        <a href="{{route('order.changeStatus',[$order->id,-1])}}"
                                           class="btn btn-sm btn-danger">取消</a>
                                    @endif
                                </td>

                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{$orders->links()}}
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>

@endsection