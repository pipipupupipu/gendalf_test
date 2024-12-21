<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class BaseModel extends Model
{
    public function getColumns()
    {
        return Schema::getColumnListing($this->getTable());
    }

    public function toAPIArray()
    {
        return $this->attributesToArray();
    }
}
