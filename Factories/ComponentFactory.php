<?php

require_once __DIR__ . '/../Components/ContainerComponent.php';
require_once __DIR__ . '/../Components/TextComponent.php';
require_once __DIR__ . '/../Components/ButtonComponent.php';

/**
 * Component Factory - Creates UI components using Factory Pattern
 * 
 * This factory class provides a centralized way to create UI components
 * with proper validation and configuration. It implements the Factory Pattern
 * to ensure consistent component creation across the application.
 */
class ComponentFactory {
    /**
     * @var array Supported component types mapped to their classes
     */
    const COMPONENT_TYPES = [
        'container' => ContainerComponent::class,
        'column' => ContainerComponent::class,
        'row' => ContainerComponent::class,
        'stack' => ContainerComponent::class,
        'text' => TextComponent::class,
        'button' => ButtonComponent::class
    ];
    
    /**
     * @var array Component type mappings for container types
     */
    const CONTAINER_TYPE_MAPPINGS = [
        'container' => 'container',
        'column' => 'column',
        'row' => 'row',
        'stack' => 'stack',
    ];
    
    /**
     * @var array Component validators
     */
    private static array $validators = [];
    
    /**
     * @var array Component configurations
     */
    private static array $configurations = [];
    
    /**
     * Create a component by type
     * 
     * @param string $type Component type
     * @param array $config Component configuration
     * @return BaseComponent Created component
     * @throws InvalidArgumentException If type is not supported
     */
    public static function create(string $type, array $config = []): BaseComponent {
        if (!self::isSupported($type)) {
            throw new InvalidArgumentException("Component type '{$type}' is not supported");
        }
        
        $component = self::createComponent($type, $config);
        
        // Apply configuration
        self::applyConfiguration($component, $config);
        
        // Apply global configuration if exists
        if (isset(self::$configurations[$type])) {
            self::applyConfiguration($component, self::$configurations[$type]);
        }
        
        // Validate component
        self::validateComponent($component);
        
        return $component;
    }
    
    /**
     * Create a container component
     * 
     * @param string $type Container type (column, row, stack, etc.)
     * @param array $config Component configuration
     * @return ContainerComponent Created container component
     */
    public static function createContainer(string $type = 'column', array $config = []): ContainerComponent {
        $containerType = self::CONTAINER_TYPE_MAPPINGS[$type] ?? 'column';
        $component = new ContainerComponent($containerType);
        
        self::applyConfiguration($component, $config);
        
        return $component;
    }
    
