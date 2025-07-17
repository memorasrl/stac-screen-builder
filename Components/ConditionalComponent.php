<?php

require_once __DIR__ . '/BaseComponent.php';

/**
 * Conditional Component - Conditional rendering based on frontend function calls
 * 
 * This component allows conditional rendering of UI elements based on
 * named functions that will be called in Flutter.
 */
class ConditionalComponent extends BaseComponent {
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct('conditionalWidget');
        $this->initializeDefaults();
    }
    
    /**
     * Initialize default properties
     */
    private function initializeDefaults(): void {
        // No default children - they're optional
    }
    
    /**
     * Set the condition to evaluate
     * 
     * This condition will be a function name that will be called on the frontend.
     * Examples:
     * - 'hasProLicense' (calls hasProLicense() function)
     * - 'isUserAdmin' (calls isUserAdmin() function)
     * - 'hasValidSubscription' (calls hasValidSubscription() function)
     * - 'canAccessFeature' (calls canAccessFeature() function)
     * 
     * @param string $functionName Frontend function name to call
     * @return self For method chaining
     */
    public function setCondition(string $functionName): self {
        $this->setProperty('condition', $functionName);
        return $this;
    }
    
    /**
     * Set parameters to pass to the condition function
     * 
     * @param array $params Parameters to pass to the function
     * @return self For method chaining
     */
    public function setConditionParams(array $params): self {
        $this->setProperty('conditionParams', $params);
        return $this;
    }
    
    /**
     * Set condition with parameters in one call
     * 
     * @param string $functionName Frontend function name to call
     * @param array $params Parameters to pass to the function
     * @return self For method chaining
     */
    public function setConditionWithParams(string $functionName, array $params = []): self {
        $this->setCondition($functionName);
        if (!empty($params)) {
            $this->setConditionParams($params);
        }
        return $this;
    }
    
    /**
     * Set the component to show when condition is TRUE
     * 
     * If this is set and condition evaluates to true,
     * this component will be rendered.
     * 
     * @param BaseComponent $child Component to show when true
     * @return self For method chaining
     */
    public function setTrueChild(BaseComponent $child): self {
        $this->setProperty('trueChild', $child->toArray());
        return $this;
    }
    
    /**
     * Set the component to show when condition is FALSE
     * 
     * If this is set and condition evaluates to false,
     * this component will be rendered.
     * 
     * @param BaseComponent $child Component to show when false
     * @return self For method chaining
     */
    public function setFalseChild(BaseComponent $child): self {
        $this->setProperty('falseChild', $child->toArray());
        return $this;
    }
    
    /**
     * Shorthand method to show content only when condition is true
     * 
     * @param string $functionName Frontend function name to call
     * @param BaseComponent $child Component to show when true
     * @return self For method chaining
     */
    public function showWhen(string $functionName, BaseComponent $child): self {
        $this->setCondition($functionName);
        $this->setTrueChild($child);
        return $this;
    }
    
    /**
     * Shorthand method to hide content when condition is true
     * (show only when condition is false)
     * 
     * @param string $functionName Frontend function name to call
     * @param BaseComponent $child Component to show when false
     * @return self For method chaining
     */
    public function hideWhen(string $functionName, BaseComponent $child): self {
        $this->setCondition($functionName);
        $this->setFalseChild($child);
        return $this;
    }
    
    /**
     * Set both true and false children at once
     * 
     * @param string $functionName Frontend function name to call
     * @param BaseComponent $trueChild Component to show when true
     * @param BaseComponent $falseChild Component to show when false
     * @return self For method chaining
     */
    public function setChildren(string $functionName, BaseComponent $trueChild, BaseComponent $falseChild): self {
        $this->setCondition($functionName);
        $this->setTrueChild($trueChild);
        $this->setFalseChild($falseChild);
        return $this;
    }
    
    /**
     * Get the condition function name
     * 
     * @return string|null Function name to call
     */
    public function getCondition(): ?string {
        return $this->getProperty('condition');
    }
    
    /**
     * Check if component has a true child
     * 
     * @return bool True if trueChild is set
     */
    public function hasTrueChild(): bool {
        return $this->getProperty('trueChild') !== null;
    }
    
    /**
     * Check if component has a false child
     * 
     * @return bool True if falseChild is set
     */
    public function hasFalseChild(): bool {
        return $this->getProperty('falseChild') !== null;
    }
    
    /**
     * Validate conditional component configuration
     * 
     * @return array Array of validation errors
     */
    public function validate(): array {
        $errors = parent::validate();
        
        // Validate condition
        $condition = $this->getCondition();
        if (empty($condition)) {
            $errors[] = "Function name is required for conditional component";
        }
        
        // Validate that at least one child is set
        if (!$this->hasTrueChild() && !$this->hasFalseChild()) {
            $errors[] = "At least one child (trueChild or falseChild) must be set";
        }
        
        return $errors;
    }
}

?>
