<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cinema;

class RoomController extends Controller
{
    public function index()
    {
        $cinemas = Cinema::with(['rooms.seats'])->get();
        return view('index', compact('cinemas'));
    }
}
