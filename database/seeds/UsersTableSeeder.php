<?php

use App\Components\Users\Users;
use App\Components\Permissions\Permission;
use App\Components\Roles\Repositories\RoleRepository;
use App\Components\Roles\Role;
use Illuminate\Database\Seeder;

class EmployeesTableSeeder extends Seeder
{
    public function run()
    {
        $createProductPerm = factory(Permission::class)->create([
            'name' => 'create-expenses',
            'display_name' => 'Create expenses'
        ]);

        $viewProductPerm = factory(Permission::class)->create([
            'name' => 'view-expenses',
            'display_name' => 'View expenses'
        ]);

        $updateProductPerm = factory(Permission::class)->create([
            'name' => 'update-expenses',
            'display_name' => 'Update expenses'
        ]);

        $deleteProductPerm = factory(Permission::class)->create([
            'name' => 'delete-expenses',
            'display_name' => 'Delete expenses'
        ]);

        $updateOrderPerm = factory(Permission::class)->create([
            'name' => 'update-order',
            'display_name' => 'Update order'
        ]);

        $employee = factory(Users::class)->create([
            'email' => 'user@yopmail.com'
        ]);

        // $super = factory(Role::class)->create([
        //     'name' => 'user',
        //     'display_name' => 'User'
        // ]);

        $roleSuperRepo = new RoleRepository($super);
        $roleSuperRepo->attachToPermission($createProductPerm);
        $roleSuperRepo->attachToPermission($viewProductPerm);
        $roleSuperRepo->attachToPermission($updateProductPerm);
        $roleSuperRepo->attachToPermission($deleteProductPerm);
        $roleSuperRepo->attachToPermission($updateOrderPerm);

        $employee->roles()->save($super);

        $employee = factory(Users::class)->create([
            'email' => 'admin@yopmail.com'
        ]);

        // $admin = factory(Role::class)->create([
        //     'name' => 'administrator',
        //     'display_name' => 'Administrator'
        // ]);

        $roleAdminRepo = new RoleRepository($admin);
        $roleAdminRepo->attachToPermission($createProductPerm);
        $roleAdminRepo->attachToPermission($viewProductPerm);
        $roleAdminRepo->attachToPermission($updateProductPerm);
        $roleAdminRepo->attachToPermission($deleteProductPerm);
        $roleAdminRepo->attachToPermission($updateOrderPerm);

        $employee->roles()->save($admin);

    }
}
