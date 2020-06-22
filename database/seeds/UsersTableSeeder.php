<?php

use Illuminate\Database\Seeder;
use App\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        
        Permission::query()->delete();
        Role::query()->delete();
        User::query()->delete();
        \DB::table('model_has_roles')->delete();
        
        User::insert([
            [
                'name' => 'Admin', 
                'email' => 'admin@app.com', 
                'email_verified_at' => '2020-01-28 09:36:03', 
                'password' => bcrypt('adminadmin'), 
                'remember_token' => Str::random(60),
            ],
            [
                'name' => 'Staff', 
                'email' => 'staff@app.com', 
                'email_verified_at' => '2020-01-28 09:36:03', 
                'password' => bcrypt('staffstaff'), 
                'remember_token' => Str::random(60),
            ],
            [
                'name' => 'Febriansyah', 
                'email' => 'febri@app.com', 
                'email_verified_at' => '2020-01-28 09:36:03', 
                'password' => bcrypt('febrifebri'), 
                'remember_token' => Str::random(60),
            ],  
        ]);

        Permission::insert([
            [
                'name' => 'Admin',
                'guard_name' => 'web'
            ],
            [
                'name' => 'Staff',
                'guard_name' => 'web'
            ],
            [
                'name' => 'Member',
                'guard_name' => 'web'
            ]
        ]);

        Role::create(['name' => 'Admin'])->givePermissionTo(Permission::all());  

        Role::create(['name' => 'Staff'])->givePermissionTo([
            'Staff',
        ]);  

        Role::create(['name' => 'Member'])->givePermissionTo([
            'Member',
        ]);  

        \DB::table('model_has_roles')->insert([
            [
                'role_id'=> 1,
                'model_type'=> 'App\\User',
                'model_id'=>1
            ],
            [
                'role_id'=> 2,
                'model_type'=> 'App\\User',
                'model_id'=>2
            ],
            [
                'role_id'=> 3,
                'model_type'=> 'App\\User',
                'model_id'=>3
            ],
        ]);

    }
}
