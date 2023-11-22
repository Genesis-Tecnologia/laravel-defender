<?php

namespace GenesisTecnologia\Defender\Contracts;

/**
 * Interface Defender.
 */
interface Defender
{
    /**
     * Get the current authenticated user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function getUser();

    /**
     * Check if the authenticated user has the given permission.
     *
     * @param string $permission
     * @param bool   $force
     *
     * @return bool
     */
    public function hasPermission($permission, $force = false);

    /**
     * Check if the authenticated user has the given permission.
     *
     * @param string $permission
     * @param bool   $force
     *
     * @return bool
     */
    public function canDo($permission, $force = false);

    /**
     * Check if the authenticated user has the given permission
     * using only the roles.
     *
     * @param string $permission
     * @param bool   $force
     *
     * @return bool
     */
    public function roleHasPermission($permission, $force = false);

    /**
     * Return if the authenticated user has the given role.
     *
     * @param string $roleName
     *
     * @return bool
     */
    public function hasRole($roleName);

    /**
     * Return if the authenticated user has the given role.
     *
     * @param string $roleName
     *
     * @return bool
     */
    public function is($roleName);

    /**
     * Check if a role with the given name exists.
     *
     * @param string $roleName
     *
     * @return bool
     */
    public function roleExists($roleName);

    /**
     * Check if a permission with the given name exists.
     *
     * @param string $permissionName
     *
     * @return bool
     */
    public function permissionExists($permissionName);

    /**
     * Get the role with the given name.
     *
     * @param string $roleName
     *
     * @return \GenesisTecnologia\Defender\Role|null
     */
    public function findRole($roleName);

    /**
     * * Find a role by its id.
     *
     * @param int $roleId
     *
     * @return \GenesisTecnologia\Defender\Role|null
     */
    public function findRoleById($roleId);

    /**
     * Get the permission with the given name.
     *
     * @param string $permissionName
     *
     * @return \GenesisTecnologia\Defender\Permission|null
     */
    public function findPermission($permissionName);

    /**
     * Find a permission by its id.
     *
     * @param int $permissionId
     *
     * @return \GenesisTecnologia\Defender\Permission|null
     */
    public function findPermissionById($permissionId);

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function permissionsList();

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function rolesList();

    /**
     * Create a new role.
     * Uses a repository to actually create the role.
     *
     * @param string $roleName
     *
     * @return \GenesisTecnologia\Defender\Role
     */
    public function createRole($roleName);

    /**
     * @param string $permissionName
     * @param string $readableName
     *
     * @return \GenesisTecnologia\Defender\Permission
     */
    public function createPermission($permissionName, $readableName = null);

    /**
     * @return \GenesisTecnologia\Defender\Javascript
     */
    public function javascript();
}
