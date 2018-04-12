<?php
namespace App\Services\Utils;

class BankService
{
    public function getBanks()
    {
        $bank = config('bank');
        return $bank;
    }
}