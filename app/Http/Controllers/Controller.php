<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $response;

    protected $statusCode = 200;

    /**
     * @param $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public static function responseFailure($message, $code = 400)
    {
        return response()->json([
            'message' => $message,
        ], $code);
    }

    /**
     * @param $data
     * @param $code
     * @param array $headers
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseGeneric($data, $code, $headers = [])
    {
        return response()->json($data, $code, $headers);
    }

    /**
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseNotFound($message = 'Not found')
    {
        return $this->setStatusCode(404)->responseError($message);
    }

    /**
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseInternalServerError($message = 'Internal Error')
    {
        return $this->responseGeneric([
            'message' => $message,
        ], 500);
    }

    /**
     * @param $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseError($message)
    {
        return $this->responseGeneric([
            'message' => $message,
        ], $this->getStatusCode());
    }

    /**
     * @param $data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseSuccess($data)
    {
        return $this->responseGeneric($data, $this->getStatusCode());
    }

    /**
     * @return mixed
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param $statusCode
     *
     * @return $this
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * @param \Exception $exception
     */
    public function logError(\Exception $exception)
    {
        Log::error(implode('|', [
                $exception->getFile(),
                $exception->getLine(),
                $exception->getCode(),
                $exception->getMessage(),
                get_class($this),
            ])
        );
    }

    /**
     * @param \Exception $exception
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseException(\Exception $exception)
    {
        $this->logError($exception);

        if ($exception->getCode() >= 400 && $exception->getCode() < 500) {
            return $this->setStatusCode($exception->getCode())
                ->responseError($exception->getMessage());
        } else {
            return $this->responseInternalServerError($exception->getMessage());
        }
    }
}
