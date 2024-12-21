<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class PartnerRight extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'user_id'
    ];
    public $timestamps = false;


    public static function boot()
    {
        parent::boot();

        self::creating(function ($partnerRight) {
            $partner = User::query()->find($partnerRight->user_id);
            if (!($partner->group_id == 2)) {
                throw new \Exception('Пользователь ' . $partnerRight->user_id . ' не партнер', 400);
            }
        });

    }
}
