<?php

require_once __DIR__ . '/BaseComponent.php';

/**
 * Center Component - Centers a child widget
 * 
 * This component mimics Flutter's Center widget, which centers its child
 * within the available space. It requires no additional parameters.
 */
class CenterComponent extends BaseComponent {
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct('center');
    }
    
    /**
     * Validate the component
     * 
     * @return array Array of validation errors
     */
    public function validate(): array {
        $errors = parent::validate();
        
        $children = $this->getChildren();
        if (count($children) > 1) {
            $errors[] = 'Center component can only have one child';
        }
        
        return $errors;
    }
}

?>
