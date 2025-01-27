<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

use function generate_permission_name;

trait PermissionGenerator
{
    private ?string $module = null;

    private ?string $resource = null;

    protected bool $onlySheetPermissions = false;

    protected array $baseRoleNames = [];

    protected array $basePermissionNames = [];

    private array $roleNames = [];

    private array $permissionNames = [];

    private Collection $permissions;

    private Collection $roles;

    public function __construct()
    {
        $this->roles = collect();
        $this->permissions = collect();
        $this->baseRoleNames = config('plagiarism-checker.roles', ['admin', 'user']);
        $this->basePermissionNames = config('plagiarism-checker.permissions', ['view', 'create', 'update', 'delete']);
    }

    /**
     * Seed the permissions based on module and resource, and return a collection of permissions.
     *
     * @param string $module
     * @param string $resource
     * @param array $newPermissions
     * @param bool $includeBase
     * @return Collection
     */
    protected function seedResourcePermissions(string $module, string $resource): self
    {
        $this->initialize($module, $resource);

        return $this;
    }

    protected function usingRoles(...$roles): self
    {
        $this->roleNames = array_unique(array_merge($this->roleNames, [...$roles]));

        return $this;
    }

    protected function usingSpecificationPermissions(array $permissions)
    {
        $this->permissionNames = array_unique(array_merge($this->basePermissionNames, $permissions));

        return $this;
    }

    protected function usingPermissions(...$permissions): self
    {
        $this->permissionNames = array_unique(array_merge($this->permissionNames, [...$permissions]));

        return $this;
    }

    protected function execute(): self
    {
        if (empty($this->roleNames)) {
            $this->includeAllRole();
        }

        if (empty($this->permissionNames)) {
            $this->includeBasePermissions();
        }

        $this->includeAdminRole();

        $this->createPermissionsIfNotExist();

        if (!$this->onlySheetPermissions) {
            $this->assignPermissionsToRoles();
        }

        return $this;
    }

    private function initialize(string $module, string $resource): void
    {
        $this->module = $module;
        $this->resource = $resource;
    }

    private function includeAllRole(): self
    {
        $this->baseRoleNames[] = Role::all()->pluck('name')->toArray();

        return $this;
    }

    private function includeBasePermissions(): self
    {
        $this->permissionNames = array_unique(array_merge($this->permissionNames, $this->basePermissionNames));

        return $this;
    }

    private function includeAdminRole(): self
    {
        $this->baseRoleNames[] = User::ADMIN_ROLE;

        return $this;
    }

    private function createPermissionsIfNotExist(): self
    {
        foreach ($this->permissionNames as $action) {
            $permissionName = generate_permission_name($this->module, $this->resource, $action);

            if (Permission::where('name', $permissionName)->doesntExist()) {
                $permission = Permission::create([
                    'resource' => $this->resource,
                    'name' => $permissionName
                ]);
                $this->permissions->push($permission);
            }
        }

        return $this;
    }

    private function assignPermissionsToRoles(): self
    {
        $this->roles = Role::whereIn('name', $this->roleNames)->get();

        foreach ($this->roles as $role) {
            $role->givePermissionTo($this->permissions->pluck('name')->toArray());
        }

        return $this;
    }
}
