<?php

namespace App\Services\Utilities;

class JsonService
{
    const OBJECT_FORMAT = 0;
    const ARRAY_FORMAT = 1;

    /**
     * Return a success structure in JSON format
     *
     * @param  any  $data  Data to be returned
     * @return string
     */
    public function success($data = null)
    {
        $content = ([
            'status' => 200,
            'success' => true,
        ]);

        if ($data) {
            $content['data'] = $data;
        }

        return json_encode($content);
    }

    /**
     * Return an error structure in JSON format
     *
     * @param  string  $message  Error message to return
     * @param  any  $data  Any supporting data to return
     * @param  int  $status  HTTP status code
     * @return string
     */
    public function error(string $message, $data = null, $status = 500)
    {
        $content = ([
            'status' => $status,
            'success' => false,
            'data' => array_merge(['message' => $message], $data)
        ]);

        return json_encode($content);
    }

    /**
     * Alias for error()
     *
     * @param  string  $message  Error message to return
     * @param  any  $data  Any supporting data to return
     * @param  int  $status  HTTP status code
     * @return string
     */
    public function failure(string $message, $data = null, $status = 500)
    {
        return $this->error($message, $data, $status);
    }

    /**
     * Decode a JSON string
     *
     * @param  string  $json  JSON string to decode
     * @param  int  $format  0 = as object, 1 = as array
     * @return array|object
     */
    public function decode(string $json, int $format = self::OBJECT_FORMAT)
    {
        $data = json_decode($json, $format);

        if (! $error = $this->jsonError()) {
            $result = [
                'success' => true,
                'data' => $data
            ];
        } else {
            $result = [
                'success' => false,
                'error' => $error
            ];
        }

        return $format == self::OBJECT_FORMAT ? (object) $result : $result;
    }

    // ---------------------------------------------------------------------

    /**
     * Return the error message for the last JSON decode (if any)
     *
     * @return string|null
     */
    protected function jsonError()
    {
        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                return null;
            case JSON_ERROR_DEPTH:
                return 'Maximum stack depth exceeded';
            case JSON_ERROR_STATE_MISMATCH:
                return 'Underflow or the modes mismatch';
            case JSON_ERROR_CTRL_CHAR:
                return 'Unexpected control character found';
            case JSON_ERROR_SYNTAX:
                return 'Syntax error, malformed JSON';
            case JSON_ERROR_UTF8:
                return 'Malformed UTF-8 characters, possibly incorrectly encoded';
            default:
                return 'Unknown error';
        }

        return null;
    }
}
