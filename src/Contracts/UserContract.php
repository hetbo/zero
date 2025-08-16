<?php

namespace Hetbo\Zero\Contracts;

use Illuminate\Database\Eloquent\Relations\HasMany;

interface UserContract {

    public function carrots(): HasMany;
    
}