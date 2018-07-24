
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">添加分类</h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <form role="form" method="post" action="" enctype="multipart/form-data">
            {{csrf_field()}}
            <div class="box-body">

                <input type="file" name="img">

            </div>
            <!-- /.box-body -->

            <div class="box-footer">
                <button type="submit" class="btn btn-primary">提交</button>
            </div>

        </form>
    </div>
