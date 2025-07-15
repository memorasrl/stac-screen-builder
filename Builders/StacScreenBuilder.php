<?php

require_once __DIR__ . '/../Components/BaseComponent.php';
require_once __DIR__ . '/../Factories/ComponentFactory.php';
require_once __DIR__ . '/../Interfaces/BuildableInterface.php';
require_once __DIR__ . '/../Interfaces/SerializableInterface.php';

/**
 * Screen Builder - Main builder class implementing Builder Pattern
 * 
 * This class is the main entry point for building Flutter-compatible screens
 * using a fluent interface. It implements the Builder Pattern to provide
 * a clean and intuitive API for creating complex UI structures.
 */
class StacScreenBuilder implements BuildableInterface, SerializableInterface {    
    /**
     * @var BaseComponent|null Root component of the screen
     */
    private ?BaseComponent $rootComponent = null;
    
    /**
     * @var ComponentFactory Component factory instance
     */
    private ComponentFactory $factory;
 
    /**
     * Constructor
     */
    public function __construct() {
        $this->factory = new ComponentFactory();
    }
    
    /**
     * Set root component
     * 
     * @param BaseComponent $component Root component
     * @return self For method chaining
     */
    public function setRootComponent(BaseComponent $component): self {
        $this->rootComponent = $component;
        return $this;
    }
    
    /**
     * Get root component
     * 
     * @return BaseComponent|null Root component
     */
    public function getRootComponent(): ?BaseComponent {
        return $this->rootComponent;
    }
    
    /**
     * Create a container component
     * 
     * @param string $type Container type
     * @param array $config Component configuration
     * @return ContainerComponent Created container
     */
    public function createContainer(string $type = 'column', array $config = []): ContainerComponent {
        return $this->factory->createContainer($type, $config);
    }
    
    /**
     * Create a text component
     * 
     * @param string $text Text content
     * @param array $config Component configuration
     * @return TextComponent Created text component
     */
    public function createText(string $text = '', array $config = []): TextComponent {
        return $this->factory->createText($text, $config);
    }
    
    /**
     * Create a button component
     * 
     * @param string $text Button text
     * @param string $onPressed Callback function
     * @param string $type Button type
     * @param array $config Component configuration
     * @return ButtonComponent Created button component
     */
    public function createButton(string $text = '', string $onPressed = '', string $type = 'elevated', array $config = []): ButtonComponent {
        return $this->factory->createButton($text, $onPressed, $type, $config);
    }
    
    /**
     * Build the screen
     * 
     * @return array Built screen configuration
     */
    public function build(): array {        
        $errors = $this->validate();
        if (!empty($errors)) {
            throw new InvalidArgumentException('Screen validation failed: ' . implode(', ', $errors));
        }
        
        return ($this->rootComponent ? $this->rootComponent->toArray() : null) ?? [];
    }
    
    /**
     * Validate screen configuration
     * 
     * @return array Array of validation errors
     */
    public function validate(): array {
        $errors = [];
        
        // Validate root component
        if (!$this->rootComponent) {
            $errors[] = 'Root component is required';
        } else {
            $componentErrors = $this->rootComponent->validate();
            if (!empty($componentErrors)) {
                $errors = array_merge($errors, $componentErrors);
            }
        }
        
        return $errors;
    }
    
    /**
     * Convert screen to array
     * 
     * @return array Screen array representation
     */
    public function toArray(): array {
        return $this->build();
    }
    
    /**
     * Convert screen to JSON
     * 
     * @return string JSON representation
     */
    public function toJson(): string {
        return json_encode($this->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    /**
     * Clone screen
     * 
     * @return self Cloned screen
     */
    public function clone(): self {
        $cloned = new self();
        
        if ($this->rootComponent) {
            $clonedRoot = $this->rootComponent->clone();
            $cloned->setRootComponent($clonedRoot);
        }
        
        return $cloned;
    }
    
    /**
     * Validate field against rules
     * 
     * @param string $field Field name
     * @param mixed $value Field value
     * @param array $rules Validation rules
     * @return array Validation errors
     */
    private function validateField(string $field, $value, array $rules): array {
        $errors = [];
        
        foreach ($rules as $rule) {
            switch ($rule) {
                case 'required':
                    if (empty($value)) {
                        $errors[] = "Field '{$field}' is required";
                    }
                    break;
                case 'string':
                    if ($value !== null && !is_string($value)) {
                        $errors[] = "Field '{$field}' must be a string";
                    }
                    break;
                default:
                    if (strpos($rule, 'min:') === 0) {
                        $min = intval(substr($rule, 4));
                        if (is_string($value) && strlen($value) < $min) {
                            $errors[] = "Field '{$field}' must be at least {$min} characters";
                        }
                    }
                    break;
            }
        }
        
        return $errors;
    }
}

?>
