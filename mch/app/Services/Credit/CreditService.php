<?php

namespace App\Services\Credit;

use App\Model\CreditRule;
use Illuminate\Support\Facades\Auth;

class CreditService
{
    public function getCreditRule()
    {
        $shop_id = Auth::user()->shop_id;
        $data = CreditRule::where('shop_id', $shop_id)->first();
        if($data)
        {
            $data['rule'] = json_decode($data['rule'],true);
        }else{
            $data['rule'] = [
                'self_percent'=>SELF_PERCENT,
                'parent_percent'=>PARENT_PERCENT,
                'partner_percent'=>PARTNER_PERCENT
            ];
        }
        return $data;
    }

    public function putCreditRule($params)
    {
        $shop_id = Auth::user()->shop_id;

        $rule = [
            'self_percent' => number_format($params['self_percent'],'2','.',''),
            'parent_percent' => number_format($params['parent_percent'],'2','.',''),
            'partner_percent' => number_format($params['partner_percent'],'2','.','')
        ];

        $data = [
            'rule'=>json_encode($rule)
        ];

        if(CreditRule::where('shop_id',$shop_id)->first())
        {
            CreditRule::where('shop_id',$shop_id)->update($data);
        }else{
            $data['shop_id'] = $shop_id;
            CreditRule::create($data);
        }
        return ['status'=>true];
    }
}
