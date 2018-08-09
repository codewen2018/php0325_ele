@extends("layouts.shop.default")

@section("content")
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <a href="#" class="btn btn-primary disabled">添加</a>
                <div class="box-body table-responsive no-padding">
                    <table class="table table-bordered">
                        <tr>
                            <th>Id</th>
                            <th>标题</th>
                            <th>报名时间</th>
                            <th>结束时间</th>
                            <th>开奖时间</th>
                            <th>报名人数/总数</th>
                            <th>开奖状态</th>
                            <th>操作</th>
                        </tr>
                        @foreach($events as $event)
                            <tr>
                                <td>{{$event->id}}</td>
                                <td>{{$event->title}}</td>
                                <td>{{date('Y-m-d H:i:s',$event->start_time)}}</td>
                                <td>{{date('Y-m-d H:i:s',$event->end_time)}}</td>
                                <td>{{date('Y-m-d H:i:s',$event->prize_time)}}</td>
                                <td>{{$event->users->count()}}/{{$event->num}}</td>
                                <td>{{$event->is_prize}}</td>

                                <td>
                                    <a href="" class="btn btn-info">详情</a>
                                    @if(time()>$event->start_time && time()<$event->end_time && $event->users->count()<$event->num)
                                        <a href="" class="btn btn-danger">报名</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection