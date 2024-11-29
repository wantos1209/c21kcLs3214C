<?php

namespace Database\Seeders;

use App\Models\Game;
use Illuminate\Database\Seeder;

class GameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Game::create([
            'name' => 'buk buk',
            'status' => 'hot',
            'group' => 'game',
            'model' => 'new'
        ]);
    }
}
