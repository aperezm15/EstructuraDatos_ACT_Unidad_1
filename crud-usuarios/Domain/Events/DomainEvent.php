<?php

abstract class DomainEvent {

    private $eventName;
    private $occurredOn;

    public function __construct($eventName) 
    {
        $this->eventName = $eventName;
        $this->occurredOn = date("Y-m-d H:i:s");
    }

    public function eventName() {
        return $this->eventName;
    }
    public function occurredOn() {
        return $this->occurredOn;
    }

    abstract public function payload();
}