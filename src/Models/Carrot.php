<?php

namespace Hetbo\Zero\Models;

use Hetbo\Zero\Database\Factories\CarrotFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Carrot extends Model
{
    protected $fillable = ['name', 'length'];
}