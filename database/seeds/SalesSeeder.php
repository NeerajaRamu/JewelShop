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
                'sold_date'     => '2017-10-06',
                'customer_name' => 'Neeraja',
                'ornament_name' => 'Necklace',
                'quantity_sold' => '400 gms',
                'gold_cost'     => '28000',
                'total_cost'    => '4354354',
            ],
            [
                'sold_date'     => '2017-11-06',
                'customer_name' => 'Sailaja',
                'ornament_name' => 'Chain',
                'quantity_sold' => '400 gms',
                'gold_cost'     => '25000',
                'total_cost'    => '600',
            ],
            [
                'sold_date'     => '2017-10-06',
                'customer_name' => 'Vishwarupa',
                'ornament_name' => 'Ear rings',
                'quantity_sold' => '400 gms',
                'gold_cost'     => '32000',
                'total_cost'    => '3454345',
            ],
            [
                'sold_date'     => '2017-11-06',
                'customer_name' => 'Laxmi',
                'ornament_name' => 'Ear rings',
                'quantity_sold' => '500 gms',
                'gold_cost'     => '34534',
                'total_cost'    => '56765656',
            ],
            [
                'sold_date'     => '2017-10-06',
                'customer_name' => 'Lalitha',
                'ornament_name' => 'Stones Necklace',
                'quantity_sold' => '900 gms',
                'gold_cost'     => '43534',
                'total_cost'    => '67876876',
            ]
        ];

        foreach ($data as $key => $item) {
            $user = Sales::firstOrNew($item);
            $user['user_id'] = $userId;
            $user->save();
        }
    }
}
