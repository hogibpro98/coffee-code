<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use App\Exceptions\PosException;

class ZoomService
{
    public function store($title, $agenda, $minutes, $start)
    {
        $zoomApiUrl = str_replace('%account%', config('app.zoom.account'), $this->getEndpointURL('create'));
        $response = $this->getHttpClient()->post($zoomApiUrl, $this->getBody($title, $agenda, $minutes, $start));
        return $response->body();
    }

    public function update($meetingId, $title, $agenda, $minutes, $start)
    {
        $zoomApiUrl = str_replace('%id%', $meetingId, $this->getEndpointURL('update'));
        $response = $this->getHttpClient()->patch($zoomApiUrl, $this->getBody($title, $agenda, $minutes, $start));
        return $response->status();
    }

    public function detail($meetingId)
    {
        $zoomApiUrl = str_replace('%id%', $meetingId, $this->getEndpointURL('detail'));
        $response = $this->getHttpClient()->get($zoomApiUrl);
        return $response->body();
    }

    public function destroy($meetingId)
    {
        $zoomApiUrl = str_replace('%id%', $meetingId, $this->getEndpointURL('delete'));
        $response = $this->getHttpClient()->delete($zoomApiUrl);
        return $response->status();
    }

    private function getBody($title, $agenda, $minutes, $start)
    {
        $carbon = Carbon::parse($start);

        return [
            "topic" => $title,
            "agenda" => $agenda,
            "type" => "2",
            "duration" => intval($minutes),
            "start_time" => $carbon->format('Y-m-d\TH:i:s'),
            "timezone" => "Asia/Tokyo",
            "settings" => [
                "use_pmi" => "false",
                "waiting_room" => "true"
            ]
        ];
    }

    private function getHttpClient()
    {
        $token = $this->getToken();
        return $response = Http::withHeaders([
            'Content-type' => 'application/json',
            'Authorization' => "Bearer $token",
        ]);
    }

    private function urlSafeBase64Encode($str)
    {
        return str_replace(array('+', '/', '='), array('-', '_', ''), base64_encode($str));
    }

    private function getEndpointURL($key)
    {
        return config('app.zoom.url') . config('app.zoom.path')[$key];
    }

    private function getToken()
    {
        $expiration = time() + 20;
        $header = self::urlSafeBase64Encode('{"alg":"HS256","typ":"JWT"}');
        $payload = self::urlSafeBase64Encode('{"iss":"' . config('app.zoom.api_key') . '","exp":' . $expiration . '}');
        $signature = self::urlSafeBase64Encode(hash_hmac('sha256', "$header.$payload", config('app.zoom.api_secret') , true));
        return "$header.$payload.$signature";
    }

}
