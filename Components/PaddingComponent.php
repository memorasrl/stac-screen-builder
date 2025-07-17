<?php

require_once __DIR__ . '/BaseComponent.php';

/**
 * Padding Component - Flutter Padding widget
 * 
 * This component represents Flutter Padding widget that adds padding
 * around its child component. It supports both uniform padding and
 * edge-specific padding (left, top, right, bottom).
 */
class PaddingComponent extends BaseComponent {
    
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct('padding');
        $this->initializeDefaults();
    }
    
    /**
     * Initialize default properties
     */
    private function initializeDefaults(): void {
        // Set default padding to 0
        $this->setPadding(0);
    }
    
    /**
     * Set padding value
     * 
     * @param int|array $padding Padding value - can be:
     *                          - int: uniform padding for all sides
     *                          - array: specific padding with keys 'left', 'top', 'right', 'bottom'
     * @return self For method chaining
     * @throws InvalidArgumentException If padding format is invalid
     */
    public function setPadding($padding): self {
        if (is_int($padding)) {
            // Uniform padding
            $this->setProperty('padding', $padding);
        } elseif (is_array($padding)) {
            // Edge-specific padding
            $this->validatePaddingArray($padding);
            $this->setProperty('padding', $padding);
        } else {
            throw new InvalidArgumentException("Padding must be an integer or an array");
        }
        
        return $this;
    }
    
    /**
     * Get padding value
     * 
     * @return int|array Current padding value
     */
    public function getPadding() {
        return $this->getProperty('padding', 0);
    }
    
    /**
     * Set padding for specific edge
     * 
     * @param string $edge Edge name ('left', 'top', 'right', 'bottom')
     * @param int $value Padding value
     * @return self For method chaining
     * @throws InvalidArgumentException If edge is invalid
     */
    public function setPaddingEdge(string $edge, int $value): self {
        $validEdges = ['left', 'top', 'right', 'bottom'];
        
        if (!in_array($edge, $validEdges)) {
            throw new InvalidArgumentException("Invalid edge: {$edge}. Valid edges are: " . implode(', ', $validEdges));
        }
        
        $currentPadding = $this->getProperty('padding', []);
        
        // Convert to array if it's currently an integer
        if (is_int($currentPadding)) {
            $currentPadding = [];
        }
        
        $currentPadding[$edge] = $value;
        $this->setProperty('padding', $currentPadding);
        
        return $this;
    }
    
    /**
     * Get padding for specific edge
     * 
     * @param string $edge Edge name ('left', 'top', 'right', 'bottom')
     * @return int|null Padding value for the edge or null if not set
     */
    public function getPaddingEdge(string $edge): ?int {
        $padding = $this->getProperty('padding', []);
        
        if (is_int($padding)) {
            return $padding; // Uniform padding
        }
        
        return $padding[$edge] ?? null;
    }
    
    /**
     * Set left padding
     * 
     * @param int $value Left padding value
     * @return self For method chaining
     */
    public function setLeft(int $value): self {
        return $this->setPaddingEdge('left', $value);
    }
    
    /**
     * Set top padding
     * 
     * @param int $value Top padding value
     * @return self For method chaining
     */
    public function setTop(int $value): self {
        return $this->setPaddingEdge('top', $value);
    }
    
    /**
     * Set right padding
     * 
     * @param int $value Right padding value
     * @return self For method chaining
     */
    public function setRight(int $value): self {
        return $this->setPaddingEdge('right', $value);
    }
    
    /**
     * Set bottom padding
     * 
     * @param int $value Bottom padding value
     * @return self For method chaining
     */
    public function setBottom(int $value): self {
        return $this->setPaddingEdge('bottom', $value);
    }

    /**
     * Set horizontal padding
     * 
     * @param int $value Horizontal padding value
     * @return self For method chaining
     */
    public function setHorizontal(int $value): self {
        return $this->setPaddingEdge('left', $value)->setPaddingEdge('right', $value);
    }

    /**
     * Set vertical padding
     * 
     * @param int $value Vertical padding value
     * @return self For method chaining
     */
    public function setVertical(int $value): self {
        return $this->setPaddingEdge('top', $value)->setPaddingEdge('bottom', $value);
    }
    
    /**
     * Validate padding array format
     * 
     * @param array $padding Padding array to validate
     * @throws InvalidArgumentException If padding array is invalid
     */
    private function validatePaddingArray(array $padding): void {
        $validKeys = ['left', 'top', 'right', 'bottom'];
        
        foreach ($padding as $key => $value) {
            if (!in_array($key, $validKeys)) {
                throw new InvalidArgumentException("Invalid padding key: {$key}. Valid keys are: " . implode(', ', $validKeys));
            }
            
            if (!is_int($value) || $value < 0) {
                throw new InvalidArgumentException("Padding value for '{$key}' must be a non-negative integer");
            }
        }
    }
    
    /**
     * Validate padding component configuration
     * 
     * @return array Array of validation errors
     */
    public function validate(): array {
        $errors = parent::validate();
        
        $padding = $this->getProperty('padding');
        
        if ($padding === null) {
            $errors[] = "Padding value is required";
        } elseif (is_int($padding) && $padding < 0) {
            $errors[] = "Padding value must be non-negative";
        } elseif (is_array($padding)) {
            try {
                $this->validatePaddingArray($padding);
            } catch (InvalidArgumentException $e) {
                $errors[] = $e->getMessage();
            }
        }
        
        return $errors;
    }
}

?>
