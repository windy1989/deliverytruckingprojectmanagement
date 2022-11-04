<?php

namespace Database\Seeders;

use App\Models\FileManager;
use Illuminate\Database\Seeder;

class FileManagerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        require public_path('website/backup.php');

        foreach($file_managers as $fm) {
            FileManager::insert([
                'id'         => $fm['id'],
                'user_id'    => $fm['user_id'],
                'name'       => $fm['name'],
                'extension'  => $fm['extension'],
                'size'       => $fm['size'],
                'file'       => $fm['file'],
                'created_at' => $fm['created_at'],
                'updated_at' => $fm['updated_at']
            ]);
        }
    }
}
