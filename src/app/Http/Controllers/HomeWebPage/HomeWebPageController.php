<?php

namespace App\Http\Controllers\HomeWebPage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class HomeWebPageController extends Controller
{
    public function getType()
    {
        return "home-web-page";
    }



    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {

        return view('home-web-page.home-web-page', $this->getDataSource());
    }
    private function getDataSource()
    {
        return [
            "header" => $this->dataHeader(),
            "video" => $this->dataVideo(),
            "carousel" => $this->dataCarousel(),
            "testimonial" => $this->dataTestimonial(),
            "team" => $this->dataTeam(),
            "faq" => $this->dataFaq(),
        ];
    }
    private function dataVideo()
    {
        $link = "https://www.youtube.com/embed/ak69h2gPs9I";
        return [
            "title" => "Click on the video for an overview",
            "iframe" => '<iframe width="1154" height="649" src="' . $link . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>'
        ];
    }

    private function dataCarousel()
    {
        // $x = app()->backgroundImage();
        $x = $this->getUrlImages('/images/homepage-what-it-does');
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
    private function dataTeam()
    {
        $images = $this->getUrlImages('/images/homepage-who-use-it');
        $arrayText = [
            'Health and Safety',
            'Manufacturing',
            'Quality Control',
            'Construction Site',
            'Document Control',
            'HR and Admin',
            'Technical',
            'Finance',
            'Project Management',
            'Supply Chain',
            'Compliance',
            'Consultant',
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

    private function getUrlImages($path)
    {
        $directory = public_path($path);
        $path = asset($path);
        if (File::isDirectory($directory)) {
            $files = File::files($directory);
            return array_map(function ($file) use ($path) {
                return $path . '/' . $file->getFilename();
            }, $files);
        }
        return [];
    }
    private function dataHeader()
    {
        return [
            "What It Does",
            "What You Get",
            "Why It's Cool",
            "Who Use It",
            "Testimonials",
            "FAQs",
            "Overview Video",
        ];
    }
    private function dataFAQ()
    {
        return [
            [
                "title" => "1. What is " . env("APP_NAME") . " and how does it help in construction management?",
                "content" => "" . env("APP_NAME") . " is a comprehensive modular construction software designed to streamline and optimize every phase of construction project management. It offers a suite of modules including QA/QC, Project Management, Production, Travel Claim, ESG, and HSE, facilitating better planning, execution, and monitoring of construction projects.",
            ],
            [
                "title" => "2. Can I customize the software to fit the specific needs of my project?",
                "content" => "Yes, our software is modular, meaning you can choose and customize the modules that are most relevant to your project's needs. This flexibility allows you to tailor the software to meet your specific requirements, ensuring efficient management of your construction projects."
            ],
            [
                "title" => "3. How does the Project Management module enhance project execution?",
                "content" => "The Project Management module provides tools for planning, scheduling, resource allocation, and budget management, enabling you to track project progress in real-time, identify potential bottlenecks early, and make informed decisions to keep your project on track and within budget."
            ],

            [
                "title" => "4. What features does the QA/QC module offer?",
                "content" => "Our QA/QC module offers a range of features designed to maintain the highest standards of quality and compliance, including customizable checklists, automated defect tracking, and detailed reports. This ensures that quality control processes are seamlessly integrated into every stage of your construction project."
            ],

            [
                "title" => "5. Is the software suitable for managing large-scale construction projects?",
                "content" => "Absolutely. " . env("APP_NAME") . " is designed to scale with your projects, from small builds to large-scale developments. Its robust architecture and comprehensive feature set make it ideal for managing complex construction projects efficiently."
            ],

            [
                "title" => "6. How does the software support sustainability and ESG compliance?",
                "content" => "The ESG module helps you monitor and manage the environmental, social, and governance aspects of your construction projects. It enables you to track your project's environmental impact, implement sustainable practices, and ensure compliance with relevant ESG standards and regulations."
            ],

            [
                "title" => "7. What kind of support can I expect after purchasing the software?",
                "content" => "We offer comprehensive support to all our customers, including onboarding, training sessions, and ongoing technical support. Our dedicated support team is available to ensure you maximize the benefits of our software for your construction projects."
            ],

            [
                "title" => "8. How secure is the data in " . env("APP_NAME") . "?",
                "content" => "Data security is a top priority for us. Our software employs advanced encryption, secure data storage solutions, and regular security updates to protect your data and ensure that it is accessible only to authorized users."
            ],

            [
                "title" => "9. Can the software integrate with other tools and systems?",
                "content" => "Yes, " . env("APP_NAME") . " is designed with integration in mind. It can seamlessly connect with various external tools and systems, enhancing your workflow and ensuring smooth data exchange across your project management ecosystem."
            ],

            [
                "title" => "10. How do I get started with " . env("APP_NAME") . "?",
                "content" => "Getting started is easy! Contact us to schedule a demo or sign up directly through our website. Our team will guide you through the setup process, help you select and customize the modules you need, and provide training to ensure you get the most out of your software."
            ],

        ];
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
}
