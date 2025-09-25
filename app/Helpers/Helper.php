<?php

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\JsonResponse;

if (!function_exists('to_latin_digits')) {
    function to_latin_digits(string|null $str): string|null
    {
        if (blank($str))
            return $str;

        return str_replace(
            array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'),
            array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9'),
            $str
        );
    }
}

if (!function_exists('persian_digits')) {
    function to_persian_digits(string|int|float|null $str): string|null
    {
        if ($str === null)
            return null;

        return str_replace(
            array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9'),
            array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'),
            (string)$str
        );
    }
}
if (!function_exists('generateOrderCode')) {
    function generateOrderCode($userId)
    {
        $random = random_int(100, 999);
        return 1 . $random . str_pad($userId, 5, '0', STR_PAD_LEFT) . time();
    }
}

use Illuminate\Pagination\LengthAwarePaginator;

function api_response(mixed $data = [], string $message = '', int $status = 200, array $append = []): JsonResponse
{
    $response = [
        'message' => $message,
    ];

    if ($data instanceof LengthAwarePaginator) {
        $response['data'] = $data->items();
        $response['total'] = $data->total();
        $response['per_page'] = $data->perPage();
        $response['last_page'] = $data->lastPage();
        $response['next_page_url'] = $data->nextPageUrl();
    } else {
        if ($data instanceof Arrayable) {
            $data = $data->toArray();
        }

        $response['data'] = $data;
    }

    return response()->json(array_merge($response, $append), $status);
}

function generatePaginationLinks(LengthAwarePaginator $data)
{
    $links = [];
    $links[] = [
        'url' => $data->previousPageUrl(),
        'label' => '&laquo; Previous',
        'active' => $data->onFirstPage(),
    ];
    foreach (range(1, $data->lastPage()) as $page) {
        $links[] = [
            'url' => $data->url($page),
            'label' => (string) $page,
            'active' => $data->currentPage() === $page,
        ];
    }

    $links[] = [
        'url' => $data->nextPageUrl(),
        'label' => 'Next &raquo;',
        'active' => !$data->hasMorePages(),
    ];

    return $links;
}


function normalize_filename(string $filename): string
{
    $replacements = [
        'тАУ' => '-',
    ];

    return strtr($filename, $replacements);
}
