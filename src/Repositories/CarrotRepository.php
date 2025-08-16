<?php

namespace Hetbo\Zero\Repositories;

use Hetbo\Zero\Contracts\CarrotRepositoryInterface;
use Hetbo\Zero\Contracts\UserContract;
use Hetbo\Zero\Models\Carrot;
use Illuminate\Database\Eloquent\Collection;

class CarrotRepository implements CarrotRepositoryInterface {

    public function getForUser(UserContract $user): Collection
    {
        return $user->carrots()->orderBy('created_at', 'desc')->get();
    }

    public function createForUser(UserContract $user, array $data): bool
    {
        $user->carrots()->create($data);
        return true;
    }

    public function delete(int $carrotId): bool
    {
        return Carrot::destroy($carrotId);
    }

}