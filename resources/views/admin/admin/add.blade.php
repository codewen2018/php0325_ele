@extends("layouts.admin.default")

@section("content")
    <form class="form-horizontal" action="" method="post">
        {{csrf_field()}}
        <div class="form-group">
            <label class="col-sm-2 control-label">名称</label>
            <div class="col-sm-10">
                <input type="text" name="name" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">密码</label>
            <div class="col-sm-10">
                <input type="text" name="password" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">Email</label>
            <div class="col-sm-10">
                <input type="text" name="email" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">角色名称</label>
            <div class="col-sm-10">
                @foreach($roles as $role)
                <input type="checkbox" name="role[]" value="{{$role->name}}">{{$role->name}}
                @endforeach
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-default">提交</button>
            </div>
        </div>
    </form>
@endsection
