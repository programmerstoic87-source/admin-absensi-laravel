<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Location;
use App\Models\Setting;
use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ApiSettingController extends Controller
{
    public function getSettingsByBranch(Request $request)
    {
        $branchId = $request->query('branch_id');

        return response()->json(
            Setting::where('branch_id', $branchId)->get()
        );
    }
}
