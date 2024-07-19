<?php

namespace App\View\Components\HomeWebPage;

use Illuminate\View\Component;

class Testimonial extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    private function dataTestimonial()
    {
        return [
            [
                "title" => "Transforming Our Projects from Day One",
                "content" => "From the moment we integrated " . env("APP_NAME") . "into our operations, we saw a dramatic shift in how we manage projects. The real-time insights and comprehensive modules have made us more efficient and proactive. A game-changer for the industry!",
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
                "content" => "Managing travel claims and expenses used to be a nightmare. " . env("APP_NAME") . " has not only simplified the process but also brought transparency and accountability to our expense management. It's impressive how much easier our lives have become.",
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
                "content" => "Health, safety, and environment are non-negotiable for us. " . env("APP_NAME") . "'s HSE module ensures that we're always compliant with regulations and our safety standards. The peace of mind it brings is invaluable.",
                "owner" => "Carlos Espinoza, HSE Manager, SafeBuild Ventures",
                "rating" => 5,
            ],
            [
                "title" => "A Partner in Project Success",
                "content" => "This software has become more than just a tool; it's a partner in our project's success. The Project Management module has streamlined our workflows, ensuring that every project is delivered on time and within budget. " . env("APP_NAME") . " is the future of construction management.",
                "owner" => "Emily Wang, CEO, Urban Innovations",
                "rating" => 5,
            ],
        ];
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.home-web-page.testimonial', [
            'dataSource' => $this->dataTestimonial(),
        ]);
    }
}
