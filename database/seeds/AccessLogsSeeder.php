<?php

use Illuminate\Database\Seeder;
use App\Models\Users;
use App\Models\AccessLogs;

class AccessLogsSeeder extends Seeder
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
                'date'     => '2017-10-06 13:17:26',
                'time_in' => '2017-10-06 13:17:28',
                'time_out' => '2017-10-06 23:17:26',
                'total_gold_sold' => '400 gms',
                'total_amount'     => '50000',
                'total_hours_spent'    => '10',
            ],
            [
                'date'     => '2017-10-06 13:17:26',
                'time_in' => '2017-10-06 13:17:30',
                'time_out' => '2017-10-06 23:17:26',
                'total_gold_sold' => '200 gms',
                'total_amount'     => '20000',
                'total_hours_spent'    => '10',
            ],
            [
                'date'     => '2017-10-06 13:17:26',
                'time_in' => '2017-10-06 13:17:26',
                'time_out' => '2017-10-06 23:17:26',
                'total_gold_sold' => '100 gms',
                'total_amount'     => '12000',
                'total_hours_spent'    => '10',
            ],
            [
                'date'     => '2017-10-06 13:17:26',
                'time_in' => '2017-10-06 13:17:26',
                'time_out' => '2017-10-06 23:17:26',
                'total_gold_sold' => '300 gms',
                'total_amount'     => '24000',
                'total_hours_spent'    => '10',
            ],
            [
                'date'     => '2017-10-06 13:17:26',
                'time_in' => '2017-10-06 13:17:26',
                'time_out' => '2017-10-06 23:17:26',
                'total_gold_sold' => '200 gms',
                'total_amount'     => '25000',
                'total_hours_spent'    => '8',
            ]
        ];

        foreach ($data as $key => $item) {
            $user = AccessLogs::firstOrNew($item);
            $user['user_id'] = $userId;
            $user->save();
        }
    }
}
