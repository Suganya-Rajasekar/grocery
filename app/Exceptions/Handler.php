<?php

namespace App\Exceptions;
use App\Http\Controllers\Controller;
use Request;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(function (\Illuminate\Auth\AuthenticationException $e, $request) {
            if (Request::is('api/*')) {
                $Controller = new Controller;
                $Controller->ErrorResponse('Invalid Access Token',401);
            }
        });
    }
}

