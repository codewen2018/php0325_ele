@extends("layouts.shop.default")

@section("content")
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <a href="{{route('menu_cate.add')}}" class="btn btn-primary btn-sm">添加</a>

                    {{--          <div class="box-tools">
                                  <div class="input-group input-group-sm" style="width: 150px;">
                                      <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">

                                      <div class="input-group-btn">
                                          <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                                      </div>
                                  </div>
                              </div>--}}
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <tbody>
                        <tr>
                            <th>ID</th>
                            <th>名称</th>
                            <th>描述</th>
                            <th>默认</th>
                            <th>操作</th>
                        </tr>
                        @foreach($cates as $cate)
                            <tr>
                                <td>{{$cate->id}}</td>
                                <td>{{$cate->name}}</td>
                                <td>{{$cate->description}}</td>
                                <td>{{$cate->is_selected}}</td>
                                <td>
                                    <a href="{{route('menu_cate.edit',$cate->id)}}" class="btn btn-info">编辑</a>
                                    <a href="{{route('menu_cate.del',$cate->id)}}" class="btn btn-danger">删除</a>

                                </td>

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