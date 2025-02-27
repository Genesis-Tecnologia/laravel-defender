<?php

namespace GenesisTecnologia\Defender\Middlewares;

use Closure;
use Illuminate\Support\Arr;

/**
 * Class DefenderHasPermissionMiddleware.
 */
class NeedsPermissionMiddleware extends AbstractDefenderMiddleware
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param callable                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $permissions = null, $any = false)
    {
        if (is_null($permissions)) {
            $permissions = $this->getPermissions($request);
            $anyPermission = $this->getAny($request);
        } else {
            $permissions = explode('|', $permissions); // Laravel 5.1 - Using parameters
            $anyPermission = $any;
        }

        if (is_null($this->user)) {
            return $this->forbiddenResponse();
        }

        if ($this->user->isSuperUser()) {
            return $next($request);
        }

        if (is_array($permissions) and count($permissions) > 0) {
            $canResult = true;

            foreach ($permissions as $permission) {
                $canPermission = $this->user->hasPermission($permission);

                // Check if any permission is enough
                if ($anyPermission and $canPermission) {
                    return $next($request);
                }

                $canResult = $canResult & $canPermission;
            }

            if (! $canResult) {
                return $this->forbiddenResponse();
            }
        }

        return $next($request);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    private function getPermissions($request)
    {
        $routeActions = $this->getActions($request);

        $permissions = Arr::get($routeActions, 'shield', []);

        return is_array($permissions) ? $permissions : (array) $permissions;
    }
}
