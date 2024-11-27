<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;

class PrintTimeEvery20Seconds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'print:time-every-20-seconds';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Broadcast Open and Close at specific seconds in an infinite loop.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Command started. Press Ctrl + C to stop.');

        // while (true) {
        //     $currentTime = Carbon::now(); // Get current time
        //     $currentSecond = (int)$currentTime->format('s'); // Get current second

        //     if ($currentSecond >= 0 && $currentSecond <= 9) {
        //         $this->info("Open at: " . $currentTime->toDateTimeString());
        //     } elseif ($currentSecond >= 10 && $currentSecond <= 19) {
        //         $this->info("Close at: " . $currentTime->toDateTimeString());
        //     } elseif ($currentSecond >= 20 && $currentSecond <= 29) {
        //         $this->info("Open at: " . $currentTime->toDateTimeString());
        //     } elseif ($currentSecond >= 30 && $currentSecond <= 39) {
        //         $this->info("Close at: " . $currentTime->toDateTimeString());
        //     } elseif ($currentSecond >= 40 && $currentSecond <= 49) {
        //         $this->info("Open at: " . $currentTime->toDateTimeString());
        //     } elseif ($currentSecond >= 50 && $currentSecond <= 59) {
        //         $this->info("Close at: " . $currentTime->toDateTimeString());
        //     }

        //     // Sleep for 1 second to keep sync with real-time seconds
        //     sleep(1);
        // }

        while (true) {
            $currentTime = Carbon::now();
            $currentSecond = (int)$currentTime->format('s');
            
            $data = [
                'status' => 'Success',
                'message' => 'Data successfully',
                'periodno' => 'nomorbaru',
                'result' => '',
                'is_countdown' => false,
                'timeCD' => $currentTime->toDateTimeString(),
                'statusBet' => 'Open bet'
            ];
        
            $isOpenBet = $currentSecond % 20 < 10; 
            
            if ($isOpenBet) {
                $data["lastPeriod"] = 'asd';
                $data["countDown"] = 10 - ($currentSecond % 10);  
                
                if ($currentSecond % 10 < 8) {
                    $data["state_anim"] = 1;  
                } else {
                    $data["state_anim"] = 2;  
                }
                
                $this->info("Open at: " . $currentSecond . " " . $currentTime->toDateTimeString() . " " . $data["state_anim"]);
            } else {
                $data["statusBet"] = 'Close bet';
                $data["state_anim"] = 3;  
                
                if ($currentSecond % 20 === 10) {
                    $this->info("Update at: " . $currentTime->toDateTimeString());
                    $this->info("Close at: " . $currentTime->toDateTimeString());
                    $this->info("Result at: " . $currentTime->toDateTimeString());
                }
                
                if ($currentSecond % 20 == 17) {
                    $this->info("Finish at: " . $currentTime->toDateTimeString());
                }
            }
            
            sleep(1);
        }

        return 0;
    }
}
