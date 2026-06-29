<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LocaleController extends Controller
{
    public function switch(Request $request, string $locale)
    {
        $allowed = ['hy', 'ru', 'en'];

        if (in_array($locale, $allowed)) {
            session(['locale' => $locale]);
        }

        return redirect()->back()->withInput();
    }
}
