<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category\Category;

class AdminCategory extends Controller
{
    public function category()
    {
        $categories = Category::with('children')->whereNull('parent_id')->get();

        return view('admin.category', compact('categories'));    }
}
