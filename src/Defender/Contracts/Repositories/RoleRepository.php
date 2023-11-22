<?php

namespace GenesisTecnologia\Defender\Contracts\Repositories;

/**
 * Interface RoleRepository.
 */
interface RoleRepository extends AbstractRepository
{
    /**
     * Create a new role with the given name.
     *
     * @param string $roleName
     *
     * @throws \Exception
     *
     * @return \GenesisTecnologia\Defender\Role
     */
    public function create($roleName);
}
