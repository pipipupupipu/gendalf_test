<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'name', 'preview_text', 'price', 'category_id'
    ];
    public $timestamps = false;

    public function toAPIArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'preview_text' => $this->preview_text,
            'price' => $this->price,
            'category' => $this->category->name
        ];

    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
