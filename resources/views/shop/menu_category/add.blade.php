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
                    <input type="text" class="form-control" id="name" placeholder="分类名称" name="name"
                           value="{{old('name')}}">
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
                            <input type="radio" name="is_selected" value="1">是</label>

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

                <div class="form-group">
                    <label>图像</label>

                    <input type="text" class="form-control" id="img" name="logo">

                {{--  <!--用来存放文件信息-->
                  <div id="thelist" class="uploader-list"></div>
                  <div class="btns">
                      <div id="picker">选择文件</div>
                  </div>--}}


                <!--用来存放item-->
                    <div id="fileList" class="uploader-list"></div>
                    <div id="picker">选择图片</div>


                </div>


            </div>
            <!-- /.box-body -->

            <div class="box-footer">
                <button type="submit" class="btn btn-primary">提交</button>
            </div>

        </form>
    </div>

@endsection

@section("js")
    <script>
        var uploader = WebUploader.create({

            //自动提交
            auto: true,
            //CSRF
            formData: {
                "_token": "{{csrf_token()}}"
            },
            // swf文件路径
            swf: '/webuploader/Uploader.swf',

            // 文件接收服务端。
            server: '{{route('menu_cate.upload')}}',

            // 选择文件的按钮。可选。
            // 内部根据当前运行是创建，可能是input元素，也可能是flash.
            pick: '#picker',

            // 只允许选择图片文件。
            accept: {
                title: 'Images',
                extensions: 'gif,jpg,jpeg,bmp,png',
                mimeTypes: 'image/*'
            }
        });


        // 当有文件添加进来的时候
        uploader.on( 'fileQueued', function( file ) {
            var $li = $(
                '<div id="' + file.id + '" class="file-item thumbnail">' +
                '<img>' +
                '<div class="info">' + file.name + '</div>' +
                '</div>'
                ),
                $img = $li.find('img');


            // $list为容器jQuery实例
            var $list=$("#fileList");
            $list.append( $li );

            // 创建缩略图
            // 如果为非图片文件，可以不用调用此方法。
            // thumbnailWidth x thumbnailHeight 为 100 x 100
            uploader.makeThumb( file, function( error, src ) {
                if ( error ) {
                    $img.replaceWith('<span>不能预览</span>');
                    return;
                }

                $img.attr( 'src', src );
            }, 100, 100 );
        });
        // 文件上传过程中创建进度条实时显示。
        uploader.on( 'uploadProgress', function( file, percentage ) {
            var $li = $( '#'+file.id ),
                $percent = $li.find('.progress span');

            // 避免重复创建
            if ( !$percent.length ) {
                $percent = $('<p class="progress"><span></span></p>')
                    .appendTo( $li )
                    .find('span');
            }

            $percent.css( 'width', percentage * 100 + '%' );
        });

        // 文件上传成功，给item添加成功class, 用样式标记上传成功。
        uploader.on( 'uploadSuccess', function( file,data ) {
            $( '#'+file.id ).addClass('upload-state-done');

            //设置img的值
            $("#img").val(data.url);
        });

        // 文件上传失败，显示上传出错。
        uploader.on( 'uploadError', function( file ) {
            var $li = $( '#'+file.id ),
                $error = $li.find('div.error');

            // 避免重复创建
            if ( !$error.length ) {
                $error = $('<div class="error"></div>').appendTo( $li );
            }

            $error.text('上传失败');
        });

        // 完成上传完了，成功或者失败，先删除进度条。
        uploader.on( 'uploadComplete', function( file ) {
            $( '#'+file.id ).find('.progress').remove();
        });
    </script>
@endsection