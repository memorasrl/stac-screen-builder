<?php

require_once __DIR__ . '/BaseComponent.php';

/**
 * Button Component - Flutter Button widgets
 * 
 * This component represents Flutter button widgets with full styling support.
 * It supports all major Flutter button types:
 * - ElevatedButton: Material elevated button
 * - OutlinedButton: Material outlined button
 * - TextButton: Material text button
 * - IconButton: Icon-only button
 * - FloatingActionButton: FAB button
 */
class ButtonComponent extends BaseComponent {
    /**
     * @var array Valid button types mapped to Flutter widgets
     */
    const BUTTON_TYPES = [
        'elevated' => 'elevatedButton',
        'outlined' => 'outlinedButton',
        'text' => 'textButton',
        'icon' => 'iconButton',
        'fab' => 'floatingActionButton'
    ];
    
    /**
     * Constructor
     * 
     * @param string $id Component ID
     * @param string $text Button text
     * @param string $onPressed Callback function name
     * @param string $buttonType Button type
     */
    public function __construct(string $id, string $text = '', string $onPressed = '', string $buttonType = 'elevated') {
        if (!array_key_exists($buttonType, self::BUTTON_TYPES)) {
            throw new InvalidArgumentException("Invalid button type: {$buttonType}");
        }
        
        parent::__construct($buttonType);
        $this->setText($text);
        $this->setOnPressed($onPressed);
        $this->initializeDefaults();
    }
    
    /**
     * Initialize default properties
     */
    private function initializeDefaults(): void {
        // TODO: Set default properties for the button
    }
    
    /**
     * Set button text
     * 
     * @param string $text Button text
     * @return self For method chaining
     */
    public function setText(string $text): self {
        // TODO: create a text child component
        return $this;
    }
    
    /**
     * Set button press callback
     * 
     * @param string $callback Callback function name
     * @return self For method chaining
     */
    public function setOnPressed(string $callback): self {
        $this->setProperty('onPressed', $callback);
        return $this;
    }
}

?>
