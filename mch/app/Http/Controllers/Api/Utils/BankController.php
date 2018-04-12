<?php
namespace App\Http\Controllers\Api\Utils;

use App\Services\Utils\BankService;
use App\Http\Controllers\Controller;

class BankController extends Controller
{
    public function getBanks(BankService $bankService)
    {
        $banks =  $bankService->getBanks();
        return response()->json($banks);
    }
}