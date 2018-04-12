<?php
namespace App\Transformers;
use App\Model\MokerMessage;
use League\Fractal\TransformerAbstract;

class MokerMessageTransformer extends TransformerAbstract {

    public function transform(MokerMessage $message) {
        return [
            'id' => $message['id'],
            'moker_id' => $message['moker_id'],
            'type' => $message['type'],
            'content' => json_decode($message['content']),
            'created_at' => beautifyTime(strtotime($message['created_at']), 1)
        ];
    }
}