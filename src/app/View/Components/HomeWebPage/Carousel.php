<?php

namespace App\View\Components\HomeWebPage;

use Illuminate\Support\Arr;
use Illuminate\View\Component;

class Carousel extends Component
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

    private function dataCarousel()
    {
        $x = Arr::getFileListFromDisk('/images/homepage-what-it-does');
        $contents = [
            [
                "title" => "Production of Modules",
                "description" => "Begin with our intuitive platform to design your modular units. Our system integrates seamlessly with manufacturing workflows, ensuring that your designs are efficiently transformed into high-quality prefabricated modules."
            ],
            [
                "title" => "Comprehensive Quality Assurance and Control",
                "description" => "Leverage our detailed QAQC checklists to maintain high standards throughout the production process. Each checklist is customizable to fit your project's specific needs."
            ],
            [
                "title" => "Health, Safety, and Environment (HSE)",
                "description" => "Our app includes dedicated features to manage HSE compliance in the factory setting. Track safety incidents, manage risk assessments, and ensure environmental regulations are met, all within a centralized platform."
            ],
            [
                "title" => "Environmental, Social, and Governance (ESG)",
                "description" => "Showcase your company’s commitment to sustainability and governance with our ESG reporting tools. Track your environmental impact, social contributions, and governance practices, enhancing transparency and accountability."
            ],
            [
                "title" => "Efficient Logistics and Tracking",
                "description" => "Organize and monitor the shipping of your modular units from factory to site. Our shipping management tools provide you with detailed logistics planning, real-time tracking, and notifications to ensure a smooth delivery process."
            ],
            [
                "title" => "Seamless On-Site Assembly",
                "description" => "Once the modules arrive on-site, our app guides you through the assembly process. Access detailed assembly instructions, manage your on-site workforce, and update project timelines, all designed to expedite construction and reduce time to occupancy."
            ],
        ];
        return [
            "images" => $x,
            "contents" => $contents
        ];
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $dataSource = $this->dataCarousel();
        return view('components.home-web-page.carousel', [
            "images" => $dataSource['images'],
            "contents" => $dataSource['contents'],
        ]);
    }
}
