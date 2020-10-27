<?php

    namespace App\Traits;
    use Illuminate\Http\Response;
    Trait ApiResponser{

        Protected function successResponse($data, $code = Response::HTTP_OK)
        {
            return response () -> json(['data' => $data],$code);
        }

        protected function errorResponse($message,$code)
        {
            return response()->json(['error'=>$message,'code'=>$code],$code);
        }
    }
?>