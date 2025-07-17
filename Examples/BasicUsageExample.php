<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../Interfaces/SerializableInterface.php';
require_once __DIR__ . '/../Interfaces/BuildableInterface.php';
require_once __DIR__ . '/../Interfaces/ContainerInterface.php';
require_once __DIR__ . '/../Components/BaseComponent.php';
require_once __DIR__ . '/../Components/TextComponent.php';
require_once __DIR__ . '/../Components/ButtonComponent.php';
require_once __DIR__ . '/../Factories/ComponentFactory.php';
require_once __DIR__ . '/../Builders/StacScreenBuilder.php';
require_once __DIR__ . '/../../custom-components/CustomInput.php';

$sb = new StacScreenBuilder();
$sb->setRootComponent(
    $sb->padding()
        ->setPadding(16)
        ->addChild(
            $sb->column()
                ->setMainAxisAlignment('center')
                ->setCrossAxisAlignment('center')
                ->addChildren([
                    $sb->expanded()
                        ->addChild(
                            CustomInput::create('username')
                                ->setValidatorRules([
                                    ['rule' => 'required', 'message' => 'This field is required'],
                                    ['rule' => 'min:3', 'message' => 'Must be at least 3 characters']
                                ])
                                ->setDecoration('hintText', 'Username')
                                ->setPrefixIcon(
                                    $sb->icon('person')
                                        ->setColor('#888888')
                                )
                        ),
                    $sb->row()
                        ->setMainAxisAlignment('spaceEvenly')
                        ->setCrossAxisAlignment('center')
                        ->addChildren([
                            $sb->elevatedButton()
                                ->addChild(
                                    $sb->row()
                                        ->setProperty('spacing', 8)
                                        ->setMainAxisAlignment('center')
                                        ->addChildren([
                                            $sb->icon('add')
                                                ->setColor('#FFFFFF'),
                                            $sb->text('Add Item')
                                                ->setFontWeight('bold')
                                        ])
                                )
                        ])
                ])
        )
);

echo json_encode($sb->build(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

?>
