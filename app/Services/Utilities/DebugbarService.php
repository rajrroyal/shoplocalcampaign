<?php

namespace App\Services\Utilities;

use Debugbar;

class DebugbarService
{
    /**
     * Output each of the provided variables to debugbar
     * Wrap each of the variables in a simple object to preserve the capitalization of the variables names
     *
     * @param  array  $variables  Associative array of variables to dump
     */
    public function dump(array $variables)
    {
        // Verify Debugbar is installed
        if (! class_exists('Debugbar')) {
            return;
        }

        // Verify there are variables to output
        if (! sizeof($variables)) { return; }

        foreach($variables as $key=>$value)
        {
            // Output the variables to Debugbar as objects with a key equal to the original variable
            // name (since the new Debugbar capitalizes captions now)
            $data = new \stdClass();
            $data->$key = $value;
            Debugbar::addMessage($data, $key);
        }
    }

    /**
     * Disable debugbar for the current route (ie: for api json responses)
     */
    public function disable()
    {
        // Verify Debugbar is installed
        if (! class_exists('Debugbar')) {
            return;
        }

        app('debugbar')->disable();
    }
}
