<?php

use Illuminate\Database\Seeder;
use App\Models\Roles;
use App\Models\Region;
use App\Models\Users;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $supervisorRoleId   = Roles::where('name', 'Supervisor')->value('id');
        $salespersonRoleId  = Roles::where('name', 'Sales Person')->value('id');
        $regionId           = Region::where('name', 'Hyderabad')->value('id');

        $data = [
            [
                'name'      => 'Supervisor',
                'email'     => 'neerajaindian@gmail.com',
                'password'  => Hash::make('test@123'),
                'region_id' => $regionId,
                'role_id'   => $supervisorRoleId,
                'status'    => '1',
            ],
            [
                'name'      => 'Salesperson1',
                'email'     => 'salesperson1@gmail.com',
                'password'  => Hash::make('test@123'),
                'region_id' => $regionId,
                'role_id'   => $salespersonRoleId,
                'status'    => '1',
            ],
            [
                'name'      => 'Salesperson2',
                'email'     => 'salesperson2@gmail.com',
                'password'  => Hash::make('test@123'),
                'region_id' => $regionId,
                'role_id'   => $salespersonRoleId,
                'status'    => '0',
            ],
            [
                'name'      => 'Supervisor2',
                'email'     => 'supervisor2@gmail.com',
                'password'  => Hash::make('test@123'),
                'region_id' => $regionId,
                'role_id'   => $supervisorRoleId,
                'status'    => '1',
            ],
            [
                'name'      => 'Salesperson3',
                'email'     => 'salesperson3@gmail.com',
                'password'  => Hash::make('test@123'),
                'region_id' => $regionId,
                'role_id'   => $salespersonRoleId,
                'status'    => '1',
            ]
        ];

        foreach ($data as $key => $item) {
            $user = Users::firstOrNew($item);
            $user->save();
        }
    }
}
