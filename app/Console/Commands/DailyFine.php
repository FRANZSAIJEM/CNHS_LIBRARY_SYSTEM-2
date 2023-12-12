<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AcceptedRequest;
use App\Models\DefaultFine;
use App\Models\User;


class DailyFine extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:daily-fine';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $currentDate = now();

        $requests = AcceptedRequest::where('date_return', '<=', $currentDate)->get();
        $usersToUnsuspend = User::where('suspend_end_date', '<=', $currentDate)->get();




        // Retrieve the amount from any DefaultFine record (assuming there's only one)
        $defaultFine = DefaultFine::value('amount');
        $dailyFine = DefaultFine::value('set_daily_fines');

        if ($defaultFine !== null) {
            // Subtract set_daily_fines from defaultFine once
            $defaultFine -= $dailyFine;
        }



        foreach ($usersToUnsuspend as $user) {
            // Update is_suspended to false
            $user->is_suspended = false;

            // Set suspend_start_date and suspend_end_date to null
            $user->suspend_start_date = null;
            $user->suspend_end_date = null;

            try {
                    // Save the model
                    $user->save();
                } catch (\Exception $e) {
                    // Log or dump the exception for debugging
                    dump("Exception occurred for User ID: {$user->id} - {$e->getMessage()}");
                }
            }


        foreach ($requests as $request) {
            // Check if the book has been returned
            if (!$request->book_returned) {
                // Add daily fines only if the book is not returned
                $request->daily_fines += $dailyFine;
                $request->late_return = 'Late';
            }

            if ($defaultFine !== null) {
                $request->total_fines = $request->daily_fines + $defaultFine;
                $request->save();
            }
        }

        $this->info('Daily fines for expired requests calculated and saved successfully.');
    }

}
