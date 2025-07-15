<?php

require_once __DIR__ . '/BaseComponent.php';

/**
 * Text Component - Flutter Text widget
 * 
 * This component represents Flutter Text widget with full styling support.
 * It supports all major Flutter text properties:
 * - Text content and styling
 * - Font properties (size, weight, family)
 * - Color and decoration
 * - Alignment and overflow handling
 * - Text spans and rich text
 * 
 * @package StacScreenBuilder\Components
 * @author Your Name
 * @version 1.0.0
 */
class TextComponent extends BaseComponent {
    /**
     * @var array Valid font weights mapped to Flutter FontWeight
     */
    const FONT_WEIGHTS = [
        'thin' => 'w100',
        'extraLight' => 'w200',
        'light' => 'w300',
        'normal' => 'w400',
        'medium' => 'w500',
        'semiBold' => 'w600',
        'bold' => 'w700',
        'extraBold' => 'w800',
        'black' => 'w900'
    ];
    
    /**
     * @var array Valid text alignments mapped to Flutter TextAlign
     */
    const TEXT_ALIGNMENTS = [
        'left' => 'TextAlign.left',
        'right' => 'TextAlign.right',
        'center' => 'TextAlign.center',
        'justify' => 'TextAlign.justify',
        'start' => 'TextAlign.start',
        'end' => 'TextAlign.end'
    ];
    
    /**
     * @var array Valid text overflow options mapped to Flutter TextOverflow
     */
    const TEXT_OVERFLOW_OPTIONS = [
        'clip' => 'TextOverflow.clip',
        'fade' => 'TextOverflow.fade',
        'ellipsis' => 'TextOverflow.ellipsis',
        'visible' => 'TextOverflow.visible'
    ];
    
    /**
     * Constructor
     * 
     * @param string $id Component ID
     * @param string $text Text content
     */
    public function __construct(string $text = '') {
        parent::__construct('text');
        $this->setText($text);
        $this->initializeDefaults();
    }
    
    /**
     * Initialize default properties
     */
    private function initializeDefaults(): void {
       // TODO: Set default properties for the button
    }

    /**
     * Set a style property
     * 
     * @param string $key Style property key
     * @param mixed $value Style property value
     * @return self For method chaining
     */
    private function setStyle(string $key, $value): self {
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
    private function getStyle(string $key, $default = null) {
        return $this->getProperty("style.{$key}", $default);
    }
    
    /**
     * Set text content
     * 
     * @param string $text Text content
     * @return self For method chaining
     */
    public function setText(string $text): self {
        $this->setProperty('data', $text);
        return $this;
    }
    
    /**
     * Get text content
     * 
     * @return string Text content
     */
    public function getText(): string {
        return $this->getProperty('data', '');
    }
    
    /**
     * Set text alignment
     * 
     * @param string $alignment Text alignment
     * @return self For method chaining
     * @throws InvalidArgumentException If alignment is invalid
     */
    public function setTextAlign(string $alignment): self {
        if (!array_key_exists($alignment, self::TEXT_ALIGNMENTS)) {
            throw new InvalidArgumentException("Invalid text alignment: {$alignment}");
        }
        
        $this->setProperty('textAlign', $alignment);
        return $this;
    }
    
    /**
     * Set text overflow behavior
     * 
     * @param string $overflow Overflow behavior
     * @return self For method chaining
     * @throws InvalidArgumentException If overflow is invalid
     */
    public function setOverflow(string $overflow): self {
        if (!array_key_exists($overflow, self::TEXT_OVERFLOW_OPTIONS)) {
            throw new InvalidArgumentException("Invalid text overflow: {$overflow}");
        }
        
        $this->setProperty('overflow', $overflow);
        return $this;
    }

    /**
     * Set font size
     * 
     * @param float $size Font size in logical pixels
     * @return self For method chaining
     */
    public function setFontWeight(string $weight): self {
        if (!array_key_exists($weight, self::FONT_WEIGHTS)) {
            throw new InvalidArgumentException("Invalid font weight: {$weight}");
        }
        
        $this->setStyle('fontWeight', self::FONT_WEIGHTS[$weight]);
        return $this;
    }
    
    /**
     * Validate text component configuration
     * 
     * @return array Array of validation errors
     */
    public function validate(): array {
        $errors = parent::validate();
        
        // Validate text content
        $text = $this->getProperty('data');
        if ($text === null || $text === '') {
            $errors[] = "Text content is required";
        }
        
        // Validate font size
        $fontSize = $this->getStyle('style.fontSize');
        if ($fontSize !== null && (!is_numeric($fontSize) || $fontSize <= 0)) {
            $errors[] = "Font size must be a positive number";
        }
        
        // Validate overflow
        $overflow = $this->getProperty('overflow');
        if ($overflow && !array_key_exists($overflow, self::TEXT_OVERFLOW_OPTIONS)) {
            $errors[] = "Invalid text overflow: {$overflow}";
        }
        
        return $errors;
    }
}

?>
