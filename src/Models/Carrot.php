<?php

namespace Hetbo\Zero\Models;

use Hetbo\Zero\Database\Factories\CarrotFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Carrot extends Model {

    use HasFactory;
    protected $fillable = ['name', 'length', 'user_id'];

    public function user(): BelongsTo
    {
        // Dynamically use the configured user model
        return $this->belongsTo(config('zero.user_model'));
    }

    protected static function newFactory(): CarrotFactory
    {
        return CarrotFactory::new();
    }

}