<?php

namespace App\View\Components\Layout;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Request;

class Sidebar extends Component
{
    public $activeRoute;

    public function __construct()
    {
        $this->activeRoute = Request::route()->getName();
    }
    
    public function render()
    {
        return view('components.layout.sidebar');
    }
}
