<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Zunit_test_01;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    private function getUser()
    {
        return [
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'full_name' => 'Administrator',
            'first_name' => 'Fortune',
            'last_name' => 'Truong',
            'settings' => "[]",
        ];
    }

    public function test_the_application_returns_a_successful_response()
    {
        $this->withoutExceptionHandling();

        $user = User::create($this->getUser());
        // dd($user);
        $this->actingAs($user);
        $doc = $this->post('/dashboard/zunit_test_01s', [
            'name' => 'a',
        ]);
        dump($doc);
        dd(Zunit_test_01::all());
        $this->assertCount(1, Zunit_test_01::all());
    }
}
