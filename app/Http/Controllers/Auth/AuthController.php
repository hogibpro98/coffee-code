<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTFactory;
use Tymon\JWTAuth\Facades\JWTAuth;

use App\Exceptions\PosException;
use App\Mail\MainMailable;
use App\Models\User;

use DB;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        $token = auth()->attempt($credentials);

        if($token){
            return response()->json([
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => config('jwt.ttl'),
                'refresh_token' => $this->createRefreshToken()
            ], 200);
        }

        throw new PosException('00', '001', 401);
    }

    public function logout()
    {
        try {
            auth()->logout();
            return response()->json(null, 204);
        } catch (JWTException $e) {
            // 認証モジュールエラー
            throw new PosException('00', '002', 400);
        }
    }

    public function me()
    {
        return response()->json(Auth::user(), 200);
    }

    /**
     * API用のリフレッシュトークンを作成
     *
     * @return string
     */
    private function createRefreshToken()
    {
        $customClaims = self::getJWTCustomClaimsForRefreshToken();
        $payload = JWTFactory::make($customClaims);
        $token = JWTAuth::encode($payload)->get();
        return $token;
    }
    /**
     * リフレッシュトークン用CustomClaimsを返却
     *
     * @return object
     */
    private function getJWTCustomClaimsForRefreshToken()
    {
        $data = [
            'sub' => Auth::user()->id,
            'iat' => Carbon::now(),
            'exp' => Carbon::now()->addMinutes(config('jwt.refresh_ttl'))->getTimestamp()
        ];
        return JWTFactory::customClaims($data);
    }
}
