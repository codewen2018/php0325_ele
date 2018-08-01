@extends("layouts.shop.default")

@section("content")
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <a href="{{route('menu.add')}}" class="btn btn-primary btn-sm disabled">添加</a>

                    <div class="box-tools">

                        <form action="" class="form-inline">

                            <input type="date" name="start" class="form-control" size="2" placeholder="开始日期"
                                   value="{{request()->input('start')}}"> -
                            <input type="date" name="end" class="form-control" size="2" placeholder="结束日期"
                                   value="{{request()->input('end')}}">
                            <input type="submit" value="搜索" class="btn btn-success">
                        </form>


                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <tbody>
                        <tr>
                            <th>日期</th>
                            <th>订单数</th>
                            <th>收入</th>
                        </tr>
                        @foreach($orders as $order)
                            <tr>
                                <td>{{$order->day}}</td>

                                <td>{{$order->count}}</td>
                                <td>{{$order->money}}</td>

                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>

@endsection