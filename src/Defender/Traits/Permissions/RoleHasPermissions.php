<?php

namespace GenesisTecnologia\Defender\Traits\Permissions;

use GenesisTecnologia\Defender\Pivots\PermissionRolePivot;
use Illuminate\Database\Eloquent\Model;

/**
 * Trait RoleHasPermissions.
 */
trait RoleHasPermissions
{
    use InteractsWithPermissions;

    /**
     * Many-to-many permission-user relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        $permissionModel = config('defender.permission_model');
        $permissionRoleTable = config('defender.permission_role_table');
        $roleKey = config('defender.role_key');
        $permissionKey = config('defender.permission_key');

        return $this->belongsToMany($permissionModel, $permissionRoleTable, $roleKey, $permissionKey)->withPivot('value', 'expires');
    }

    /**
     * @param Model  $parent
     * @param array  $attributes
     * @param string $table
     * @param bool   $exists
     * @param  string|null  $using
     *
     * @return PermissionRolePivot|\Illuminate\Database\Eloquent\Relations\Pivot
     */
    public function newPivot(Model $parent, array $attributes, $table, $exists, $using = null)
    {
        $permissionModel = app()['config']->get('defender.permission_model');

        if ($parent instanceof $permissionModel) {
            return PermissionRolePivot::fromAttributes($parent, $attributes, $table, $exists);
        }

        return parent::newPivot($parent, $attributes, $table, $exists, $using);
    }
}
