<?php namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

use Symfony\Component\HttpFoundation\Response;

class ApiController extends BaseController
{
    /**
     * $statusCode
     *
     * @var integer
     */
    protected $statusCode = Response::HTTP_OK; // 200
    
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
     * respondUnauthorized (401)
     *
     * @param mixed $message
     * @return void
     */
    public function respondUnauthorized($message = 'Unauthorized, check your credentials!.')
    {
        return $this->setStatusCode(Response::HTTP_UNAUTHORIZED)->respondWithError($message);
    }
    
    /**
     * respondNotFound (404)
     *
     * @param mixed $message
     * @return void
     */
    public function respondNotFound($message = 'Not found!')
    {
        return $this->setStatusCode(Response::HTTP_NOT_FOUND)->respondWithError($message);
    }

    /**
     * respondUnprocessableEntity (422)
     *
     * @param mixed $message
     * @return void
     */
    public function respondUnprocessableEntity($message)
    {
        return $this->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY)->respondWithError($message);    
    }
    
    /**
     * respondInternalError (500)
     *
     * @param mixed $message
     * @return void
     */
    public function respondInternalError($message = 'Internal Error!')
    {
        return $this->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR)->respondWithError($message);
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
