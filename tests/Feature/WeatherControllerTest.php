<?php
namespace Tests\Feature;

use Tests\TestCase;

class WeatherControllerTest extends TestCase
{

    public function testGetByDate()
    {
        $response = $this->postJson('/api/weather', [
            'date' => '2021-01-01'
        ]);

        $response->assertStatus(200)->assertJsonStructure([
            'id',
            'date',
            'temp'
        ]);
    }

    public function testGetByEmptyDate()
    {
        $response = $this->postJson('/api/weather');

        $response->assertStatus(422)->assertJsonStructure([
            'errors' => [
                'date'
            ]
        ]);
    }
    
    public function testGetByFutureDate()
    {
        $response = $this->postJson('/api/weather', [
            'date' => '2121-01-01'
        ]);
        
        $response->assertStatus(422)->assertJsonStructure([
            'errors' => [
                'date'
            ]
        ]);
    }
    
    public function testGetByIncorrectDate()
    {
        $response = $this->postJson('/api/weather', [
            'date' => '21210101'
        ]);
        
        $response->assertStatus(422)->assertJsonStructure([
            'errors' => [
                'date'
            ]
        ]);
    }
}
