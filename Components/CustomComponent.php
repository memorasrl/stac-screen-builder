<?php

require_once __DIR__ . '/BaseComponent.php';

/**
 * Custom Component - Generic component for custom widget types
 * 
 * This component allows creating custom widgets with any type and properties.
 * It's useful for creating widgets that are not yet implemented as specific components.
 */
class CustomComponent extends BaseComponent {
    
    /**
     * Constructor
     * 
     * @param string $customType Custom widget type
     * @param bool $canHaveChildren Whether this component can have children
     */
    public function __construct(string $customType, bool $canHaveChildren = false) {
        parent::__construct($customType, $canHaveChildren);
        $this->initializeDefaults();
    }
    
    /**
     * Initialize default properties
     */
    private function initializeDefaults(): void {
        // No default properties for custom components
    }

    /**
     * Validate custom component configuration
     * 
     * @return array Array of validation errors
     */
    public function validate(): array {
        $errors = parent::validate();
        
        // Custom components have minimal validation
        // Type is required (set in constructor)
        if (empty($this->type)) {
            $errors[] = "Custom component type is required";
        }
        
        return $errors;
    }
}

?>
