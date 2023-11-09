<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AcceptedRequest;
use App\Models\DefaultFine;

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

        // Retrieve the amount from any DefaultFine record (assuming there's only one)
        $defaultFine = DefaultFine::value('amount');
        $dailyFine = DefaultFine::value('set_daily_fines');

        if ($defaultFine !== null) {
            // Subtract set_daily_fines from defaultFine once
            $defaultFine -= $dailyFine;
        }

        foreach ($requests as $request) {
            $request->daily_fines += $dailyFine;

            if ($defaultFine !== null) {
                $request->total_fines = $request->daily_fines + $defaultFine;
                $request->save();
            }
        }

        $this->info('Daily fines for expired requests calculated and saved successfully.');
    }

}
