<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $products = \App\Models\Product::factory(50)->create();

        $users = \App\Models\User::factory(20)->create();

        $orders = \App\Models\Order::factory(10)
            ->make()
            ->each(function($order) use ($users){
                $order->customer_id = $users->random()->id;
                $order->save();

                $payment = \App\Models\Payment::factory()->make();
                
                $order->payment()->save($payment);
            });

    }
}
