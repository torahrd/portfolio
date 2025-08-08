<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        // レート制限エラーをユーザーフレンドリーなメッセージに変換
        $this->renderable(function (TooManyRequestsHttpException $e, Request $request) {
            if ($request->is('forgot-password') && $request->isMethod('POST')) {
                return redirect()->back()
                    ->withInput($request->only('email'))
                    ->withErrors(['email' => '短時間に多くのリクエストが送信されました。1分後に再度お試しください。']);
            }
        });
    }
}
