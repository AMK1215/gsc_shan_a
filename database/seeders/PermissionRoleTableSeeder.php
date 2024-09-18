<?php

namespace Database\Seeders;

use App\Models\Admin\Permission;
use App\Models\Admin\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionRoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin gets specific permissions
        $admin_permissions = Permission::whereIn('title', [
            'admin_access',
            'master_index',
            'master_create',
            'master_edit',
            'master_delete',
            'transfer_log',
            'make_transfer',
        ])->pluck('id');

        Role::findOrFail(1)->permissions()->sync($admin_permissions);

        // Master gets specific permissions
        $master_permissions = Permission::whereIn('title', [
            'agent_index',
            'agent_create',
            'agent_edit',
            'agent_delete',
            'transfer_log',
            'make_transfer',
        ])->pluck('id');

        Role::findOrFail(2)->permissions()->sync($master_permissions);

        // Agent gets specific permissions
        $agent_permissions = Permission::whereIn('title', [
            'player_index',
            'player_create',
            'player_edit',
            'player_delete',
            'transfer_log',
            'make_transfer',
            'deposit',
            'withdraw',
            'bank',
            'promotion',
            'contact',
        ])->pluck('id');

        Role::findOrFail(3)->permissions()->sync($agent_permissions);
    }
}
