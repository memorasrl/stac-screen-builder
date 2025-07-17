<?php

require_once __DIR__ . '/BaseComponent.php';
require_once __DIR__ . '/TextComponent.php';

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
    ];
    
    /**
     * Constructor
     * 
     * @param ?TextComponent $text Button text component
     * @param string $onPressed Callback function name
     * @param string $buttonType Button type
     */
    public function __construct(?TextComponent $text = null, string $onPressed = '', string $buttonType = 'elevated') {
        if (!array_key_exists($buttonType, self::BUTTON_TYPES)) {
            throw new InvalidArgumentException("Invalid button type: {$buttonType}");
        }
        
        parent::__construct(self::BUTTON_TYPES[$buttonType]);
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
     * @param ?TextComponent $text Button text component
     * @return self For method chaining
     */
    public function setText(?TextComponent $text): self {
        if ($text !== null) {
            $this->addChild($text);            
        }

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
     * Convert component to array representation
     * 
     * This method converts the button component to an array format suitable for
     * serialization or further processing.
     * 
     * @return array Array representation of the button component
     */
    public function toArray(): array {
        $obj = parent::toArray();

        if (isset($obj['icon']) && $obj['icon'] instanceof IconComponent) {
            $obj['icon'] = $obj['icon']->toArray();
        }

        return $obj;
    }
}

?>
