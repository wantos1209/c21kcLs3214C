<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Events\CountdownEvent;
use App\Models\Period;

class BroadcastCountdown extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:broadcast-countdown';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Broadcast countdown event every 10 seconds';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        while (true) {
            if (file_exists(storage_path('app/stop_broadcast'))) {
                $this->info('Broadcast stopped.');
                unlink(storage_path('app/stop_broadcast')); // Hapus file stop
                break;
            }
    
            $period = Period::create([]);
    
            for ($i = 10; $i >= 0; $i--) {
                
                $data = [
                    'periodno' => $period->periodno,
                    'count_down' => $i
                ];
                
                broadcast(new CountdownEvent($data)); 
                sleep(1); 
            }
            sleep(10); 
        }
    }
}
