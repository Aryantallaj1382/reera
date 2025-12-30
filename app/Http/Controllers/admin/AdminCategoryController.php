<?php

namespace App\Http\Controllers\admin;

use App\Models\Category\Category;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AdminCategoryController extends Controller
{
    public function index()
    {
        // فقط دسته‌های اصلی
        $categories = Category::whereNull('parent_id')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function show(Category $category)
    {
        $category->load('children.children'); // دو سطح برای شروع
        return view('admin.categories.show', compact('category'));
    }

    public function storeChild(Request $request, Category $category)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'title_en' => 'required|string|max:255'
        ]);

        $category->children()->create([
            'title' => $request->title,
            'title_en' => $request->title_en
        ]);

        return back()->with('success', 'زیر دسته اضافه شد');
    }

    public function update(Request $request, Category $category)
    {
        // جلوگیری از ویرایش دسته اصلی
        if ($category->parent_id === null) {
            abort(403);
        }

        $request->validate([
            'title' => 'required',
            'title_en'=> 'required'
        ]);
        $category->update(['title' => $request->title , 'title_en' => $request->title_en]);

        return back();
    }

    public function destroy(Category $category)
    {
        if ($category->parent_id === null) {
            abort(403);
        }

        $category->delete();
        return back();
    }
}
