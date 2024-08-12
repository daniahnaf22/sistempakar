<?php
  
namespace Database\Seeders;
  
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
  
class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
           'dashboard',

           'logs-list',
           'logs-delete',

           'role-list',
           'role-create',
           'role-edit',
           'role-delete',

           'member-list',
           'member-create',
           'member-edit',
           'member-delete',

           'setting-list',
           'setting-edit',

           'penyakit-list',
           'penyakit-create',
           'penyakit-edit',
           'penyakit-delete',

           'gejala-list',
           'gejala-create',
           'gejala-edit',
           'gejala-delete',

           'rules-list',
           'rules-edit',

           'rulesDs-list',
           'rulesDs-edit',

           'diagnosa',
           'diagnosa-create',

           'diagnosaDs',
           'diagnosa-createDs',

           'riwayat-list',
           'riwayat-show',

           'riwayatDs-list',
           'riwayatDs-show'
        ];
     
        foreach ($permissions as $permission) {
             Permission::create(['name' => $permission]);
        }
    }
}