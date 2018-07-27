@extends("layouts.shop.default")
@section("content")
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">添加菜品</h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <form role="form" method="post" action="">
            {{csrf_field()}}
            <div class="box-body">
                <div class="form-group">
                    <select name="cate_id" class="form-control">
                        @foreach($cates as $cate)
                            <option value="{{$cate->id}}">{{$cate->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="name">名称</label>
                    <input type="text" class="form-control" id="name" placeholder="分类名称" name="goods_name"
                           value="{{old('goods_name')}}">
                </div>
                <div class="form-group">
                    <label for="goods_price">价格</label>
                    <input type="text" class="form-control" id="goods_price" placeholder="菜品编号"
                           name="goods_price" value="{{old('goods_price')}}">
                </div>

                <div class="form-group">
                    <label for="description">描述</label>
                    <textarea class="form-control" name="description">
{{old('description')}}
                    </textarea>

                </div>


                <input type="hidden" name="goods_img" id="goods_img">

                <div id="uploader-demo" class="wu-example">
                    <div id="fileList" class="uploader-list">
                    </div>
                    <div id="filePicker">选择图片</div>
                </div>

            </div>

            <div class="form-group">
                <label for="tips">简介</label>
                <input type="text" class="form-control" id="tips" placeholder="简介"
                       name="tips" value="{{old('tips')}}">
            </div>

            <div class="form-group">
                <label for="rating">评分</label>
                <input type="text" class="form-control" id="rating" placeholder="评分"
                       name="rating" value="{{old('rating')}}">
            </div>

            <div class="form-group">
                <label for="month_sales">月销量</label>
                <input type="text" class="form-control" id="month_sales" placeholder="月销量"
                       name="month_sales" value="{{old('month_sales')}}">
            </div>

            <div class="form-group">
                <label for="satisfy_rate">满意度评分</label>
                <input type="text" class="form-control" id="satisfy_rate" placeholder="满意度评分"
                       name="satisfy_rate" value="{{old('satisfy_rate')}}">
            </div>
            <div class="form-group">
                <label for="satisfy_count">满意度数量</label>
                <input type="text" class="form-control" id="satisfy_count" placeholder="满意度数量"
                       name="satisfy_count" value="{{old('satisfy_count')}}">
            </div>
            <div class="form-group">
                <label for="satisfy_count">是否上架</label>

                <input type="radio" name="status" checked>是
                <input type="radio" name="status">否

            </div>
            <!-- /.box-body -->

            <div class="box-footer">
                <button type="submit" class="btn btn-primary">提交</button>
            </div>

        </form>
    </div>

@endsection

@section('js')
    <script>
        // 图片上传demo
        jQuery(function () {
            var $ = jQuery,
                $list = $('#fileList'),
                // 优化retina, 在retina下这个值是2
                ratio = window.devicePixelRatio || 1,

                // 缩略图大小
                thumbnailWidth = 100 * ratio,
                thumbnailHeight = 100 * ratio,

                // Web Uploader实例
                uploader;

            // 初始化Web Uploader
            uploader = WebUploader.create({

                // 自动上传。
                auto: true,

                // swf文件路径
                swf: '/webuploader/Uploader.swf',

                formData: {
                    // 这里的token是外部生成的长期有效的，如果把token写死，是可以上传的。
                    _token: '{{csrf_token()}}'
                    // 我想上传时再请求服务器返回token，改怎么做呢？反复尝试而不得。谢谢大家了！
                    //uptoken_url: '127.0.0.1:8080/examples/upload_token.php'
                },

                // 文件接收服务端。
                server: '{{route('menu.upload')}}',


                // 选择文件的按钮。可选。
                // 内部根据当前运行是创建，可能是input元素，也可能是flash.
                pick: '#filePicker',

                // 只允许选择文件，可选。
                accept: {
                    title: 'Images',
                    extensions: 'gif,jpg,jpeg,bmp,png',
                    mimeTypes: 'image/*'
                }
            });

            // 当有文件添加进来的时候
            uploader.on('fileQueued', function (file) {
                var $li = $(
                    '<div id="' + file.id + '" class="file-item thumbnail">' +
                    '<img>' +
                    '<div class="info">' + file.name + '</div>' +
                    '</div>'
                    ),
                    $img = $li.find('img');

                $list.append($li);

                // 创建缩略图
                uploader.makeThumb(file, function (error, src) {
                    if (error) {
                        $img.replaceWith('<span>不能预览</span>');
                        return;
                    }

                    $img.attr('src', src);
                }, thumbnailWidth, thumbnailHeight);
            });

            // 文件上传过程中创建进度条实时显示。
            uploader.on('uploadProgress', function (file, percentage) {
                var $li = $('#' + file.id),
                    $percent = $li.find('.progress span');

                // 避免重复创建
                if (!$percent.length) {
                    $percent = $('<p class="progress"><span></span></p>')
                        .appendTo($li)
                        .find('span');
                }

                $percent.css('width', percentage * 100 + '%');
            });

            // 文件上传成功，给item添加成功class, 用样式标记上传成功。
            uploader.on('uploadSuccess', function (file, data) {
                //console.dir(data);
                $('#' + file.id).addClass('upload-state-done');

                //找到goods_img  设置goods_img的value值
                $("#goods_img").val(data.url);

            });

            // 文件上传失败，现实上传出错。
            uploader.on('uploadError', function (file) {
                var $li = $('#' + file.id),
                    $error = $li.find('div.error');

                // 避免重复创建
                if (!$error.length) {
                    $error = $('<div class="error"></div>').appendTo($li);
                }

                $error.text('上传失败');
            });

            // 完成上传完了，成功或者失败，先删除进度条。
            uploader.on('uploadComplete', function (file) {
                $('#' + file.id).find('.progress').remove();
            });
        });
    </script>
@endsection