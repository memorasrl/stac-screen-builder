<?php

/**
 * Interface for component building operations
 * 
 * This interface defines the contract for building UI components
 * that are compatible with Flutter widget system
 */
interface BuildableInterface {
    /**
     * Build the component with its current configuration
     * 
     * @return array The built component configuration
     */
    public function build(): array;
    
    /**
     * Validate the component configuration
     * 
     * @return array Array of validation errors (empty if valid)
     */
    public function validate(): array;
}

?>
