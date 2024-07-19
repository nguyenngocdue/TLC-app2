<?php

namespace App\Http\Controllers\HomeWebPage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class HomeWebPageController extends Controller
{
    public function getType()
    {
        return "home-web-page";
    }

    public function indexModuQa(Request $request)
    {
        return view('home-web-page.home-web-page-moduqa', [
            "header" => [
                "What It Does",
                "What You Get",
                "Why It's Cool",
                "Who Use It",
                "Overview Video",
            ],
        ]);
    }

    public function indexCompany(Request $request)
    {
        return view('home-web-page.home-web-page-company', [
            "header" => [
                "Our Products",
                "Our Service",
                "Know Our Team",
                "FAQs",
                "Testimonials",
            ],
        ]);
    }

    public function indexQuickLink(Request $request)
    {
        return view('home-web-page.home-web-page-quicklink', [
            "header" => [
                "Access App",
                "Terms & Conditions",
                "Privacy Policy",
                "Contact & Support",
                "Careers",
            ],
        ]);
    }
}
