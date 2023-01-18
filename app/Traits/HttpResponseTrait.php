<?php

namespace App\Traits;

trait HttpResponseTrait
{
    public function success($message,$data=[],$code= 200)
    {
        $response_arr = [
            'success'=>true,
            'message'=>$message,
        ];
        if ($data){
            $response_arr['data']=$data;
        }
        return response($response_arr,$code);
    }

    public function error($message,$code= 400)
    {
        return response([
            'success'=>false,
            'message'=>$message,
        ],$code);
    }

    public function response($success,$message,$data = [])
    {
        if ($success){
            return $this->success($message,$data);
        }
        return $this->error($message);
    }

}
