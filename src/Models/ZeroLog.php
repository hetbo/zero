<?php

namespace Hetbo\Zero\Models;

use Illuminate\Database\Eloquent\Model;

class ZeroLog extends Model
{
    protected $table = 'zero_logs';

    protected $fillable = [
        'action',
        'data'
    ];

    protected $casts = [
        'data' => 'array'
    ];
}