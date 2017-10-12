<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Roles;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name'        => 'Admin',
                'description' => 'Admin had access to all the accounts',
            ],
            [
                'name'        => 'Supervisor',
                'description' => 'Can view reports of all the sales agents',
            ],
            [
                'name'        => 'Sales Person',
                'description' => 'Who sells the gold',
            ]
        ];

        foreach ($data as $key => $item) {
            $user = Roles::firstOrNew($item);
            $user->save();
        }
    }
}
