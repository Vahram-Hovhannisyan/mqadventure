<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Tour;

class TourController extends Controller
{
    public function page()
    {
        $tours = Tour::active()->orderBy('sort_order')->get();
        return view('front.tours', compact('tours'));
    }
}
