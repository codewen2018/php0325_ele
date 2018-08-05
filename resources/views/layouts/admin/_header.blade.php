<nav class="navbar navbar-default">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">源码点餐平台</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li ><a href="/">首页 <span class="sr-only">(current)</span></a></li>

               {{-- @php
                $navs=\App\Models\Nav::where('parent_id',0)->get();

                foreach ($navs as $k=>$nav){

                   //1.把没有儿子的当前分类删除掉
                   if(\App\Models\Nav::where("parent_id",$nav->id)->first()===null){
//删除当前分类
                   unset($navs[$k]);
                   //跳出当前本次循环
                   continue;
}

                //2.判断当前用户有没有儿子分类的权限
                $childs=\App\Models\Nav::where("parent_id",$nav->id)->get();
                //2.1 再次循环儿子 看有没有权限
                $ok=0;
                foreach ($childs as $v){
                //2.3判断儿子有没有权限
                if (\Illuminate\Support\Facades\Auth::guard('admin')->user()->can($v->url)){
                //如果有权限
                $ok=1;
                }
                //2.4 如果$ok===0 就说明没有儿子有权限 就把当前分类的删除
                if ($ok===0){
                unset($navs[$k]);
                }
                }
                }


                @endphp--}}
                @foreach(\App\Models\Nav::navs() as $k1=>$v1)
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{$v1->name}} <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        @foreach(\App\Models\Nav::where("parent_id",$v1->id)->get() as $k2=>$v2)
                            @if(\Illuminate\Support\Facades\Auth::guard('admin')->user()->can($v2->url) or \Illuminate\Support\Facades\Auth::guard('admin')->user()->id===1)
                        <li><a href="{{route($v2->url)}}">{{$v2->name}}</a></li>
                            @endif
                       @endforeach
                    </ul>
                </li>
               @endforeach

            </ul>

            <ul class="nav navbar-nav navbar-right">


                @auth("admin")

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{\Illuminate\Support\Facades\Auth::guard("admin")->user()->name}} <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="{{route('admin.changePassword')}}">修改密码</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="{{route('admin.logout')}}">注销</a></li>
                        </ul>
                    </li>
                @endauth

                @guest("admin")
                        <li><a href="/">登录</a></li>
                @endguest





            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>