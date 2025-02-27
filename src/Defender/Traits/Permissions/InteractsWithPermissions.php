<?php

namespace GenesisTecnologia\Defender\Traits\Permissions;

use Illuminate\Support\Arr;

/**
 * Trait InteractsWithPermissions.
 */
trait InteractsWithPermissions
{
    /**
     * Attach the given permission.
     *
     * @param array|\GenesisTecnologia\Defender\Permission $permission
     * @param array                               $options
     */
    public function attachPermission($permission, array $options = [])
    {
        if (! is_array($permission)) {
            if ($this->existPermission($permission->name)) {
                return;
            }
        }

        $this->permissions()->attach($permission, [
            'value'   => Arr::get($options, 'value', true),
            'expires' => Arr::get($options, 'expires', null),
        ]);
    }

    /**
     * Get the a permission using the permission name.
     *
     * @param string $permissionName
     *
     * @return bool
     */
    public function existPermission($permissionName)
    {
        $permission = $this->permissions->first(function ($key, $value) use ($permissionName) {
            return ((isset($key->name)) ? $key->name : $value->name) == $permissionName;
        });

        if (! empty($permission)) {
            $active = (is_null($permission->pivot->expires) or $permission->pivot->expires->isFuture());

            if ($active) {
                return (bool) $permission->pivot->value;
            }
        }

        return false;
    }

    /**
     * Alias to the detachPermission method.
     *
     * @param \GenesisTecnologia\Defender\Permission $permission
     *
     * @return int
     */
    public function revokePermission($permission)
    {
        return $this->detachPermission($permission);
    }

    /**
     * Detach the given permission from the model.
     *
     * @param \GenesisTecnologia\Defender\Permission $permission
     *
     * @return int
     */
    public function detachPermission($permission)
    {
        return $this->permissions()->detach($permission);
    }

    /**
     * Sync the given permissions.
     *
     * @param array $permissions
     *
     * @return array
     */
    public function syncPermissions(array $permissions)
    {
        return $this->permissions()->sync($permissions);
    }

    /**
     * Revoke all user permissions.
     *
     * @return int
     */
    public function revokePermissions()
    {
        return $this->permissions()->detach();
    }

    /**
     * Revoke expired user permissions.
     *
     * @return int|null
     */
    public function revokeExpiredPermissions()
    {
        $expiredPermissions = $this->permissions()->wherePivot('expires', '<', Carbon::now())->get();

        if ($expiredPermissions->count() > 0) {
            return $this->permissions()->detach($expiredPermissions->modelKeys());
        }
    }

    /**
     * Extend an existing temporary permission.
     *
     * @param string $permission
     * @param array  $options
     *
     * @return bool|null
     */
    public function extendPermission($permission, array $options)
    {
        foreach ($this->permissions as $_permission) {
            if ($_permission->name === $permission) {
                return $this->permissions()->updateExistingPivot(
                    $_permission->id,
                    Arr::only($options, ['value', 'expires'])
                );
            }
        }
    }
}
