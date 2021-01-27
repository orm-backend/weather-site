<?php
namespace App\Services;

use App\Entities\History;
use App\Exceptions\ServiceException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class WeatherService
{

    const JSONRPC_VERSION = "2.0";

    /**
     *
     * @var string
     */
    private $url;

    public function __construct()
    {
        $this->url = config('site.service_url');
    }

    /**
     *
     * @param string $date
     * @return \App\Entities\History|null Null if not records found
     * @throws \Illuminate\Http\Client\RequestException If transport failed
     * @throws \Illuminate\Validation\ValidationException If method parameters validation unsuccessful
     * @throws \App\Exceptions\ServiceException If response has incorrect format or responce contains not validation error
     */
    public function getByDate(string $date)
    {
        $data = [
            'jsonrpc' => self::JSONRPC_VERSION,
            'method' => 'weather.getByDate',
            'params' => [
                'date' => $date
            ],
            'id' => 1
        ];

        $response = Http::post($this->url, $data);

        return $this->parseResponse($response);
    }

    /**
     *
     * @param int $lastDays
     * @return \App\Entities\History[]
     * @throws \Illuminate\Http\Client\RequestException If transport failed
     * @throws \Illuminate\Validation\ValidationException If method parameters validation unsuccessful
     * @throws \App\Exceptions\ServiceException If response has incorrect format or responce contains not validation error
     */
    public function getHistory(int $lastDays): array
    {
        $data = [
            'jsonrpc' => self::JSONRPC_VERSION,
            'method' => 'weather.getHistory',
            'params' => [
                'lastDays' => $lastDays
            ],
            'id' => 1
        ];

        $response = Http::post($this->url, $data);

        return $this->parseResponse($response);
    }

    /**
     *
     * @param Response $response
     * @return array|null
     * @throws \Illuminate\Http\Client\RequestException If transport failed
     * @throws \Illuminate\Validation\ValidationException If method parameters validation unsuccessful
     * @throws \App\Exceptions\ServiceException If response has incorrect format or responce contains not validation error
     */
    private function parseResponse(Response $response)
    {
        $response->throw();
        $data = json_decode($response->body(), true);

        if (array_key_exists('error', $data)) {
            if (! empty($data['error']['data']['violations'])) {
                throw ValidationException::withMessages($data['error']['data']['violations']);
            }

            throw new ServiceException(empty($data['error']['message']) ? 'Unknown error.' : $data['error']['message']);
        }

        if (! array_key_exists('result', $data)) {
            throw new ServiceException('Bad json-rpc response format.');
        }

        $result = null;
        
        if (is_array($data['result'])) {
            if (Arr::isAssoc($data['result'])) {
                $result = History::deserialize($data['result']);
            } else {
                $result = [];
                
                foreach ($data['result'] as $element) {
                    $result[] = History::deserialize($element);
                }
            }
        }

        return $result;
    }
}
