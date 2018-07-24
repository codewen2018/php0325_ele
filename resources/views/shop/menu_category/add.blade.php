@extends("layouts.shop.default")

@section("content")
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">添加分类</h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <form role="form" method="post" action="">
            {{csrf_field()}}
            <div class="box-body">
                <div class="form-group">
                    <label for="name">名称</label>
                    <input type="text" class="form-control" id="name" placeholder="分类名称" name="name" value="{{old('name')}}">
                </div>
                <div class="form-group">
                    <label for="type_accumulation">菜品编号</label>
                    <input type="text" class="form-control" id="type_accumulation" placeholder="菜品编号"
                           name="type_accumulation" value="{{old('type_accumulation')}}">
                </div>
                <div class="form-group">
                    <label for="is_selected">是否默认</label>
                    <div class="radio">
                        <label>
                            <input type="radio" name="is_selected" value="1" >是</label>

                        <label>
                            <input type="radio" name="is_selected" value="0" checked>否</label>
                    </div>

                </div>
                <div class="form-group">
                    <label for="description">描述</label>
                    <textarea class="form-control" name="description">
{{old('description')}}
                    </textarea>

                </div>

            </div>
            <!-- /.box-body -->

            <div class="box-footer">
                <button type="submit" class="btn btn-primary">提交</button>
            </div>

        </form>
    </div>

@endsection