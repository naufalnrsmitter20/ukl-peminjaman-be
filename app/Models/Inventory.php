<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Inventory extends Model
{
    protected $fillable = [
        "name",
        "category",
        "location",
        "quantity"
    ];
    public function borrow(): HasMany
    {
        return $this->hasMany(Borrow::class);
    }
}