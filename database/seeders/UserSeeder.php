<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        require public_path('website/backup.php');

        foreach($users as $u) {
            User::insert([
                'id'         => $u['id'],
                'role_id'    => $u['role_id'],
                'photo'      => $u['photo'],
                'signature'  => $u['signature'],
                'name'       => $u['name'],
                'username'   => $u['username'],
                'password'   => $u['password'],
                'email'      => $u['email'],
                'phone'      => $u['phone'],
                'address'    => $u['address'],
                'status'     => $u['status'],
                'created_at' => $u['created_at'],
                'updated_at' => $u['updated_at'],
                'deleted_at' => $u['deleted_at']
            ]);
        }
    }
}
