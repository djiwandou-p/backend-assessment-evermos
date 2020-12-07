<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;

class ApiController extends Controller
{
    /**
     * return response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($content = [], $message, $statusCode = 200, $status = true)
    {
        $response = [];
        $response['header'] = [
            'code' => $statusCode,
            'message' => $message,
            'status' => $status,
        ];
        if($content){
            $response['content'] = $content;
        }
        return response()->json($response, $statusCode);
    }
}