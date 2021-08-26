<?php

namespace App\View\Components;

use Illuminate\View\Component as OrigComponent;

class Component extends OrigComponent
{
    public $is;

    public function __construct($is = null)
    {
        $this->is = $is;
    }
    
    public function render()
    {
        return view('components.'. $this->is);
    }
}
