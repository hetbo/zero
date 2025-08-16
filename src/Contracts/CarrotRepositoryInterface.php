<?php

namespace Hetbo\Zero\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface CarrotRepositoryInterface {

    public function getForUser(UserContract $user): Collection;
    public function createForUser(UserContract $user, array $data): bool;
    public function delete(int $carrotId): bool;

}