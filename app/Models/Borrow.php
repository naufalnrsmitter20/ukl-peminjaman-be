<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Borrow extends Model
{
    protected $fillable = [
        "user_id",
        "inventory_id",
        "borrow_date",
        "return_date",
        "actual_return_date",
    ];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, "user_id");
    }
    public function inventory(): BelongsTo
    {
        return $this->belongsTo(Inventory::class, "inventory_id");
    }
    public function returnh(): HasMany
    {
        return $this->hasMany(Returnh::class);
    }
}