@extends("layouts.admin.default")

@section("content")

    <table class="table table-bordered">
        <tr>
            <th>Id</th>
            <th>名称</th>
            <th>分类</th>
            <th>LOGO</th>
            <th>状态</th>
            <th>操作</th>
        </tr>
        @foreach($shops as $shop)
            <tr>
                <td>{{$shop->id}}</td>
                <td>{{$shop->shop_name}}</td>
                <td>{{$shop->cate->name}}</td>
                <td><img src="/{{$shop->shop_img}}" height="30" width="30"></td>
                <td>{{\App\Models\Shop::$statusArray[$shop->status]}}</td>
                <td>编辑
                    删除
                    @if($shop->status===0)
                        <a href="{{route('admin.shop.changeStatus',$shop->id)}}">通审</a>
                    @endif
                </td>
            </tr>
    </table>
    @endforeach
@endsection