<?php

require_once __DIR__ . '/BaseComponent.php';
require_once __DIR__ . '/../Interfaces/ContainerInterface.php';

/**
 * Container Component - Flutter Column, Row, Stack, Container widgets
 * 
 * This component represents Flutter layout widgets that can contain other widgets.
 * It supports all major Flutter container types:
 * - Column: Vertical layout
 * - Row: Horizontal layout  
 * - Stack: Layered layout
 * - Container: Single child with styling
 * - Wrap: Flexible layout
 * - ListView: Scrollable list
 */
class ContainerComponent extends BaseComponent implements ContainerInterface {
    /**
     * @var array Valid container types mapped to Flutter widgets
     */
    const VALID_TYPES = [
        'column' => 'column',
        'row' => 'row',
        'stack' => 'stack',
        'container' => 'container',
        'listview' => 'listView'
    ];
    
    /**
     * @var array Valid main axis alignments for Column/Row
     */
    const MAIN_AXIS_ALIGNMENTS = [
        'start' => 'start',
        'end' => 'end',
        'center' => 'center',
        'spaceBetween' => 'spaceBetween',
        'spaceAround' => 'spaceAround',
        'spaceEvenly' => 'spaceEvenly'
    ];
    
    /**
     * @var array Valid cross axis alignments for Column/Row
     */
    const CROSS_AXIS_ALIGNMENTS = [
        'start' => 'start',
        'end' => 'end',
        'center' => 'center',
        'stretch' => 'stretch',
        'baseline' => 'baseline'
    ];
    
    /**
     * @var array Valid stack fit options
     */
    const STACK_FIT_OPTIONS = [
        'loose' => 'loose',
        'expand' => 'expand',
        'passthrough' => 'passthrough'
    ];
    
    /**
     * Constructor
     * 
     * @param string $containerType Container type (column, row, stack, etc.)
     */
    public function __construct(string $containerType = 'column') {
        if (!array_key_exists($containerType, self::VALID_TYPES)) {
            throw new InvalidArgumentException("Invalid container type: {$containerType}");
        }
        
        parent::__construct($containerType, true);
        $this->initializeDefaults();
    }
    
    /**
     * Initialize default properties based on container type
     */
    private function initializeDefaults(): void {
        switch ($this->type) {
            case 'column':
            case 'row':
                $this->setProperty('mainAxisAlignment', 'start');
                $this->setProperty('crossAxisAlignment', 'center');
                break;
                
            case 'stack':
                $this->setProperty('alignment', 'topStart');
                $this->setProperty('fit', 'loose');
                break;
                
            case 'container':
                $this->setProperty('alignment', 'center');
                break;

            case 'listview':
                $this->setProperty('scrollDirection', 'vertical');
                $this->setProperty('shrinkWrap', false);
                break;
        }
    }
    
    /**
     * Set main axis alignment (for Column/Row)
     * 
     * @param string $alignment Alignment value
     * @return self For method chaining
     * @throws InvalidArgumentException If alignment is invalid
     */
    public function setMainAxisAlignment(string $alignment): self {
        if (!array_key_exists($alignment, self::MAIN_AXIS_ALIGNMENTS)) {
            throw new InvalidArgumentException("Invalid main axis alignment: {$alignment}");
        }
        
        $this->setProperty('mainAxisAlignment', $alignment);
        return $this;
    }
    
    /**
     * Set cross axis alignment (for Column/Row)
     * 
     * @param string $alignment Alignment value
     * @return self For method chaining
     * @throws InvalidArgumentException If alignment is invalid
     */
    public function setCrossAxisAlignment(string $alignment): self {
        if (!array_key_exists($alignment, self::CROSS_AXIS_ALIGNMENTS)) {
            throw new InvalidArgumentException("Invalid cross axis alignment: {$alignment}");
        }
        
        $this->setProperty('crossAxisAlignment', $alignment);
        return $this;
    }
    
    /**
     * Set main axis size (for Column/Row)
     * 
     * @param string $size Size value (min, max)
     * @return self For method chaining
     */
    public function setMainAxisSize(string $size): self {
        if (!in_array($size, ['min', 'max'])) {
            throw new InvalidArgumentException("Invalid main axis size: {$size}");
        }
        
        $this->setProperty('mainAxisSize', $size);
        return $this;
    }
    
    /**
     * Set ListView scroll direction
     * 
     * @param string $direction Scroll direction (vertical, horizontal)
     * @return self For method chaining
     */
    public function setScrollDirection(string $direction): self {
        if ($this->type !== 'listview') {
            throw new InvalidArgumentException("Scroll direction can only be set on listview containers");
        }
        
        if (!in_array($direction, ['vertical', 'horizontal'])) {
            throw new InvalidArgumentException("Invalid scroll direction: {$direction}");
        }
        
        $this->setProperty('scrollDirection', $direction);
        return $this;
    }
    
    /**
     * Set ListView shrink wrap
     * 
     * @param bool $shrinkWrap Shrink wrap value
     * @return self For method chaining
     */
    public function setShrinkWrap(bool $shrinkWrap): self {
        if ($this->type !== 'listview') {
            throw new InvalidArgumentException("Shrink wrap can only be set on listview containers");
        }
        
        $this->setProperty('shrinkWrap', $shrinkWrap);
        return $this;
    }
    
    /**
     * Validate container configuration
     * 
     * @return array Array of validation errors
     */
    public function validate(): array {
        $errors = parent::validate();
        
        // Validate container type
        if (!array_key_exists($this->type, self::VALID_TYPES)) {
            $errors[] = "Invalid container type: {$this->type}";
        }
        
        // Validate main axis alignment
        $mainAxisAlignment = $this->getProperty('mainAxisAlignment');
        if ($mainAxisAlignment && !array_key_exists($mainAxisAlignment, self::MAIN_AXIS_ALIGNMENTS)) {
            $errors[] = "Invalid main axis alignment: {$mainAxisAlignment}";
        }
        
        // Validate cross axis alignment
        $crossAxisAlignment = $this->getProperty('crossAxisAlignment');
        if ($crossAxisAlignment && !array_key_exists($crossAxisAlignment, self::CROSS_AXIS_ALIGNMENTS)) {
            $errors[] = "Invalid cross axis alignment: {$crossAxisAlignment}";
        }

        return $errors;
    }
}

?>
