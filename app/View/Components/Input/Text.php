<?php

namespace App\View\Components\Input;

use Illuminate\View\Component;

class Text extends Component
{
    public $classes;
    public $disabled;

    public function __construct($disabled = false)
    {
        $styles = [
            "common" => "block w-full px-3 py-2 placeholder-gray-400 transition duration-150 ease-in-out border border-gray-300 rounded-md appearance-none focus:outline-none focus-visible:shadow-outline-blue focus-visible:border-blue-300 sm:text-sm sm:leading-5",
            "disabled" => "bg-gray-200 cursor-not-allowed"
        ];

        $this->disabled = $disabled;

        $this->classes = join(" ", [
            $styles["common"],
            $disabled ? $styles["disabled"] : null,
        ]);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.input.text');
    }
}
