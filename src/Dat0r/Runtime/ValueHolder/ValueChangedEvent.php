<?php

namespace Dat0r\Runtime\ValueHolder;

use Dat0r\Common\IEvent;
use Dat0r\Runtime\Field\IField;
use Dat0r\Runtime\Document\DocumentChangedEvent;

/**
 * ValueChangedEvent(s) reflect state changes to a document's underlying ValueHolder.
 * These events are fired everytime a document field-value actually changes and can be used
 * to track state changes over time.
 */
class ValueChangedEvent implements IEvent
{
    /**
     * Holds the event's field origin.
     * @var IField
     */
    private $field;

    /**
     * Holds the previous value of our field origin.
     *
     * @var mixed $prev_value
     */
    private $prev_value;

    /**
     * Holds the new value of our field origin.
     *
     * @var mixed $value
     */
    private $value;

    /**
     * Holds the time at which the event was created.
     *
     * @var int $timestamp
     */
    private $timestamp;

    /**
     * Holds a possibly underlying aggrgate's value changed event.
     *
     * @var DocumentChangedEvent $aggregate_event
     */
    private $aggregate_event;

    /**
     * Constructs a new ValueChangedEvent instance.
     *
     * @param IField $field
     * @param mixed $prev_value
     * @param mixed $value
     * @param DocumentChangedEvent $aggregate_event If the origin field is an aggregate, the bubbled event is passed
     */
    public static function create(IField $field, $prev_value, $value, DocumentChangedEvent $aggregate_event = null)
    {
        return new static($field, $prev_value, $value, $aggregate_event);
    }

    /**
     * Constructs a new ValueChangedEvent instance.
     *
     * @param IField $field
     * @param mixed $prev_value
     * @param mixed $value
     * @param DocumentChangedEvent $aggregate_event If the origin field is an aggregate, the bubbled event is passed
     */
    protected function __construct(IField $field, $prev_value, $value, DocumentChangedEvent $aggregate_event = null)
    {
        $this->field = $field;
        $this->timestamp = \time();
        $this->prev_value = $prev_value;
        $this->value = $value;
        $this->aggregate_event = $aggregate_event;
    }

    /**
     * Returns the event's affected field.
     *
     * @return IField
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * Returns the previous value of the event's related field.
     *
     * @return IValueHolder
     */
    public function getOldValue()
    {
        return $this->prev_value;
    }

    /**
     * Returns the new value of the event's related field.
     *
     * @return IValueHolder
     */
    public function getNewValue()
    {
        return $this->value;
    }

    /**
     * Returns the event's creation time as a unix timestamp.
     *
     * @return int
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * If the originating field is an aggregate field,
     * this method returns an aggregated document's underlying value changed event.
     *
     * @return ValueChangedEvent
     */
    public function getAggregateEvent()
    {
        return $this->aggregate_event;
    }

    /**
     * Returns a string representation of the current event.
     *
     * @return string
     */
    public function __toString()
    {
        $string_representation = sprintf(
            "The `%s` field's value changed from '%s' to '%s'",
            $this->getField()->getName(),
            $this->getOldValue(),
            $this->getNewValue()
        );

        if (($aggregate_event = $this->getAggregateEvent())) {
            $string_representation .= PHP_EOL . "The actual changed occured upon the field's aggregate though.";
            $string_representation .= PHP_EOL . $aggregate_event;
        }

        return $string_representation;
    }
}
