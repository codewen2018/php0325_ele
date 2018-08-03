@extends("layouts.admin.default")

@section("content")
    <a href="{{route('admin.role.add')}}" class="btn btn-primary">添加</a>
    <table class="table table-bordered">
        <tr>
            <th>Id</th>
            <th>角色名</th>
            <th>拥有权限</th>

            <th>操作</th>
        </tr>
        @foreach($roles as $role)
            <tr>
                <td>{{$role->id}}</td>
                <td>{{$role->name}}</td>
                <td>{{ str_replace(['[',']','"'],'', $role->permissions()->pluck('name')) }}</td>

                <td>
                    <a href="{{route('admin.role.edit',$role->id)}}" class="btn btn-info">编辑</a>

                    <a href="{{route('admin.role.del',$role->id)}}" class="btn btn-danger">删除</a>

                </td>
            </tr>
        @endforeach
    </table>

@endsection