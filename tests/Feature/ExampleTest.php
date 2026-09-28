<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('SMK Negeri 3 Yogyakarta');
        $response->assertSee('Alur 5 Fase Pembelajaran');
        $response->assertSee('Portal Siswa');
        $response->assertSee('Ruang Guru');
        $response->assertSee('Supervisi Sistem');
        $response->assertSee(route('login.student'));
        $response->assertSee(route('login.teacher'));
        $response->assertSee(route('login.admin'));
    }
}
