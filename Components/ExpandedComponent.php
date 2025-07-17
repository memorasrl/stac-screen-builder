<?php

require_once __DIR__ . '/BaseComponent.php';

/**
 * Expanded Component - Flutter Expanded widget
 * 
 * This component represents Flutter Expanded widget that expands
 * a child of a Row, Column, or Flex so that the child fills the 
 * available space along the main axis.
 */
class ExpandedComponent extends BaseComponent {
    
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct('expanded');
        $this->initializeDefaults();
    }
    
    /**
     * Initialize default properties
     */
    private function initializeDefaults(): void {
        // Set default flex to 1
        $this->setFlex(1);
    }
    
    /**
     * Set flex value
     * 
     * @param int $flex Flex value (how much space this child should take)
     * @return self For method chaining
     * @throws InvalidArgumentException If flex is not a positive integer
     */
    public function setFlex(int $flex): self {
        if ($flex <= 0) {
            throw new InvalidArgumentException("Flex must be a positive integer");
        }
        
        $this->setProperty('flex', $flex);
        return $this;
    }
    
    /**
     * Get flex value
     * 
     * @return int Current flex value
     */
    public function getFlex(): int {
        return $this->getProperty('flex', 1);
    }
    
    /**
     * Validate expanded component configuration
     * 
     * @return array Array of validation errors
     */
    public function validate(): array {
        $errors = parent::validate();
        
        // Validate flex value
        $flex = $this->getProperty('flex');
        if ($flex === null || !is_int($flex) || $flex <= 0) {
            $errors[] = "Flex must be a positive integer";
        }
        
        return $errors;
    }
}

?>
