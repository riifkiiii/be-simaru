<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    private $roles = [
        ["admin", "Admin"],
        ["owner", "Owner"],
        ["dev", "Developer"],
        ["user", "User"],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->roles as $role) {
            \App\Models\Role::create([
                "guid" => $role[0],
                "name" => $role[1],
            ]);
        }
    }
}
