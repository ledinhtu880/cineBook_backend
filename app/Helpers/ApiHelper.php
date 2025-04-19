<?php

namespace App\Helpers;

use Illuminate\Http\Request;

class ApiHelper
{
    /**
     * Lấy các tham số từ request
     * 
     * @param Request $request
     * @return array
     */
    public static function getRequestParams(Request $request)
    {
        return [
            'sort' => $request->input('sort', 'created_at'),
            'search' => $request->input('search', null),
            'limit' => $request->input('limit', null),
            'page' => $request->input('page', 1),
        ];
    }

    /**
     * Áp dụng các bộ lọc chung cho query
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $params
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function applyFilters($query, array $params)
    {
        // Sắp xếp
        if (isset($params['sort'])) {
            $order = isset($params['order']) && strtolower($params['order']) === 'asc' ? 'asc' : 'desc';
            $query->orderBy($params['sort'], $order);
        }

        // Tìm kiếm theo từ khóa
        if (isset($params['search']) && !empty($params['search'])) {
            $query->where('title', 'like', '%' . $params['search'] . '%');
        }

        // Giới hạn số lượng kết quả
        if (isset($params['limit']) && is_numeric($params['limit'])) {
            $query->limit($params['limit']);
        }

        return $query;
    }
}
