<?php

namespace App\Traits;

trait ApiResponse
{
    public function sendResponse($data, $message = null, $status_code = 200)
    {
        $response = [
            'status_code' => $status_code ?? 200,
            'data'    => $data,
            'message' => $message,
        ];


        return response()->json($response);
    }


    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $status_code = 400, $data  = null)
    {
        $response = [
            'status_code' => $status_code,
            'message' => $error,
            'data' => $data
        ];

        return response()->json($response);
    }
}
