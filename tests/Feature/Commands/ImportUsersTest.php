<?php

namespace Tests\Feature\Commands;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ImportUsersTest extends TestCase
{
    use RefreshDatabase;

    public function test_import_users_command()
    {
        Http::fake([
            'jsonplaceholder.typicode.com/*' => Http::response([
                ['name' => 'Leanne Graham', 'email' => 'Sincere@april.biz'],
                ['name' => 'Ervin Howell', 'email' => 'Shanna@melissa.tv']], 200),
        ]);

        $this->artisan('users:import https://jsonplaceholder.typicode.com/users 2')
             ->expectsOutput('Successfully imported 2 users.')
             ->assertExitCode(0);

        $this->assertDatabaseCount('users', 2);
        $this->assertDatabaseHas('users', ['email' => 'Sincere@april.biz']);
        $this->assertDatabaseHas('users', ['email' => 'Shanna@melissa.tv']);
    }
}