    /**
     * Create a text component
     * 
     * @param string $id Component ID
     * @param string $text Text content
     * @param array $config Component configuration
     * @return TextComponent Created text component
     */
    public static function createText(string $text = '', array $config = []): TextComponent {
        $component = new TextComponent($text);
        
        self::applyConfiguration($component, $config);
        
        return $component;
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
    public static function createButton(string $text = '', string $onPressed = '', string $type = 'elevated', array $config = []): ButtonComponent {
        $component = new ButtonComponent($text, $onPressed, $type);
        
        self::applyConfiguration($component, $config);
        
        return $component;
    }
    
    /**
     * Create an elevated button
     * 
     * @param string $text Button text
     * @param string $onPressed Callback function
     * @param array $config Component configuration
     * @return ButtonComponent Created elevated button
     */
    public static function createElevatedButton(string $text, string $onPressed, array $config = []): ButtonComponent {
        return self::createButton($text, $onPressed, 'elevated', $config);
    }
    
    /**
     * Create an outlined button
     * 
     * @param string $text Button text
     * @param string $onPressed Callback function
     * @param array $config Component configuration
     * @return ButtonComponent Created outlined button
     */
    public static function createOutlinedButton(string $text, string $onPressed, array $config = []): ButtonComponent {
        return self::createButton($text, $onPressed, 'outlined', $config);
    }
    
    /**
     * Create a text button
     * 
     * @param string $text Button text
     * @param string $onPressed Callback function
     * @param array $config Component configuration
     * @return ButtonComponent Created text button
     */
    public static function createTextButton(string $text, string $onPressed, array $config = []): ButtonComponent {
        return self::createButton($text, $onPressed, 'text', $config);
    }
    
    /**
     * Create an icon button
     * 
     * @param string $icon Icon name
     * @param string $onPressed Callback function
     * @param array $config Component configuration
     * @return ButtonComponent Created icon button
     */
    public static function createIconButton(string $icon, string $onPressed, array $config = []): ButtonComponent {
        $config['icon'] = ['name' => $icon, 'position' => 'only'];
        return self::createButton('', $onPressed, 'icon', $config);
    }
    
    /**
     * Create a floating action button
     * 
     * @param string $icon Icon name
     * @param string $onPressed Callback function
     * @param array $config Component configuration
     * @return ButtonComponent Created FAB
     */
    public static function createFAB(string $icon, string $onPressed, array $config = []): ButtonComponent {
        $config['icon'] = ['name' => $icon, 'position' => 'only'];
        return self::createButton('', $onPressed, 'fab', $config);
    }
    
    /**
     * Create a column container
     * 
     * @param array $config Component configuration
     * @return ContainerComponent Created column container
     */
    public static function createColumn(array $config = []): ContainerComponent {
        return self::createContainer('column', $config);
    }
    
    /**
     * Create a row container
     * 
     * @param array $config Component configuration
     * @return ContainerComponent Created row container
     */
    public static function createRow(array $config = []): ContainerComponent {
        return self::createContainer('row', $config);
    }
    
    /**
     * Create a stack container
     * 
     * @param array $config Component configuration
     * @return ContainerComponent Created stack container
     */
    public static function createStack(array $config = []): ContainerComponent {
        return self::createContainer('stack', $config);
    }
    
    /**
     * Check if component type is supported
     * 
     * @param string $type Component type
     * @return bool True if supported
     */
    public static function isSupported(string $type): bool {
        return array_key_exists($type, self::COMPONENT_TYPES);
    }
    
    /**
     * Get supported component types
     * 
     * @return array Supported component types
     */
    public static function getSupportedTypes(): array {
        return array_keys(self::COMPONENT_TYPES);
    }
    
    /**
     * Register a validator for a component type
     * 
     * @param string $type Component type
     * @param callable $validator Validator function
     */
    public static function registerValidator(string $type, callable $validator): void {
        self::$validators[$type] = $validator;
    }
    
    /**
     * Register a configuration for a component type
     * 
     * @param string $type Component type
     * @param array $config Configuration array
     */
    public static function registerConfiguration(string $type, array $config): void {
        self::$configurations[$type] = $config;
    }
    
    /**
     * Create component instance
     * 
     * @param string $type Component type
     * @param array $config Component configuration
     * @return BaseComponent Created component
     */
    private static function createComponent(string $type, array $config): BaseComponent {
        switch ($type) {
            case 'container':
            case 'column':
            case 'row':
            case 'stack':
            case 'wrap':
            case 'listview':
                $containerType = self::CONTAINER_TYPE_MAPPINGS[$type] ?? 'column';
                return new ContainerComponent($containerType);
                
            case 'text':
                $text = $config['text'] ?? '';
                return new TextComponent($text);
                
            case 'button':
                $text = $config['text'] ?? '';
                $onPressed = $config['onPressed'] ?? '';
                $buttonType = $config['buttonType'] ?? 'elevated';
                return new ButtonComponent($text, $onPressed, $buttonType);
                
            default:
                throw new InvalidArgumentException("Unknown component type: {$type}");
        }
    }
    
    /**
     * Apply configuration to component
     * 
     * @param BaseComponent $component Component to configure
     * @param array $config Configuration array
     */
    private static function applyConfiguration(BaseComponent $component, array $config): void {
        foreach ($config as $key => $value) {
            if (method_exists($component, $key)) {
                $component->$key($value);
            } elseif ($key === 'properties') {
                if (is_array($value)) {
                    $component->setProperties($value);
                }
            } elseif ($key === 'style') {
                if (is_array($value)) {
                    $component->setStyle($value);
                }
            } elseif ($key === 'children') {
                if (is_array($value)) {
                    foreach ($value as $child) {
                        if ($child instanceof BaseComponent) {
                            $component->addChild($child);
                        }
                    }
                }
            }
        }
    }
    
    /**
     * Validate component
     * 
     * @param BaseComponent $component Component to validate
     * @throws InvalidArgumentException If validation fails
     */
    private static function validateComponent(BaseComponent $component): void {
        $type = $component->getType();
        
        // Run built-in validation
        $errors = $component->validate();
        
        // Run custom validator if registered
        if (isset(self::$validators[$type])) {
            $customErrors = call_user_func(self::$validators[$type], $component);
            if (is_array($customErrors)) {
                $errors = array_merge($errors, $customErrors);
            }
        }
        
        if (!empty($errors)) {
            throw new InvalidArgumentException("Component validation failed: " . implode(', ', $errors));
        }
    }
    
    /**
     * Get component class for type
     * 
     * @param string $type Component type
     * @return string Component class name
     * @throws InvalidArgumentException If type is not supported
     */
    public static function getComponentClass(string $type): string {
        if (!self::isSupported($type)) {
            throw new InvalidArgumentException("Component type '{$type}' is not supported");
        }
        
        return self::COMPONENT_TYPES[$type];
    }
    
    /**
     * Clear all registered validators and configurations
     */
    public static function clearRegistrations(): void {
        self::$validators = [];
        self::$configurations = [];
    }
}

?>
