<?php

namespace Hetbo\Zero\Services;

use Hetbo\Zero\Contracts\CarrotRepositoryInterface;
use Hetbo\Zero\Contracts\UserContract;
use Illuminate\Database\Eloquent\Collection;

class CarrotService
{
    public function __construct(protected CarrotRepositoryInterface $carrotRepository) {}

    public function getUserCarrots(UserContract $user): Collection
    {
        return $this->carrotRepository->getForUser($user);
    }

    public function addCarrotForUser(UserContract $user, array $data): bool
    {
        return $this->carrotRepository->createForUser($user, $data);
    }

    public function removeCarrot(int $carrotId): bool
    {
        return $this->carrotRepository->delete($carrotId);
    }
}