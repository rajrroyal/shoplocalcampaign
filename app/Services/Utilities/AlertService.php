<?php
namespace App\Services\Utilities;

/**
 * Class AlertService
 *
 * @package App\Services\Utilities
 */
class AlertService
{
    public function success(string $message)
    {
        $this->alert($message, 'success');
    }

    public function warning(string $message)
    {
        $this->alert($message, 'warning');
    }

    public function error(string $message)
    {
        $this->alert($message, 'error');
    }

    // Alias for error()
    public function failure(string $message)
    {
        $this->error($message);
    }

    public function info(string $message)
    {
        $this->alert($message, 'info');
    }

    public function alert(string $message, string $type = null)
    {
        session()->flash('alert', [
            'message' => $message,
            'type' => $type
        ]);
    }

    // ---------------------------------------------------------------------

    public function render(string $format = 'default')
    {
        if (session()->has('alert')) {
            switch($format) {
                default: return $this->renderAlert();
            }
        }

        return null;
    }

    // ---------------------------------------------------------------------

    protected function renderAlert()
    {
        $alert = session()->get('alert');

        $bg = [
            "success" => "bg-green-100 border-green-400",
            "info" => "bg-blue-100 border-blue-400",
            "warning" => "bg-yellow-100 border-yellow-400",
            "error" => "bg-red-100 border-red-400"
        ];

        $text = [
            "success" => "text-green-800",
            "info" => "text-blue-800",
            "warning" => "text-yellow-800",
            "error" => "text-red-800"
        ];

        $icon = [
            "success" => '<svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>',
            "info" => '<svg class="w-5 h-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>',
            "warning" => '<svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>',
            "error" => '<svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>'
        ];

        $html = '<div class="p-6 mb-2 border-l-4 '. $bg[$alert['type']] .'">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            '. $icon[$alert['type']] .'
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium leading-5 '. $text[$alert['type']] .'">
                                '. $alert['message'] .'
                            </h3>
                        </div>
                    </div>
                </div>'
        ;

        return $html;
    }
}
