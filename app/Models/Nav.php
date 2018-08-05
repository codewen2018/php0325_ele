<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nav extends Model
{
    //
    public $fillable=['name','url','parent_id','sort'];



    public static function navs(){
        $navs=self::where('parent_id',0)->get();

        foreach ($navs as $k=>$nav){

            //1.把没有儿子的当前分类删除掉
            if(self::where("parent_id",$nav->id)->first()===null){
//删除当前分类
                unset($navs[$k]);
                //跳出当前本次循环
                continue;
            }

            //2.判断当前用户有没有儿子分类的权限
            $childs=self::where("parent_id",$nav->id)->get();
            //2.1 再次循环儿子 看有没有权限
            $ok=0;
            foreach ($childs as $v){
                //2.3判断儿子有没有权限
                if (\Illuminate\Support\Facades\Auth::guard('admin')->user()->can($v->url)){
                    //                    //如果有权限
                    $ok=1;
                }
                //2.4 如果$ok===0 就说明没有儿子有权限 就把当前分类的删除
                if ($ok===0 && \Illuminate\Support\Facades\Auth::guard('admin')->user()->id!=1){
                    unset($navs[$k]);
                }
            }
        }

        return $navs;
    }
}
