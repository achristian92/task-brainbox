<?php

use App\Repositories\Counters\Counter;
use App\Repositories\Customers\Customer;
use App\Repositories\Supervisors\Supervisor;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name'           => 'Admin',
            'email'          => 'aruiz@tavera.pe',
            'password'       => bcrypt('123456'),
            'created_at'     => \Carbon\Carbon::now()->toDateTimeString(),
            'updated_at'     => \Carbon\Carbon::now()->toDateTimeString(),
            'nro_document'   => '12395147',
            'can_check_all_customers' => 1,
            'can_be_check_all' => 1,
        ]);

        // Create the initial admin user
        $roles = ['admin','supervisor','colaborador'];
        foreach ($roles as $rol) {
            DB::table('roles')->insert([
                'name'        => $rol,
                'guard_name'  => 'web',
            ]);
        }


        DB::table('model_has_roles')->insert([
            'role_id'    => '1',
            'model_type' => 'App\User',
            'model_id'   => '1',
        ]);

        DB::table('model_has_roles')->insert([
            'role_id'    => '2',
            'model_type' => 'App\User',
            'model_id'   => '1',
        ]);

        DB::table('model_has_roles')->insert([
            'role_id'    => '3',
            'model_type' => 'App\User',
            'model_id'   => '1',
        ]);


        $customer_ids = Customer::all()->pluck('id')->toArray();
        User::first()->customers()->sync([1]+array_slice($customer_ids, 0, random_int(1,count($customer_ids))));
        factory(User::class, 4)
                ->create()
                ->each(function (User $user) use ($customer_ids) {
                    $role = Role::all()->pluck('name')->random();
                    $user->assignRole($role);

                    shuffle($customer_ids);
                    $user->customers()->sync([1] + array_slice($customer_ids, 0, random_int(1,count($customer_ids))));
                });

    }
}
