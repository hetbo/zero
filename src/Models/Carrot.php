<?php

namespace Hetbo\Zero\Models;

use Hetbo\Zero\Database\Factories\CarrotFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Carrot extends Model
{

    use HasFactory;

    protected $fillable = [
        'name',
        'length',
    ];

    protected $casts = [
        'length' => 'integer',
    ];

    /**
     * Get all models that have this carrot.
     */
    public function carrotables(): MorphToMany
    {
        return $this->morphedByMany('*', 'carrotable')->withPivot('role');
    }
    protected static function newFactory()
    {
        return CarrotFactory::new();
    }
}