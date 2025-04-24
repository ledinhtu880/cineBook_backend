<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductComboResource;
use App\Repositories\ProductComboRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;


class ProductComboController extends Controller
{
    protected $productComboRepository;
    public function __construct(ProductComboRepository $productComboRepository)
    {
        $this->productComboRepository = $productComboRepository;
    }
    public function index()
    {
        try {
            $products = $this->productComboRepository->all();

            return response()->json([
                'status' => 'success',
                'data' => ProductComboResource::collection($products),
            ], 200);
        } catch (Exception $ex) {
            Log::error('Error in ProductController@index: ' . $ex->getMessage());
            return response()->json([
                'message' => 'Quá trình tải thành phố bị lỗi',
                'error' => $ex->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
