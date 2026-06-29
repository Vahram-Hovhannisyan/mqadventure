<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\GalleryItem;
use App\Models\SiteSetting;
use App\Models\Tour;

class HomeController extends Controller
{
    public function index()
    {
        $tours   = Tour::all();
        $gallery = GalleryItem::all();

        return view('front.home', compact('tours', 'gallery'));
    }
}
