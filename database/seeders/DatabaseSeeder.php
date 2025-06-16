<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Throwable;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Roles seeder
        
        $roles = [
            [
                'name' => 'admin'
            ],
            [
                'name' => 'manager'
            ],
            [
                'name' => 'employee'
            ]
                
        ];
        try {
            collect($roles)->each(function($role){
                Role::updateOrCreate([
                    'name' => $role['name']
                ], $role);
            });
        } catch (Throwable) {
            
        };
        
        // Permissions seeder
        
        $permissions = [
            [
                'name' => 'create_department'    
            ],
            [
                'name' => 'edit_department'    
            ],
            [
                'name' => 'delete_department'    
            ],
            [
                'name' => 'view_department'    
            ],
            [
                'name' => 'create_employee'    
            ],
            [
                'name' => 'edit_employee'    
            ],
            [
                'name' => 'delete_employee'    
            ],
            [
                'name' => 'view_employee'    
            ],
            [
                'name' => 'assign_role'
            ],
            [
                'name' => 'view_profile'
            ]
        ];
        
        try {
            collect($permissions)->each(function($permission){
                Permission::updateOrCreate([
                    'name' => $permission['name']
                ], $permission);
            });

            $adminRole = Role::where('name', 'admin')->first();
            $allPermissions = Permission::all()->pluck('name')->toArray();
            $adminRole->syncPermissions($allPermissions);

            $managerRole = Role::where('name', 'manager')->first();
            $managerPermissions = Permission::whereIn('name', ['view_employee', 'edit_employee'])->get();
            $managerRole->syncPermissions($managerPermissions);
            
            $employeeRole = Role::where('name', 'employee')->first();
            $employeePermissions = Permission::where('name', 'view_profile')->get();
            $employeeRole->syncPermissions($employeePermissions);

        } catch (\Throwable $th) {
            //throw $th;
        }

        // User seeder

        User::factory()->create([
            'name' => 'User',
            'email' => 'user@example.com',
            'password' => Hash::make(1234)
        ])->assignRole('admin');

        // Department seeder

        Department::create([
            'name' => 'Information Technology',
            'code' => 'IT-01',
            'description' => 'Lorem ipsum dolor sit amet'
        ]);
    }
}
