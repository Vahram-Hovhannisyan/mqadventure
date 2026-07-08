<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        try {
            // Проверяем, есть ли сессия
            if (Session::isStarted()) {
                $locale = Session::get('locale');
            } else {
                $locale = null;
            }

            // Если нет в сессии, берем из запроса или конфига
            if (!$locale) {
                $locale = $request->get('locale', config('app.locale', 'hy'));
            }

            // Проверяем, что локаль допустимая
            $allowedLocales = ['hy', 'ru', 'en'];
            if (!in_array($locale, $allowedLocales)) {
                $locale = 'hy';
            }

            // Устанавливаем локаль
            app()->setLocale($locale);

        } catch (\Exception $e) {
            // Если что-то пошло не так - используем локаль по умолчанию
            app()->setLocale('hy');
        }

        return $next($request);
    }
}
