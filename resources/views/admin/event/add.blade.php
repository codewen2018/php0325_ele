@extends("layouts.admin.default")
@include('vendor.ueditor.assets')
@section("content")
    <form class="form-horizontal" action="" method="post">
        {{csrf_field()}}
        <div class="form-group">
            <label class="col-sm-2 control-label">名称</label>
            <div class="col-sm-10">
                <input type="text" name="title" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">最多报人人数</label>
            <div class="col-sm-10">
                <input type="text" name="num" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">content</label>
            <div class="col-sm-10">
                <!-- 编辑器容器 -->
                <script id="container" name="content" type="text/plain"></script>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">开始时间</label>
            <div class="col-sm-10">
                <input type="datetime-local" name="start_time" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">结束时间</label>
            <div class="col-sm-10">
                <input type="datetime-local" name="end_time" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">开奖时间</label>
            <div class="col-sm-10">
                <input type="datetime-local" name="prize_time" class="form-control">
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