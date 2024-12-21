<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];
    public $timestamps = false;

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
