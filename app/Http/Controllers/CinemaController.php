<?php

namespace App\Http\Controllers;

use App\Repositories\CinemaRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CinemaController extends Controller
{
    protected $cinemaRepository;

    public function __construct(CinemaRepository $cinemaRepository)
    {
        $this->cinemaRepository = $cinemaRepository;
    }

    public function index()
    {
        $cinemas = $this->cinemaRepository->all();

        return response()->json([
            'status' => 'success',
            'data' => $cinemas
        ], 200);
    }
}
