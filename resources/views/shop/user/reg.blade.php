@extends("layouts.shop.login")
@section('content')
    <h1 class="text-center">商家注册</h1>
    <form method="post" enctype="multipart/form-data">

        {{csrf_field()}}
        <div class="form-group">
            <label for="name">店铺名称：</label>
            <input type="text" name="shop_name" class="form-control" value="{{ old('shop_name') }}">
        </div>
        <div class="form-group">
            <label>店铺分类：</label>
            <select name="shop_cate_id" class="form-control">

                @foreach($cates as $cate)
                    <option value="{{$cate->id}}">{{$cate->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="shop_img">店铺图片：</label>
            <input type="file" name="shop_img">
        </div>
        <div class="form-group">
            <label for="start_send">起送金额：</label>
            <input type="number" name="start_send" class="form-control" value="{{ old('start_send') }}">
        </div>
        <div class="form-group">
            <label for="send_cost">配送费：</label>
            <input type="number" name="send_cost" class="form-control" value="{{ old('send_cost') }}">
        </div>

        <div class="form-group">
            <label for="notice">店铺公告：</label>
            <textarea name="notice" class="form-control">{{ old('notice') }}</textarea>
        </div>
        <div class="form-group">
            <label for="discount">优惠信息：</label>
            <textarea name="discount" class="form-control">{{ old('discount') }}</textarea>
        </div>
        <div class="form-group">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="brand" value="1" @if(old('brand')==1) checked @endif> 品牌连锁店
                </label>


                <label>
                    <input type="checkbox" name="on_time" value="1" @if(old('on_time')==1) checked @endif> 准时送达
                </label>

                <label>
                    <input type="checkbox" name="fengniao" value="1" @if(old('fengniao')==1) checked @endif> 蜂鸟配送
                </label>

                <label>
                    <input type="checkbox" name="bao" value="1" @if(old('bao')==1) checked @endif> 保
                </label>

                <label>
                    <input type="checkbox" name="piao" value="1" @if(old('piao')==1) checked @endif> 票
                </label>

                <label>
                    <input type="checkbox" name="zhun" value="1" @if(old('zhun')==1) checked @endif> 准
                </label>
            </div>
        </div>
        <div class="form-group">
            <label for="name">账号：</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}">
        </div>
        <div class="form-group">
            <label for="email">邮箱：</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}">
        </div>
        <div class="form-group">
            <label for="password">密码：</label>
            <input type="password" name="password" class="form-control">
        </div>
        <div class="form-group">
            <label for="password_confirmation">确认密码：</label>
            <input type="password" name="password_confirmation" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">提交</button>
    </form>
@endsection