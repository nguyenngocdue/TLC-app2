<?php

namespace App\Http\Controllers\LandingPage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class LandingPageController extends Controller
{
    public function getType()
    {
        return "landing-page";
    }

    

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        
        return view('landing-page.landing-page',$this->getDataSource());
    }
    private function getDataSource(){
        return [
            "video" => $this->dataVideo(),
            "carousel" => $this->dataCarousel(),
            "testimonial" => $this->dataTestimonial(),
            "team" => $this->dataTeam(),
        ];
    }
    private function dataVideo(){
        return [
            "title" => "Click on the video for an overview",
            "iframe" => '<iframe width="1154" height="649" src="https://www.youtube.com/embed/xyYl0XRhUSg" title="Northcote Elevation Apartments, Auckland NZ. Modular Installtion by TLC Modular" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>'
        ];
    }
    
    private function dataCarousel(){
        return app()->backgroundImage();
    }
    private function dataTeam(){
        $images = $this->getUrlImages();
        $arrayText = [
            'Production Manager',
            'HSE Manager',
            'QAQC Manager',
            'Construction Inspector',
            'Vendor',
            'Contractor',
            'Production Admin',
            'HR Manager',
            'Design Manager',
            'Finance Manager',
            'Administrator Manager',
            'Compliance Manager'
        ];
        $results = [];
        foreach ($images as $key => $value) {
            $results[] = [
                "src" => $value,
                "name" => $arrayText[$key],
            ];
        }
        return $results;
    }

    private function getUrlImages(){
        $directory = public_path('/images/landing-page');
        $path = asset('/images/landing-page');
        if (File::isDirectory($directory)) {
            $files = File::files($directory);
            return array_map( function ($file) use($path) {
                return $path . '/'. $file->getFilename();
            },$files);
        } 
        return [];
    }
    private function dataTestimonial(){
        return[
            [
                "title" => "Transforming Our Projects from Day One",
                "content" => "From the moment we integrated [Your Software Name] into our operations, we saw a dramatic shift in how we manage projects. The real-time insights and comprehensive modules have made us more efficient and proactive. A game-changer for the industry!",
                "owner" => "Alex Johnson, Project Manager, BuildRight Construction",
                "rating" => 5,
            ],
            [
                "title" => "Quality and Compliance Made Easy",
                "content" => "The QA/QC module has revolutionized our approach to quality control. With easy-to-use checklists and automated reports, we're not just meeting our quality standards; we're setting new ones.",
                "owner" => "Sarah Lee, Quality Assurance Specialist, GreenBuild Developers",
                "rating" => 4,
            ],
            [
                "title" => "Every Detail at Your Fingertips",
                "content" => "Managing travel claims and expenses used to be a nightmare. [Your Software Name] has not only simplified the process but also brought transparency and accountability to our expense management. It's impressive how much easier our lives have become.",
                "owner" => "Mike Rodriguez, Operations Director, HighRise Solutions",
                "rating" => 5,
            ],
            [
                "title" => "Sustainability Goals Within Reach",
                "content" => "The ESG module has been instrumental in helping us track and achieve our sustainability goals. It's empowering to see the tangible impact our projects have on the environment and the community, all managed through this incredible platform.",
                "owner" => "Jessica Thompson, Sustainability Officer, EcoConstruct",
                "rating" => 4,
            ],
            [
                "title" => "Safety First, Always",
                "content" => "Health, safety, and environment are non-negotiable for us. [Your Software Name]'s HSE module ensures that we're always compliant with regulations and our safety standards. The peace of mind it brings is invaluable.",
                "owner" => "Carlos Espinoza, HSE Manager, SafeBuild Ventures",
                "rating" => 5,
            ],
            [
                "title" => "A Partner in Project Success",
                "content" => "This software has become more than just a tool; it's a partner in our project's success. The Project Management module has streamlined our workflows, ensuring that every project is delivered on time and within budget. [Your Software Name] is the future of construction management.",
                "owner" => "Emily Wang, CEO, Urban Innovations",
                "rating" => 5,
            ],
        ] ;
    }
}
