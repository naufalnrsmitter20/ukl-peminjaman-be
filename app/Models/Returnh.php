<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Returnh extends Model
{
    protected $fillable = [
        "return_date",
        "borrow_id",
        "actual_return_date"
    ];

    public function borrow(): BelongsTo
    {
        return $this->belongsTo(Borrow::class, "borrow_id");
    }
}