<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckReferer
{
    public function handle(Request $request, Closure $next): Response
    {
        // Danh sách các domain được phép
        $allowedDomains = [
            'http://localhost:3000',
            'http://127.0.0.1:3000',
            'http://localhost:5173',
            'http://127.0.0.1:5173',
        ];

        // Lấy referer từ header
        $referer = $request->header('referer');

        // Kiểm tra API key trong header
        $apiKey = $request->header('X-API-Key');
        $validApiKey = env('API_KEY', 'your-secret-api-key');

        // Kiểm tra user-agent để nhận biết Postman
        $userAgent = $request->header('User-Agent');
        $isPostman = str_contains($userAgent, 'Postman');

        // Nếu là Postman và có API Key hợp lệ
        if ($isPostman && $apiKey && $apiKey === $validApiKey) {
            return $next($request);
        }

        // Nếu có API key hợp lệ, cho phép truy cập
        if ($apiKey && $apiKey === $validApiKey) {
            return $next($request);
        }

        // Kiểm tra referer
        $isAllowed = false;
        if ($referer) {
            foreach ($allowedDomains as $domain) {
                if (str_starts_with($referer, $domain)) {
                    $isAllowed = true;
                    break;
                }
            }
        }

        if (!$isAllowed) {
            return response()->json([
                'message' => 'Unauthorized access',
            ], 403);
        }

        return $next($request);
    }
}
