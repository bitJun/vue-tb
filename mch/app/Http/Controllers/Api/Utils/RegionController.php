<?php

namespace App\Http\Controllers\Api\Utils;

use App\Services\Utils\RegionService;
use App\Http\Controllers\Controller;

class RegionController extends Controller
{
    public function getRegionsByTree(RegionService $regionService)
    {
        $regions =  $regionService->getRegionsByTree();
        return response()->json($regions);
    }
}
