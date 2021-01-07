<?php

namespace App\Http\Middleware;

use App\Models\Visitor;
use Closure;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Tymon\JWTAuth\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class FrontAuth extends BaseMiddleware
{
    public function __construct(JWTAuth $auth)
    {
        parent::__construct($auth);
    }

    /**
     * 刷新token
     *
     * @param         $request
     * @param Closure $next
     *
     * @return mixed
     * @author  maxiongfei <maxiongfei@vchangyi.com>
     * @date    2019/5/24 11:15 AM
     */
    public function handle($request, Closure $next)
    {
        Log::info("frontend请求参数Authorization", [$request->header('Authorization')]);
        $originType = $request->header('Origin-Type');
        $token = parseJwtToken($request->header('Authorization'));
        if (empty($token)) {
            Log::error("没有获取到token", $request->header());
            // Unauthorized response if token not there
            throw new UnauthorizedHttpException('jwt-auth', '未登录');
        }
        try {
            $tokenData = JWT::decode($token, env('JWT_SECRET'), ['HS256']);
            $tokenDataArray = json_decode($tokenData->sub, true);
            // Log::info("tokenData", $tokenDataArray);
//            dd($tokenDataArray);
            if ($tokenDataArray['member'] != 0) {
                // 同步通讯录，清空redis
                $cacheUserData = Redis::get($originType.'.'.$tokenDataArray['memUserid']);
                if (is_null($cacheUserData)) {
                    throw new UnauthorizedHttpException('jwt-auth', '无效的token');
                }
            }

            return $next($request);
        } catch (ExpiredException $e) {
            throw new UnauthorizedHttpException('jwt-auth', 'token已过期');
        } catch (\Exception $e) {
            throw new UnauthorizedHttpException('jwt-auth', '无效的token');
        }
    }
}
