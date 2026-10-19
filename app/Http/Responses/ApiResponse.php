<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;

/**
 * Envelope padrão das respostas da API.
 *
 * Sucesso: { "data": ..., "message": "..." }
 * Erro:    { "message": "...", "errors": { "campo": ["..."] } }
 *
 * Ponto de partida da tarefa #3 (padrão de resposta). Ajustar com o time.
 */
class ApiResponse
{
    public static function success(mixed $data = null, ?string $message = null, int $status = 200): JsonResponse
    {
        return response()->json(array_filter([
            'data' => $data,
            'message' => $message,
        ], fn ($value) => $value !== null), $status);
    }

    public static function error(string $message, array $errors = [], int $status = 400): JsonResponse
    {
        return response()->json(array_filter([
            'message' => $message,
            'errors' => $errors ?: null,
        ], fn ($value) => $value !== null), $status);
    }
}
