<?php

/**
 * Interface for components that can be serialized to JSON
 * 
 * This interface ensures that all UI components can be converted
 * to JSON format for consumption by Flutter applications via REST API
 */
interface SerializableInterface {
    /**
     * Convert the component to JSON string
     * 
     * @return string JSON representation of the component
     */
    public function toJson(): string;
    
    /**
     * Convert the component to associative array
     * 
     * @return array Array representation of the component
     */
    public function toArray(): array;
}

?>
