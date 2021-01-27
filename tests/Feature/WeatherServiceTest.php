<?php
namespace Tests\Feature;

use App\Services\WeatherService;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;
use App\Entities\History;

class WeatherServiceTest extends TestCase
{

    /**
     * 
     * @var \App\Services\WeatherService
     */
    private $service;

    /**
     *
     * {@inheritdoc}
     * @see \Illuminate\Foundation\Testing\TestCase::setUp()
     */
    protected function setUp(): void
    {
        if (! $this->app) {
            $this->refreshApplication();
        }

        $this->service = $this->app->make(WeatherService::class);
        $this->setUpHasRun = true;
    }

    public function testGetByEmptyDate()
    {
        $errors = [];
        
        try {
            $this->service->getByDate('');
        } catch (\Exception $e) {
            $this->assertInstanceOf(ValidationException::class, $e);
            $errors = $e->errors();
        }
        
        $this->assertArrayHasKey('date', $errors);
    }
    
    public function testGetByIncorrectDate()
    {
        $errors = [];
        
        try {
            $this->service->getByDate('12345');
        } catch (\Exception $e) {
            $this->assertInstanceOf(ValidationException::class, $e);
            $errors = $e->errors();
        }
        
        $this->assertArrayHasKey('date', $errors);
    }
    
    public function testGetByFutureDate()
    {
        $errors = [];
        
        try {
            $this->service->getByDate(Carbon::today()->addDay()->format('Y-m-d'));
        } catch (\Exception $e) {
            $this->assertInstanceOf(ValidationException::class, $e);
            $errors = $e->errors();
        }
        
        $this->assertArrayHasKey('date', $errors);
    }
    
    public function testGetByMysqlFormatDate()
    {
        /**
         * 
         * @var \App\Entities\History $data
         */
        $data = $this->service->getByDate(Carbon::today()->addMonths(-3)->format('Y-m-d'));
        $this->assertNotNull($data);
        $this->assertInstanceOf(History::class, $data);
    }
    
    public function testGetByAmericanlFormatDate()
    {
        $data = $this->service->getByDate(Carbon::today()->addMonths(-3)->format('m/d/Y'));
        $this->assertNotNull($data);
        $this->assertInstanceOf(History::class, $data);
    }
    
    public function testGetByTextFormatDate()
    {
        $data = $this->service->getByDate(Carbon::today()->addMonths(-3)->format('d F Y'));
        $this->assertNotNull($data);
        $this->assertInstanceOf(History::class, $data);
    }
    
    public function testGetByRussianFormatDate()
    {
        $data = $this->service->getByDate(Carbon::today()->addMonths(-3)->format('d.m.Y'));
        $this->assertNotNull($data);
        $this->assertInstanceOf(History::class, $data);
    }
    
    public function testGetHistoryByEmptyDays()
    {
        $errors = [];
        
        try {
            $this->service->getHistory(0);
        } catch (\Exception $e) {
            $this->assertInstanceOf(ValidationException::class, $e);
            $errors = $e->errors();
        }
        
        $this->assertArrayHasKey('lastDays', $errors);
    }
    
    public function testGetHistoryByNegativeDays()
    {
        $errors = [];
        
        try {
            $this->service->getHistory(-1);
        } catch (\Exception $e) {
            $this->assertInstanceOf(ValidationException::class, $e);
            $errors = $e->errors();
        }
        
        $this->assertArrayHasKey('lastDays', $errors);
    }
    
    public function testGetHistory()
    {
        $data = $this->service->getHistory(30);
        $this->assertIsArray($data);
        $this->assertTrue(count($data) > 0);
    }
}
