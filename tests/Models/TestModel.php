<?php

namespace Hetbo\Zero\Tests\Models;

use Hetbo\Zero\Database\Factories\TestModelFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Hetbo\Zero\Traits\HasCarrots;

class TestModel extends Model
{
    use HasCarrots, HasFactory;

    protected $fillable = ['name', 'email'];

    protected $table = 'test_models';

    protected static function newFactory()
    {
        return TestModelFactory::new();
    }

}