<?php

namespace Tests\Feature;

use App\Models\Zunit_test_01;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_application_returns_a_successful_response()
    {
        $this->withoutExceptionHandling();

        $this->post('/zunit_test_01s', [
            'name' => 'a',
        ]);

        $this->assertCount(1, Zunit_test_01::all());
    }
}
