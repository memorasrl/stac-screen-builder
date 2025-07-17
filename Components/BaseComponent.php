<?php

require_once __DIR__ . '/../Interfaces/SerializableInterface.php';
require_once __DIR__ . '/../Interfaces/BuildableInterface.php';
require_once __DIR__ . '/../Interfaces/ContainerInterface.php';

/**
 * Base class for all UI components
 * 
 * This abstract class provides the foundation for all Flutter-compatible
 * UI components that can be serialized to JSON and consumed by Flutter apps.
 */
abstract class BaseComponent implements SerializableInterface, BuildableInterface, ContainerInterface {
    /**
     * @var string Component type (maps to Flutter widget type)
     */
    protected string $type;
    
    /**
     * @var array Component properties (Flutter widget properties)
     */
    protected array $properties = [];
    
    /**
     * @var array Child components (for container widgets)
     */
    protected array $children = [];
    
    /**
     * @var BaseComponent|null Reference to parent component
     */
    protected ?BaseComponent $parent = null;

    /**
     * @var bool Indicates if this component can have children
     * 
     * This is used to determine if the component can contain other components
     */
    private $canHaveChildren = true;
    
    /**
     * Constructor
     * 
     * @param string $id Unique identifier for this component
     * @param string $type Component type (Flutter widget type)
     */
    public function __construct(string $type, bool $canHaveChildren = false) {
        $this->type = $type;
        $this->canHaveChildren = $canHaveChildren;
    }
    
    /**
     * Get component type
     * 
     * @return string Component type
     */
    public function getType(): string {
        return $this->type;
    }
    
    /**
     * Set a property value
     * 
     * Properties are Flutter widget properties like text, color, etc.
     * Supports dot notation for nested properties (e.g., "style.fontSize")
     * 
     * @param string $key Property key (supports dot notation)
     * @param mixed $value Property value
     * @return self For method chaining
     */
    public function setProperty(string $key, $value): self {
        if ($value === null || $value === '') {
            return $this;
        }
        
        if (strpos($key, '.') !== false) {
            $keys = explode('.', $key);
            $current = &$this->properties;
            
            foreach ($keys as $k) {
                if (!isset($current[$k]) || !is_array($current[$k])) {
                    $current[$k] = [];
                }
                $current = &$current[$k];
            }
            
            $current = $value;
        } else {
            // If the property already exists and both old and new values are arrays, merge them
            if (isset($this->properties[$key]) && is_array($this->properties[$key]) && is_array($value)) {
                $this->properties[$key] = array_merge($this->properties[$key], $value);
            } else {
                $this->properties[$key] = $value;
            }
        }
        
        return $this;
    }
    
    /**
     * Get a property value
     * 
     * Supports dot notation for nested properties (e.g., "style.fontSize")
     * 
     * @param string $key Property key (supports dot notation)
     * @param mixed $default Default value if property not found
     * @return mixed Property value or default
     */
    public function getProperty(string $key, $default = null) {
        if (strpos($key, '.') !== false) {
            $keys = explode('.', $key);
            $current = $this->properties;
            
            foreach ($keys as $k) {
                if (!isset($current[$k])) {
                    return $default;
                }
                $current = $current[$k];
            }
            
            return $current;
        } else {
            return $this->properties[$key] ?? $default;
        }
    }
    
    /**
     * Get all properties
     * 
     * @return array All component properties
     */
    public function getProperties(): array {
        return $this->properties;
    }
    
    /**
     * Set multiple properties at once
     * 
     * @param array $properties Associative array of properties
     * @return self For method chaining
     */
    public function setProperties(array $properties): self {
        foreach ($properties as $key => $value) {
            $this->setProperty($key, $value);
        }
        return $this;
    }
    
    /**
     * Set multiple styles at once
     * 
     * @param array $styles Associative array of styles
     * @return self For method chaining
     */
    public function setStyles(array $styles): self {
        foreach ($styles as $key => $value) {
            $this->setStyle($key, $value);
        }
        return $this;
    }
    
    /**
     * Add a child component
     * 
     * @param BaseComponent $child Child component to add
     * @return self For method chaining
     */
    public function addChild(BaseComponent $child): self {
        $child->parent = $this;
        $this->children[] = $child;
        return $this;
    }

    /**
     * Add multiple child components
     * 
     * @param array $children Array of child components to add
     * @return self For method chaining
     * @throws InvalidArgumentException If any child is not a BaseComponent
     */
    public function addChildren(array $children): self {
        foreach ($children as $child) {
            if ($child instanceof BaseComponent) {
                $child->parent = $this;
                $this->children[] = $child;
            } else {
                throw new InvalidArgumentException("Child must be an instance of BaseComponent");
            }
        }
        return $this;
    }
    
    /**
     * Get all child components
     * 
     * @return array Array of child components
     */
    public function getChildren(): array {
        return $this->children;
    }
    
    /**
     * Get parent component
     * 
     * @return BaseComponent|null Parent component or null if root
     */
    public function getParent(): ?BaseComponent {
        return $this->parent;
    }
    
    /**
     * Convert component to array representation
     * 
     * This method creates a Flutter-compatible array structure
     * that can be consumed by Flutter applications
     * 
     * @return array Array representation of component
     */
    public function toArray(): array {
        $obj = [
            'type' => $this->type,
        ];

        // check if the component has one or more children
        // if so, we will add the 'children' to the array representation
        // otherwise, we will add the 'child' property
        if ($this->hasChildren()) {
            if ($this->canHaveChildren) {
                $obj['children'] = array_map(fn($child) => $child->toArray(), $this->children);
            } else {
                $obj['child'] = $this->children[0]->toArray();
            }
        }

        // add each property to the array representation
        foreach ($this->properties as $key => $value) {
            $obj[$key] = $value;
        }

        return $obj;
    }
    
    /**
     * Convert component to JSON string
     * 
     * @return string JSON representation of component
     */
    public function toJson(): string {
        return json_encode($this->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
    
    /**
     * Build the component
     * 
     * This method is called during the build process to finalize
     * the component configuration
     * 
     * @return array Built component configuration
     */
    public function build(): array {
        return $this->toArray();
    }
    
    /**
     * Validate component configuration
     * 
     * Override this method in subclasses to implement specific validation rules
     * 
     * @return array Array of validation errors (empty if valid)
     */
    public function validate(): array {
        $errors = [];
        
        if (empty($this->type)) {
            $errors[] = "Component type is required";
        }
        
        // Validate children
        foreach ($this->children as $child) {
            $childErrors = $child->validate();
            if (!empty($childErrors)) {
                $errors = array_merge($errors, $childErrors);
            }
        }
        
        return $errors;
    }
    
    /**
     * Check if component has children
     * 
     * @return bool True if component has children
     */
    public function hasChildren(): bool {
        return !empty($this->children);
    }
    
    /**
     * Count child components
     * 
     * @return int Number of child components
     */
    public function getChildCount(): int {
        return count($this->children);
    }
    
    /**
     * Clone component (deep copy)
     * 
     * @return BaseComponent Cloned component
     */
    public function clone(): BaseComponent {
        $clone = clone $this;
        $clone->children = [];
        
        foreach ($this->children as $child) {
            $clone->addChild($child->clone());
        }
        
        return $clone;
    }
}

?>
