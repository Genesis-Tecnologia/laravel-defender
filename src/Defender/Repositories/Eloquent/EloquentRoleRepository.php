<?php

namespace GenesisTecnologia\Defender\Repositories\Eloquent;

use GenesisTecnologia\Defender\Contracts\Repositories\RoleRepository;
use GenesisTecnologia\Defender\Contracts\Role;
use GenesisTecnologia\Defender\Exceptions\RoleExistsException;
use Illuminate\Contracts\Foundation\Application;

/**
 * Class EloquentRoleRepository.
 */
class EloquentRoleRepository extends AbstractEloquentRepository implements RoleRepository
{
    /**
     * @param Application $app
     * @param Role        $model
     */
    public function __construct(Application $app, Role $model)
    {
        parent::__construct($app, $model);
    }

    /**
     * Create a new role with the given name.
     *
     * @param $roleName
     *
     * @throws \Exception
     *
     * @return Role
     */
    public function create($roleName)
    {
        if (! is_null($this->findByName($roleName))) {
            // TODO: add translation support
            throw new RoleExistsException('A role with the given name already exists');
        }

        return $role = $this->model->create(['name' => $roleName]);
    }
}
