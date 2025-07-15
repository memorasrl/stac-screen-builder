<?php

/**
 * Interface for components that can contain child components
 * 
 * This interface is implemented by layout components like Column, Row, Stack
 * that can contain other Flutter widgets as children
 */
interface ContainerInterface {
    /**
     * Add a child component to this container
     * 
     * @param BaseComponent $child The child component to add
     * @return self For method chaining
     */
    public function addChild(BaseComponent $child): self;

    /**
     * Add multiple child components to this container
     * 
     * @param array $children Array of child components to add
     * @return self For method chaining
     * @throws InvalidArgumentException If any child is not a BaseComponent
     */
    public function addChildren(array $children): self;
}

?>
