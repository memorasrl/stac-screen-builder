<?php

require_once __DIR__ . '/BaseComponent.php';

/**
 * Input Component - Flutter TextFormField widget
 * 
 * This component represents Flutter TextFormField widget for user input.
 * It supports validation, styling, and various input types.
 */
class InputComponent extends BaseComponent {
    /**
     * @var array Valid keyboard types mapped to Flutter TextInputType
     */
    const KEYBOARD_TYPES = [
        'text' => 'text',
        'number' => 'number',
        'email' => 'emailAddress',
        'phone' => 'phone',
        'url' => 'url',
        'multiline' => 'multiline',
        'password' => 'visiblePassword',
        'datetime' => 'datetime'
    ];
    
    /**
     * @var array Valid autovalidate modes mapped to Flutter AutovalidateMode
     */
    const AUTOVALIDATE_MODES = [
        'disabled' => 'disabled',
        'always' => 'always',
        'onUserInteraction' => 'onUserInteraction',
        'onUnfocus' => 'onUnfocus'
    ];
    
    /**
     * Constructor
     * 
     * @param string $id Unique identifier for this input field
     */
    public function __construct(string $id = '') {
        parent::__construct('textFormField');
        $this->setId($id);
        $this->initializeDefaults();
    }
    
    /**
     * Initialize default properties
     */
    private function initializeDefaults(): void {
        $this->setProperty('autovalidateMode', self::AUTOVALIDATE_MODES['disabled']);
        $this->setProperty('keyboardType', self::KEYBOARD_TYPES['text']);
        $this->setProperty('validatorRules', []);
    }
    
    /**
     * Set input field ID
     * 
     * @param string $id Input field ID
     * @return self For method chaining
     */
    public function setId(string $id): self {
        $this->setProperty('id', $id);
        return $this;
    }
    
    /**
     * Get input field ID
     * 
     * @return string Input field ID
     */
    public function getId(): string {
        return $this->getProperty('id', '');
    }
    
    /**
     * Set autovalidate mode
     * 
     * @param string $mode Autovalidate mode (disabled, always, onUserInteraction)
     * @return self For method chaining
     */
    public function setAutovalidateMode(string $mode): self {
        if (!array_key_exists($mode, self::AUTOVALIDATE_MODES)) {
            throw new InvalidArgumentException("Invalid autovalidate mode: {$mode}");
        }
        
        $this->setProperty('autovalidateMode', self::AUTOVALIDATE_MODES[$mode]);
        return $this;
    }
    
    /**
     * Get autovalidate mode
     * 
     * @return string Autovalidate mode
     */
    public function getAutovalidateMode(): string {
        return $this->getProperty('autovalidateMode', self::AUTOVALIDATE_MODES['disabled']);
    }
    
    /**
     * Set validator rules
     * 
     * @param array $rules Array of validation rules, each rule should be an array with 'rule' and 'message' keys
     * @return self For method chaining
     */
    public function setValidatorRules(array $rules): self {
        // Validate that each rule has the correct structure
        foreach ($rules as $index => $rule) {
            if (!is_array($rule)) {
                throw new InvalidArgumentException("Validator rule at index {$index} must be an array");
            }
            
            if (!isset($rule['rule']) || !isset($rule['message'])) {
                throw new InvalidArgumentException("Validator rule at index {$index} must have 'rule' and 'message' keys");
            }
            
            if (!is_string($rule['rule']) || !is_string($rule['message'])) {
                throw new InvalidArgumentException("Validator rule 'rule' and 'message' must be strings at index {$index}");
            }
        }
        
        $this->setProperty('validatorRules', $rules);
        return $this;
    }
    
    /**
     * Get validator rules
     * 
     * @return array Array of validation rules
     */
    public function getValidatorRules(): array {
        return $this->getProperty('validatorRules', []);
    }
    
    /**
     * Set keyboard type
     * 
     * @param string $type Keyboard type (text, number, email, phone, url, multiline, password, datetime)
     * @return self For method chaining
     */
    public function setKeyboardType(string $type): self {
        if (!array_key_exists($type, self::KEYBOARD_TYPES)) {
            throw new InvalidArgumentException("Invalid keyboard type: {$type}");
        }
        
        $this->setProperty('keyboardType', self::KEYBOARD_TYPES[$type]);
        return $this;
    }
    
