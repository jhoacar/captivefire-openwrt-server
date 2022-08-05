<?php

namespace App\Utils;

class CurlResponse
{
    /**
     * @var bool|string
     */
    public $data = '';

    /**
     * @var int
     */
    public $status = 0;

    /**
     * Construct with default values.
     */
    public function __construct()
    {
        $this->data = '';
        $this->status = 0;
    }
}
