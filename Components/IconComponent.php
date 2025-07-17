<?php

require_once __DIR__ . '/BaseComponent.php';

/**
 * Icon Component - Flutter Icon widget
 * 
 * This component represents Flutter Icon widget.
 */
class IconComponent extends BaseComponent {
    /**
     * Constructor
     * 
     * @param string $icon Icon name
     */
    public function __construct(string $icon) {
        parent::__construct('icon');
        $this->setIcon($icon);
        $this->initializeDefaults();
    }

    /**
     * Initialize default properties
     */
    private function initializeDefaults(): void {
        $this->setSize(24);
    }

    /**
     * Set icon name
     * 
     * @param string $icon Icon name
     * @return self For method chaining
     */
    public function setIcon(string $icon): self {
        $this->setProperty('icon', $icon);
        return $this;
    }

    /**
     * Set icon size
     * 
     * @param int $size Icon size in pixels
     * @return self For method chaining
     */
    public function setSize(int $size): self {
        $this->setProperty('size', $size);
        return $this;
    }

    /**
     * Set icon color
     * 
     * @param string $color Color in hex format (e.g. '#FF0000')
     * @return self For method chaining
     */
    public function setColor(string $color): self {
        $this->setProperty('color', $color);
        return $this;
    }

    /**
     * Validate container configuration
     * 
     * @return array Array of validation errors
     */
    public function validate(): array {
        $errors = parent::validate();
        
        // Validate icon name
        $icon = $this->getProperty('icon');
        if (empty($icon)) {
            $errors[] = "Icon name is required";
        }

        return $errors;
    }
}

?>