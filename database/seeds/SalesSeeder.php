<?php

use Illuminate\Database\Seeder;
use App\Models\Sales;
use App\Models\Users;

class SalesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userId   = Users::where('name', 'Salesperson1')->value('id');

        $data = [
            [
                'sold_date'     => '2017-10-06 13:17:26',
                'customer_name' => 'Neeraja',
                'ornament_name' => 'Necklace',
                'quantity_sold' => '400 gms',
                'gold_cost'     => '1',
                'total_cost'    => '1',
            ],
            [
                'sold_date'     => '2017-10-06 13:17:26',
                'customer_name' => 'Sailaja',
                'ornament_name' => 'Chain',
                'quantity_sold' => '400 gms',
                'gold_cost'     => '1',
                'total_cost'    => '1',
            ],
            [
                'sold_date'     => '2017-10-06 13:17:26',
                'customer_name' => 'Vishwarupa',
                'ornament_name' => 'Ear rings',
                'quantity_sold' => '400 gms',
                'gold_cost'     => '1',
                'total_cost'    => '1',
            ],
            [
                'sold_date'     => '2017-10-06 13:17:26',
                'customer_name' => 'Laxmi',
                'ornament_name' => 'Ear rings',
                'quantity_sold' => '500 gms',
                'gold_cost'     => '1',
                'total_cost'    => '1',
            ],
            [
                'sold_date'     => '2017-10-06 13:17:26',
                'customer_name' => 'Lalitha',
                'ornament_name' => 'Stones Necklace',
                'quantity_sold' => '900 gms',
                'gold_cost'     => '1',
                'total_cost'    => '1',
            ]
        ];

        foreach ($data as $key => $item) {
            $user = Sales::firstOrNew($item);
            $user['user_id'] = $userId;
            $user->save();
        }
    }
}
