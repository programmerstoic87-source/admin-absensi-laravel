<?php

namespace App\Http\Controllers;

use App\Models\List;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index(Request $request)
    {
        return 'ok';
    }
}
