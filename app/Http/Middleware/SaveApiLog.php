<?php

namespace App\Http\Middleware;

use App\Models\ApiLog;
use Closure;
use Illuminate\Http\Response;
use PHPUnit\Framework\Constraint\IsJson;

class SaveApiLog
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /**@var Response $response */
        $response = $next($request);

        // 返回数据格式是json
        $status = $response->status();
        if ((new IsJson())->evaluate($response->content(), '', true)) {
            $content = json_decode($response->getContent(), true);
            if (isset($content['code'])) {
                $status = $content['code'];
            }
        }

        $data = [
            'url'             => $request->url(),
            'request_header'  => json_encode($request->headers->all()),
            'request_body'    => json_encode($request->all(), JSON_UNESCAPED_UNICODE),
            'response_status' => json_encode($status, JSON_UNESCAPED_UNICODE),
            'response_header' => json_encode($response->headers->all(), JSON_UNESCAPED_UNICODE),
            'response_body'   => $response->content(),
            'execute_time'    => microtime(true) - LARAVEL_START,
            'ip'              => $request->ip(),
        ];

        // 正式环境只记录错误日志
        if ($status !== Response::HTTP_OK || env('APP_ENV') != 'prod') {
            ApiLog::create($data);
            \Log::info('access_log', $data);
        }

        return $response;
    }
}
