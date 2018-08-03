@extends("layouts.admin.default")

@section("content")
    <form class="form-horizontal" action="" method="post">
        {{csrf_field()}}
        <div class="form-group">
            <label class="col-sm-2 control-label">菜单名称</label>
            <div class="col-sm-10">
                <input type="text" name="name" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">路由</label>
            <div class="col-sm-10">
                <select name="url" class="form-control">
                    <option value="">请选择路由</option>
                    @foreach($urls as $url)
                        <option>{{$url}}</option>
                    @endforeach
                </select>

            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">上级菜单</label>
            <div class="col-sm-10">
                <select name="parent_id" class="form-control">
                    <option value="0">顶级分类</option>
                    @foreach($navs as $nav)
                        <option value="{{$nav->id}}">{{$nav->name}}</option>
                    @endforeach
                </select>

            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">排序</label>
            <div class="col-sm-10">
                <input type="text" name="sort" class="form-control" value="100">
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-default">提交</button>
            </div>
        </div>
    </form>
@endsection
