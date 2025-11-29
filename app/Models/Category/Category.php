<?php

namespace App\Models\Category;

use App\Models\Ad;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public $incrementing = false; // چون id ثابت داریم (غیر auto-increment)

    protected $fillable = ['id', 'parent_id', 'title', 'slug', 'icon', 'title_en'];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function ads()
    {
        return $this->hasMany(Ad::class);
    }
    public function getAllIds()
    {
        $ids = collect([$this->id]);

        foreach ($this->children as $child) {
            $ids = $ids->merge($child->getAllIds());
        }

        return $ids;
    }
}
