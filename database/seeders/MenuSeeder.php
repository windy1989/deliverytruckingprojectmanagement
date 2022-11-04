<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        require public_path('website/backup.php');

        foreach($menus as $m) {
            Menu::insert([
                'id'         => $m['id'],
                'name'       => $m['name'],
                'url'        => $m['url'],
                'icon'       => $m['icon'],
                'parent_id'  => $m['parent_id'],
                'order'      => $m['order'],
                'created_at' => $m['created_at'],
                'updated_at' => $m['updated_at'],
                'deleted_at' => $m['deleted_at']
            ]);
        }
    }
}
