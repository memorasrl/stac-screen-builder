<?php

require_once __DIR__ . '/BaseComponent.php';

/**
 * Form Component - Flutter Form widget
 * 
 * This component represents Flutter Form widget that provides validation context
 * for form fields and manages form state.
 */
class FormComponent extends BaseComponent {
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
     */
    public function __construct() {
        parent::__construct('form');
        $this->initializeDefaults();
    }
    
    /**
     * Initialize default properties
     */
    private function initializeDefaults(): void {
        $this->setAutovalidateMode('disabled');
    }
    
    /**
     * Set autovalidate mode
     * 
     * @param string $mode Autovalidate mode (disabled, always, onUserInteraction, onUnfocus)
     * @return self For method chaining
     */
    public function setAutovalidateMode(string $mode): self {
        if (!array_key_exists($mode, self::AUTOVALIDATE_MODES)) {
            throw new InvalidArgumentException("Invalid autovalidate mode: {$mode}. Valid modes are: " . implode(', ', array_keys(self::AUTOVALIDATE_MODES)));
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
     * Validate form component configuration
     * 
     * @return array Array of validation errors
     */
    public function validate(): array {
        $errors = parent::validate();
        
        // Validate autovalidate mode
        $autovalidateMode = $this->getAutovalidateMode();
        if (!in_array($autovalidateMode, self::AUTOVALIDATE_MODES)) {
            $errors[] = "Invalid autovalidate mode: {$autovalidateMode}";
        }
        
        return $errors;
    }
}

?>
