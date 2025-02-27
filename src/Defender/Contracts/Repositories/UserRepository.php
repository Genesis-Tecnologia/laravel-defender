<?php

namespace GenesisTecnologia\Defender\Contracts\Repositories;

interface UserRepository extends AbstractRepository
{
    public function attachRole($roleName);

    public function attachPermission($permissionName, array $options = []);
}
