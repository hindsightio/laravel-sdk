<?php namespace Hindsight\Configuration\Settings;

class RequestLoggingConfig
{
    protected $enabled = false;
    protected $redactFields = [];
    protected $redactHeaders = [];

    public function enable()
    {
        $this->enabled = true;
    }

    public function redactFields(array $fields)
    {
        $this->redactFields = array_merge($this->redactFields, $fields);
    }

    public function redactHeaders(array $headers)
    {
        $this->redactHeaders = array_merge($this->redactHeaders, $headers);
    }
}
