<?php namespace Hindsight\Support;

trait EventEmitter
{
    protected $events = [];

    public function on(string $event, callable $callback): self
    {
        if (!array_key_exists($event, $this->events)) {
            $this->events[$event] = [];
        }
        array_push($this->events[$event], $callback);

        return $this;
    }

    public function fire(string $event, ... $args): self
    {
        if (array_key_exists($event, $this->events)) {
            foreach ($this->events[$event] as $callback) {
                call_user_func_array($callback, $args);
            }
        }

        return $this;
    }
}
