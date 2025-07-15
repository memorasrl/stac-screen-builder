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

$sb = new StacScreenBuilder();
$sb->setRootComponent(
    $sb->createContainer('stack')
        ->addChildren([
            $sb->createText("prova bottone")
                ->setOverflow('fade')
                ->setFontWeight('bold'),
            $sb->createText("prova bottone 2")
                ->setOverflow('fade')
                ->setFontWeight('bold'),
            $sb->createText("prova bottone 3")
                ->setOverflow('fade')
                ->setFontWeight('bold')
        ])
);

echo json_encode($sb->build(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

?>
