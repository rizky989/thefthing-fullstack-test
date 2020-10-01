<?php

namespace App\Services;

class ResponseTransformer
{
    public static function default($data, $message, $code){
        $response['status']['code'] = $code;
        $response['status']['response'] = self::responseText($code);
        $response['status']['message'] = $message;
        $response['result'] = $data;
        
        return response()->json($response, 200);
    }

    private static function responseText(string $code){
        if ($code[0] == 2){
            return 'success';
        }else{
            return 'error';
        }
    }
}