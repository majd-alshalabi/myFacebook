<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
  public function sendResponse($result){
        $response=[
          'success'=>true,
          'data'=>$result
        ];
        return response()->json($response,200);
    }

  public function sendFaildResponse($result){
      $response=[
        'success'=>false,
        'data'=>$result
      ];
      return response()->json($response,400);
  }
}
