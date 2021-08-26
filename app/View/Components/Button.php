<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Button extends Component
{
    public $classes;
    public $disabled;
    public $type;
    protected $colors;
    protected $sizes;
    protected $styles;

    public function __construct($color = "primary", $size = "md", $disabled = false, $square = false, $fullWidth = false, $type = "button")
    {
        $colors = [
            "primary" => "text-white bg-primary-600 hover:bg-primary-500 focus-visible:border-primary-700 focus-visible:shadow-outline-primary active:bg-primary-700",
            "inverse" => "text-primary-600 bg-white hover:text-primary-500 focus-visible:shadow-outline-primary active:bg-primary-50",
            "secondary" => "text-primary-700 bg-primary-100 hover:bg-primary-50 focus-visible:border-primary-300 focus-visible:shadow-outline-primary active:bg-primary-200",
            "white" => "border-gray-300 text-gray-700 bg-white hover:text-gray-500 focus-visible:border-blue-300 focus-visible:shadow-outline-blue active:text-gray-800 active:bg-gray-50"
        ];

        $sizes = [
            "xs" => "px-2.5 py-1.5 text-xs leading-4",
            "sm" => "px-3 py-2 text-sm leading-4",
            "md" => "px-4 py-2 text-sm leading-5",
            "lg" => "px-4 py-2 text-base leading-6",
            "xl" => "px-6 py-3 text-base leading-6"
        ];

        $styles = [
            "common" => "relative inline-flex items-center justify-center border border-transparent font-medium shadow group transition ease-in-out duration-150 focus:outline-none",
            "disabled" => "cursor-not-allowed opacity-50",
            "fullWidth" => "w-full",
            "rounded" => [
                "xs" => "rounded",
                "sm" => "rounded-md",
                "md" => "rounded-md",
                "lg" => "rounded-md",
                "xl" => "rounded-md"
            ]
        ];

        $this->disabled = $disabled;

        $this->type = $type;

        $this->classes = join(" ", [
            $styles["common"],
            $colors[$color],
            $sizes[$size],
            $disabled ? $styles["disabled"] : null,
            $square ? null : $styles["rounded"][$size],
            $fullWidth ? $styles["fullWidth"] : null
        ]);
    }


    public function render()
    {
        return view('components.button');
    }
}
