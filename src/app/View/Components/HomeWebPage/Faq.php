<?php

namespace App\View\Components\HomeWebPage;

use Illuminate\View\Component;

class Faq extends Component
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
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.home-web-page.faq', ['dataSource' => $this->dataFAQ(),]);
    }
}
