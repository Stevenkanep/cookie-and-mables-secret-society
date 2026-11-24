<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Throwable;

class WeatherService
{
    /**
     * Current weather from OpenWeather. Defaults to Manila.
     * Returns null when the key is missing or the API call fails.
     */
    public function current(float $lat = 14.5995, float $lon = 120.9842): ?array
    {
        $key = config('services.openweather.key');

        if (! $key) {
            return null;
        }

        return Cache::remember("weather:{$lat}:{$lon}", now()->addMinutes(10), function () use ($lat, $lon, $key) {
            try {
                $data = Http::timeout(10)
                    ->get('https://api.openweathermap.org/data/2.5/weather', [
                        'lat' => $lat,
                        'lon' => $lon,
                        'units' => 'metric',
                        'appid' => $key,
                    ])
                    ->throw()
                    ->json();
            } catch (Throwable $e) {
                return null;
            }

            return [
                'place' => $data['name'] ?? 'Selected location',
                'temperature' => $data['main']['temp'] ?? null,
                'description' => $data['weather'][0]['description'] ?? null,
                'humidity' => $data['main']['humidity'] ?? null,
                'icon' => $data['weather'][0]['icon'] ?? null,
            ];
        });
    }
}