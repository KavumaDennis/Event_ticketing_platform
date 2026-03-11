<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TicketPurchase;

class TicketPurchasesUserSeeder extends Seeder
{
    public function run()
    {
        $ticketPurchases = TicketPurchase::all();

        foreach ($ticketPurchases as $purchase) {
            // Assign random user_id between 1 and 5
            $purchase->user_id = rand(1, 5);
            $purchase->save();

            $this->command->info("Purchase ID {$purchase->id} assigned to User ID {$purchase->user_id}");
        }
    }
}
