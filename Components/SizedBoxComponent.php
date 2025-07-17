<?php

require_once __DIR__ . '/BaseComponent.php';

/**
 * SizedBox Component - Flutter SizedBox widget
 * 
 * This component represents Flutter SizedBox widget that creates a box with a specific size.
 */
class SizedBoxComponent extends BaseComponent {
    
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct('sizedBox');
    }
    
    /**
     * Set width
     * 
     * @param float $width Width value
     * @return self For method chaining
     */
    public function setWidth(float $width): self {
        $this->setProperty('width', $width);
        return $this;
    }
    
    /**
     * Get width
     * 
     * @return float|null Width value
     */
    public function getWidth(): ?float {
        return $this->getProperty('width');
    }
    
    /**
     * Set height
     * 
     * @param float $height Height value
     * @return self For method chaining
     */
    public function setHeight(float $height): self {
        $this->setProperty('height', $height);
        return $this;
    }
    
    /**
     * Get height
     * 
     * @return float|null Height value
     */
    public function getHeight(): ?float {
        return $this->getProperty('height');
    }
}

?>
