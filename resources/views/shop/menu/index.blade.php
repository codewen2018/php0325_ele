@extends("layouts.shop.default")

@section("content")
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <a href="{{route('menu.add')}}" class="btn btn-primary btn-sm">添加</a>

                    <div class="box-tools">

                        <form action="" class="form-inline">
                            <select name="cate_id" class="form-control">
                                <option value="">请选择分类</option>
                                @foreach($cates as $cate)
                                    <option value="{{$cate->id}}"
                                            @if($cate->id==request()->input('cate_id')) selected @endif >{{$cate->name}}</option>
                                @endforeach
                            </select>
                            <input type="text" name="minPrice" class="form-control" size="2" placeholder="最低价"
                                   value="{{request()->input('minPrice')}}"> -
                            <input type="text" name="maxPrice" class="form-control" size="2" placeholder="最高价"
                                   value="{{request()->input('maxPrice')}}">
                            <input type="text" name="keyword" class="form-control" placeholder="菜品名称"
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
                            <th>图片</th>
                            <th>名称</th>
                            <th>价格</th>
                            <th>分类</th>
                            <th>状态</th>
                            <th>操作</th>
                        </tr>
                        @foreach($menus as $menu)
                            <tr>
                                <td>{{$menu->id}}</td>
                                <td><img src="{{$menu->goods_img}}?x-oss-process=image/resize,w_50,h_50"></td>
                                <td>{{$menu->goods_name}}</td>
                                <td>{{$menu->goods_price}}</td>
                                <td>{{$menu->cate_id}}</td>
                                <td>{{$menu->status}}</td>
                                <td>
                                    <a href="{{route('menu.edit',$menu->id)}}" class="btn btn-info">编辑</a>
                                    <a href="{{route('menu.del',$menu->id)}}" class="btn btn-danger">删除</a>

                                </td>

                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{$menus->links()}}
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>

@endsection