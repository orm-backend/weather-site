<?php
namespace App\Http\Controllers;

use App\Services\WeatherService;
use Illuminate\Http\Request;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class WeatherController
{

    const HISTORY_DAYS_LIMIT = 30;
    
    /**
     *
     * @var \App\Services\WeatherService
     */
    private $service;

    public function __construct(WeatherService $service)
    {
        $this->service = $service;
    }
    
    /**
     * Show index page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $histories = [];
        $errors = [];
        
        try {
            $histories = $this->service->getHistory(self::HISTORY_DAYS_LIMIT);
        } catch (ValidationException $e) {
            $errors = $e->errors();
        } catch (ConnectionException $e) {
            Log::warning($e);
            $errors[] = __('Weather service not available.');
        } catch (\Exception $e) {
            Log::error($e);
            $errors[] = __('Something went wrong.');
        }

        return view('index', ['histories' => $histories, 'errors' => $errors]);
    }
    
    /**
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function byDate(Request $request)
    {
        $history = null;
        $date = trim($request->json()->get('date'));
        
        try {
            $history = $this->service->getByDate($date);
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
            ], $e->status);
        } catch (ConnectionException $e) {
            Log::warning($e);
            return response()->json([
                'message' => __('Weather service not available.')
            ], 503);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'message' => __('Something went wrong.')
            ], 500);
        }

        return response()->json($history);
    }

}
