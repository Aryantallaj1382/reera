<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $category = Category::where('parent_id', null)->select(['title' , 'slug' , 'id'])->get();
        return api_response($category);

    }
}