    /**
     * Get keyboard type
     * 
     * @return string Keyboard type
     */
    public function getKeyboardType(): string {
        return $this->getProperty('keyboardType', self::KEYBOARD_TYPES['text']);
    }
    
    /**
     * Set a style property
     * 
     * @param string $key Style property key
     * @param mixed $value Style property value
     * @return self For method chaining
     */
    public function setStyle(string $key, $value): self {
        $this->setProperty("style.{$key}", $value);
        return $this;
    }
    
    /**
     * Get a style property
     * 
     * @param string $key Style property key
     * @param mixed $default Default value if property not found
     * @return mixed Style property value or default
     */
    public function getStyle(string $key, $default = null) {
        return $this->getProperty("style.{$key}", $default);
    }
    
    /**
     * Set a decoration property
     * 
     * @param string $key Decoration property key
     * @param mixed $value Decoration property value
     * @return self For method chaining
     */
    public function setDecoration(string $key, $value): self {
        $this->setProperty("decoration.{$key}", $value);
        return $this;
    }
    
    /**
     * Get a decoration property
     * 
     * @param string $key Decoration property key
     * @param mixed $default Default value if property not found
     * @return mixed Decoration property value or default
     */
    public function getDecoration(string $key, $default = null) {
        return $this->getProperty("decoration.{$key}", $default);
    }
    
    /**
     * Set prefix icon for input decoration
     * 
     * @param BaseComponent $icon Icon component to use as prefix
     * @return self For method chaining
     */
    public function setPrefixIcon(BaseComponent $icon): self {
        $this->setDecoration('prefixIcon', $icon);
        return $this;
    }
    
    /**
     * Set suffix icon for input decoration
     * 
     * @param BaseComponent $icon Icon component to use as suffix
     * @return self For method chaining
     */
    public function setSuffixIcon(BaseComponent $icon): self {
        $this->setDecoration('suffixIcon', $icon);
        return $this;
    }

    /**
     * Set whether the input is enabled
     * 
     * @param bool $enabled True if input should be enabled, false otherwise
     * @return self For method chaining
     */
    public function setEnabled(bool $enabled): self {
        $this->setProperty('enabled', $enabled);
        return $this;
    }
    
    /**
     * Validate input component configuration
     * 
     * @return array Array of validation errors
     */
    public function validate(): array {
        $errors = parent::validate();
        
        // Validate ID
        $id = $this->getId();
        if (empty($id)) {
            $errors[] = "Input field ID is required";
        }
        
        // Validate autovalidate mode
        $autovalidateMode = $this->getAutovalidateMode();
        if (!in_array($autovalidateMode, self::AUTOVALIDATE_MODES)) {
            $errors[] = "Invalid autovalidate mode: {$autovalidateMode}";
        }
        
        // Validate keyboard type
        $keyboardType = $this->getKeyboardType();
        if (!in_array($keyboardType, self::KEYBOARD_TYPES)) {
            $errors[] = "Invalid keyboard type: {$keyboardType}";
        }
        
        // Validate validator rules
        $validatorRules = $this->getValidatorRules();
        if (!is_array($validatorRules)) {
            $errors[] = "Validator rules must be an array";
        }
        
        return $errors;
    }
    
    /**
     * Convert component to array representation
     * 
     * @return array Array representation of the input component
     */
    public function toArray(): array {
        $array = parent::toArray();
        
        // Handle prefix and suffix icons in decoration
        if (isset($array['decoration'])) {
            $decoration = &$array['decoration'];
            
            // Handle prefix icon
            if (isset($decoration['prefixIcon']) && $decoration['prefixIcon'] instanceof BaseComponent) {
                $decoration['prefixIcon'] = $decoration['prefixIcon']->toArray();
            }
            
            // Handle suffix icon
            if (isset($decoration['suffixIcon']) && $decoration['suffixIcon'] instanceof BaseComponent) {
                $decoration['suffixIcon'] = $decoration['suffixIcon']->toArray();
            }
        }
        
        return $array;
    }
}

?>
