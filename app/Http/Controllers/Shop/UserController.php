<?php

namespace App\Http\Controllers\Shop;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * 注册
     */
    public function reg()
    {

        return view("shop.user.reg");
    }

    public function index()
    {

        return view("shop.user.index");
    }
}
