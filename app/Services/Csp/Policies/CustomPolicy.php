<?php

namespace App\Services\Csp\Policies;

use Spatie\Csp\Directive;
use Spatie\Csp\Policies\Basic;

class CustomPolicy extends Basic
{
    public function configure()
    {
        parent::configure();
        
        $this->addDirective(Directive::SCRIPT, [
                'unsafe-eval', 
                'https://www.googletagmanager.com/'
            ])
            ->addDirective(Directive::IMG, [
                'data:',
                'https://www.google-analytics.com/'
            ])
            ->addDirective(Directive::FONT, 'self data:');
    }
}