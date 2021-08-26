<?php

namespace App\Services;

use App\Events\BroadcastToast;

class ToastService
{
    /**
     * Send toast information to broadcast event
     *
     * Config options:
     *    string|null  title  Toast title
     *    string|null  icon  (success|info|error)
     *    int|null  timeout  Delay in ms; default if null; none if false
     *    boolean|null  close_button  Show close button
     *    string|null  url  Link to use on click
     *
     * @param  int  $userId
     * @param  string  $message
     * @param  array  $config
     */
    public function broadcast(int $userId, string $message, array $config = [])
    {
        $toast = [
            'message' => $message,
            'title' => isset($config['title']) ? $config['title'] : null,
            'icon' => isset($config['icon']) ? $config['icon'] : null,
            'timeout' => isset($config['timeout']) ? (int) $config['timeout'] : null,
            'close_button' => isset($config['close_button']) ? (int) $config['close_button'] : null,
            'url' => isset($config['url']) ? $config['url'] : null,
        ];

        BroadcastToast::dispatch($userId, $toast);
    }
}
