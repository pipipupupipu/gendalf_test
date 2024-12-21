<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'name', 'password', 'group_id'
    ];
    public $timestamps = false;

    public function setPasswordAttribute($value): void
    {
        $this->attributes['password'] = md5($value);
    }

    public function checkPassword($password): bool
    {
        return md5($password) === $this->password;
    }
}
