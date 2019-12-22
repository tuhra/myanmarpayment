<?php

namespace App\Traits;

trait RespondTrait
{


    protected $statusCode = 200;


    protected $data = NULL;



    public function getStatusCode()
    {
        return $this->statusCode;
    }


    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }


    public function getData()
    {
        return $this->data;
    }


    public function setResponseData($data)
    {
        $this->data = $data;
        return $this;
    }


    public function responseNotFound($message = 'Not found!', $headers = [])
    {
        return $this->setStatusCode(404)->responseWithError($message, $headers);
    }

    public function responseNotAcceptable($message = 'Data doesn\'t match with validation rules!', $headers = [])
    {
        return $this->setStatusCode(406)->responseWithError($message, $headers);
    }




    public function responseUnauthorized($message = 'Unauthorized access!', $headers = [])
    {
        return $this->setStatusCode(401)->responseWithError($message, $headers);
    }



    public function responseBadRequest($message = 'Bad request!', $headers = [])
    {
        return $this->setStatusCode(400)->responseWithError($message, $headers);
    }


    public function responseForbidden($message = 'Access forbidden!', $headers = [])
    {
        return $this->setStatusCode(403)->responseWithError($message, $headers);
    }



    public function responseConflict($message = 'Duplicate entry not allowed!', $headers = [])
    {
        return $this->setStatusCode(409)->responseWithError($message, $headers);
    }



    public function responseInternalError($message = 'Internal error!', $headers = [])
    {
        return $this->setStatusCode(500)->responseWithError($message, $headers);
    }


    public function responseUnavailable($message = 'Service Unavailable or temporary disabled or deactivated', $headers = [])
    {
        return $this->setStatusCode(503)->responseWithError($message, $headers);
    }



    public function responseWithError($message = NULL, $headers = [])
    {
        return $this->respond([
            'status' => 'error',
            'status_code' => $this->getStatusCode(),
            'data' => $this->getData(),
            'message' => $message,
        ], $headers);
    }

    public function responseSuccess($message = NULL, $headers = [])
    {
        return $this->setStatusCode(200)->responseWithSuccess($message, $headers);
    }


    public function responseNoContent($message = 'No content!', $headers = [])
    {
        return $this->setStatusCode(204)->responseWithSuccess($message, $headers);
    }



    public function responseWithSuccess($message = NULL, $headers = [])
    {
        return $this->respond([
            'status' => 'success',
            'status_code' => $this->getStatusCode(),
            'data' => $this->getData(),
            'message' => $message,
        ], $headers);
    }


    public function respond($data, $headers = [])
    {
        return response()->json($data, 200, $headers);
    }


    public function response(array $errors)
    {
        return $this->setResponseData($errors)->responseNotAcceptable();
    }


    public function forbiddenResponse($message = 'Access forbidden!', $headers = [])
    {
        return $this->responseForbidden($message, $headers);
    }

}