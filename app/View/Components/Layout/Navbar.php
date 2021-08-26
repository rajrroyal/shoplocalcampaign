<?php

namespace App\View\Components\Layout;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Request;

class Navbar extends Component
{
    public $hasSidebar;
    public $activeRoute;

    public function __construct(bool $hasSidebar = false)
    {
        $this->hasSidebar = $hasSidebar;

        $this->activeRoute = Request::route()->getName();
    }
    
    public function render()
    {
        return view('components.layout.navbar');
    }
}
