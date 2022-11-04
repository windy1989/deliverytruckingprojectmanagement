<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\RoleAccess;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        require public_path('website/backup.php');

        foreach($roles as $r) {
            Role::insert([
                'id'         => $r['id'],
                'name'       => $r['name'],
                'created_at' => $r['created_at'],
                'updated_at' => $r['updated_at'],
                'deleted_at' => $r['deleted_at']
            ]);
        }

        foreach($role_accesses as $ra) {
            RoleAccess::insert([
                'id'         => $ra['id'],
                'role_id'    => $ra['role_id'],
                'menu_id'    => $ra['menu_id'],
                'created_at' => $ra['created_at'],
                'updated_at' => $ra['updated_at']
            ]);
        }
    }
}
