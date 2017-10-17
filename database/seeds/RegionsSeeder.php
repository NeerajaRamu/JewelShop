<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Region;

class RegionsSeeder extends Seeder
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
                'name'        => 'Hyderabad',
                'description' => 'This regions covers all areas in Hyderabad',
            ],
            [
                'name'        => 'Vijayawada',
                'description' => 'This regions covers all areas in Vijayawada',
            ],
            [
                'name'        => 'Kurnool',
                'description' => 'This regions covers all areas in Kurnool',
            ]
        ];

//        $deployment = Deployment::firstOrFail();
//        $credential = Credential::where('deployment_id', '=', $deployment->id)->firstOrFail();
//        $role       = Role::where('name', 'Kepler')->firstOrFail();

        foreach ($data as $key => $item) {
            $user = Region::firstOrNew($item);
//            $user->deployment()->associate($deployment);
//            $user->credential()->associate($credential);
            $user->save();
//            $user->roles()->sync([$role->id]);
        }
    }
}




