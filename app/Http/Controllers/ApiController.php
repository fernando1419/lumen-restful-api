<?php namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class ApiController extends BaseController
{
    /**
     * $statusCode
     *
     * @var integer
     */
    protected $statusCode = 200;

    /**
     * Get the value of statusCode
     */ 
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Set the value of statusCode
     *
     * @return  self
     */ 
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * respondCreated
     *
     * @param $message
     * @return void
     */
    public function respondCreated($message)
    {
        return $this->setStatusCode(201)->respond([
            'message' => $message
        ]);    
    }

    /**
     * respondUnprocessableEntity
     *
     * @param mixed $message
     * @return void
     */
    public function respondUnprocessableEntity($message)
    {
        return $this->setStatusCode(422)->respondWithError($message);    
    }
    
    /**
     * respondNotFound
     *
     * @param mixed $message
     * @return void
     */
    public function respondNotFound($message = 'Not found!')
    {
        return $this->setStatusCode(404)->respondWithError($message);
    }
    
    /**
     * respondInternalError
     *
     * @param mixed $message
     * @return void
     */
    public function respondInternalError($message = 'Internal Error!')
    {
        return $this->setStatusCode(500)->respondWithError($message);
    }

    /**
     * respondWithError
     *
     * @param mixed $data
     * @param array $headers
     * @return void
     */
    public function respondWithError($message)
    {
        return $this->respond([
            'error' => [
                'message' => $message,
                'status_code' => $this->getStatusCode()
            ]
        ]); 
    }
    
    /**
     * respond
     *
     * @param mixed $data
     * @param array $headers
     * @return void
     */
    public function respond($data, $headers = [])
    {
        return response()->json($data, $this->getStatusCode(), $headers);
    }   
    
}
