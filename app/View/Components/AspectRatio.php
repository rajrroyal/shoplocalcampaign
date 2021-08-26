<?php

namespace App\View\Components;

use Illuminate\View\Component;

class AspectRatio extends Component
{
    public $ratioClass;

    public function __construct($ratio = "1:1")
    {
        $classes = [
            '1:1' => 'aspect-ratio-1/1',
            '4:3' => 'aspect-ratio-4/3',
            '16:9' => 'aspect-ratio-16/9',
            '2:1' => 'aspect-ratio-2/1',
            '21:9' => 'aspect-ratio-21/9'
        ];

        $this->ratioClass = $classes[$ratio];
    }
    
    public function render()
    {
        return view('components.aspect-ratio');
    }
}
