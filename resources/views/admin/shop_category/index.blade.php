@extends("layouts.admin.default")

@section("title","列表")

@section("content")

    <form action="" method="get">

        <input type="text" name="keyword" placeholder="搜索用户名或EMAIL">
        <input type="submit" value="搜索">
    </form>
    <table class="table table-bordered">
        <tr>
            <th>Id</th>
            <th>名称</th>
            <th>LOGO</th>
            <th>状态</th>
            <th>操作</th>
        </tr>

        @foreach($cates as $cate)
            <tr>
                <td>{{$cate->id}}</td>
                <td>{{$cate->name}}</td>
                <td>
                    <img src="/uploads/{{$cate->logo}}" width="40">

                </td>
                <td>
                    <a href="/">编辑</a>
                    <a href="/">删除</a>

                </td>
            </tr>
        @endforeach
    </table>
@endsection