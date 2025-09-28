# Menu Manager Bundle
Menu manager bundle provides easy way to create and organize menus in your symfony applications.
### Installation 
```
 composer require wiredupdev/menu-manager-bundle
```
### Config symfony
```
<?php
    // aplication-folder/config/bundles.php
    return [
        //...
        Wiredupdev\MenuManagerBundle\WudMenuManagerBundle::class => ['all' => true]  
    ];
```
###  Creating menu class
Class must implement __invoke method.
```
<?php
//...
use Wiredupdev\MenuManagerBundle\Menu\Item;
use Wiredupdev\MenuManagerBundle\Menu\Manager;

class Site
{
    //..
    public function __invoke(Manager $menuManager): void
    {
        $menuManager->add(
            Item::create('main_menu_site', 'main menu site')
            ->addChild(
                Item::create('main_menu_home', 'home', 'http://localhost/home')
                ->addAttribute('item_html_class', 'main-menu-home')
            )->addChild(
                Item::create('main_menu_about_about_us', 'about us', 'http://localhost/aboutus')
                ->addAttribute('item_html_class', 'main-menu-about-about-us')
            )
        );
    }
```
###  Config service
Menu class must register with tag ``wud_menu_manager.menus``
```
services:
  App\Menu\Site:
    tags:
      - { name: wud_menu_manager.menus }
```
###  Menu item processor
Processor component run individual process when iterates through menu items.
To register custom processes you need to add tag ``wud_menu_manager.processor`` to your service and implement
``Wiredupdev\MenuManagerBundle\Menu\Processor\ProcessInterface``.
```
services:
  App\Menu\Processor\ExampleProcess:
    tags:
      - { name: wud_menu_manager.menus }
```
````
//..
use Wiredupdev\MenuManagerBundle\Menu\Item;

class ExampleProcess implements ProcessInterface
{
    //...

    public function process(Item $item): void
    {
         // change menu item data.
    }
}
````
