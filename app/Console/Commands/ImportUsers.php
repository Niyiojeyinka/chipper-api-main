<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;

class ImportUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:import {url} {limit}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import users from a JSON URL';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $url = $this->argument('url');
        $limit = $this->argument('limit');

        $response = Http::get($url);

        if ($response->failed()) {
            $this->error('Failed to retrieve data from the provided URL.'. $response->status());
            return;
        }

        $users = $response->json();

        if (!is_array($users)) {
            $this->error('Invalid JSON structure.');
            return;
        }

        $users = array_slice($users, 0, $limit);

        $userData = [];
        foreach ($users as $user) {
            $userData[] = [
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => Hash::make('password'), // Default password
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        User::insert($userData);

        $this->info("Successfully imported {$limit} users.");
    }
}
