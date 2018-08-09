@extends("layouts.admin.default")
@include('vendor.ueditor.assets')
@section("content")
    <form class="form-horizontal" action="" method="post">
        {{csrf_field()}}
        <div class="form-group">
            <label class="col-sm-2 control-label">名称</label>
            <div class="col-sm-10">
                <input type="text" name="name" class="form-control" value="{{old('name')}}">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">活动</label>
            <div class="col-sm-10"><select name="event_id" class="form-control">
                    <option value="">请选择活动</option>
                    @foreach($events as $event)
                        <option value="{{$event->id}}">{{$event->title}}</option>
                    @endforeach
                </select></div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">描述</label>
            <div class="col-sm-10">
                <!-- 编辑器容器 -->
                <script id="container" name="description" type="text/plain"></script>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-default">提交</button>
            </div>
        </div>
    </form>
@endsection
@section("js")
    <!-- 实例化编辑器 -->
    <script type="text/javascript">
        var ue = UE.getEditor('container');
        ue.ready(function () {
            ue.execCommand('serverparam', '_token', '{{ csrf_token() }}'); // 设置 CSRF token.
        });
    </script>
@endsection