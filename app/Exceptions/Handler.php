<?php

namespace App\Exceptions;

use Exception;
use InvalidArgumentException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {

        
        if(($exception instanceof InvalidArgumentException)) abort(404);

        elseif(($exception instanceof TokenMismatchException))
        {
            // dd($request);
            //abort(404);
            return redirect('/admin/home');
        }
        elseif(($exception instanceof \GuzzleHttp\Exception\ServerErrorResponseException))
        {
                // echo "hellpo";
                die();
        }
        elseif(($exception instanceof \GuzzleHttp\Exception\ClientException))
        {
            // dd($request);

            if($exception->getResponse()->getStatusCode()==400)
            {
                // echo "400";

                            $message=str_replace('"""
', "", $exception->getMessage());

            $message=str_replace('
"""', "", $message);

             $message=substr($message, strpos($message, "response:")+9);

                    // dd($data);
             $message=json_decode($message);
             if(strpos($message->errors[0]->message, "See messages for details"))
             {

                $message=str_replace(".targets[0]: ", "", $message->messages[0]->message);
                echo ($message);
             }
             else
             {
                echo ($message->errors[0]->message);
             }
            
                die();

            }
            else
            {
            $message=str_replace('"""
', "", $exception->getMessage());

            $message=str_replace('
"""', "", $message);
        //var_dump($message);
            abort(444);

        }
             // return response()->json(['message' => $exception->getMessage()]);
            // /return redirect('/admin/home');
        }
          elseif(($exception instanceof \GuzzleHttp\Exception\RequestException))
        {
            // dd($request);

    
 
            if($exception->getStatusCode()==400)
            {
                // echo "400";

                            $message=str_replace('"""
', "", $exception->getMessage());

            $message=str_replace('
"""', "", $message);

             $message=substr($message, strpos($message, "response:")+9);

                    // dd($data);
             $message=json_decode($message);
             if(strpos($message->errors[0]->message, "See messages for details"))
             {

                $message=str_replace(".targets[0]: ", "", $message->messages[0]->message);
                echo ($message);
             }
             else
             {
                echo ($message->errors[0]->message);
             }
            
                die();

            }
            else
            {
            $message=str_replace('"""
', "", $exception->getMessage());

            $message=str_replace('
"""', "", $message);
        //var_dump($message);
            abort(444);

        }
             // return response()->json(['message' => $exception->getMessage()]);
            // /return redirect('/admin/home');
        }      
        // if($exception->getStatusCode()==525)
        // {

        //     echo $exception->getMessage();
        //     die();

        // }
//         """
// Client error: `POST https://api.cloudflare.com/client/v4/zones/6af67be27b884e0a45acd233060b640b/custom_certificates` resulted in a `400 Bad Request` response:\n
// {"success":false,"errors":[{"code":1200,"message":"Invalid certificate key"}],"messages":[],"result":null}\n
// """


        
        return parent::render($request, $exception);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest(route('auth.login'));
    }
}
