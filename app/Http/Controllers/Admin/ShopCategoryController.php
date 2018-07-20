<?php

namespace App\Http\Controllers\Admin;

use App\Models\ShopCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShopCategoryController extends Controller
{
    public function index(){

        $cates=ShopCategory::all();

        return view("admin.shop_category.index",compact('cates'));
    }
}
