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

class AppMenu
{
    public function __construct(
        private MenuFactory $menuFactory,
    ) {
    }

    public function __invoke(): MenuItemInterface
    {
        return $this->menuFactory->create('main_menu_site', [
            'children' => [
                [
                    'label' => 'Home',
                    'uri' => [
                        'link' => 'https://www.example.com/home',
                    ],
                    'id' => 'home',
                ],
                [
                    'label' => 'About us',
                    'uri' => [
                       'route'=> [
                            'name'=> 'app.aboutus',
                            'parameters => [] // optional
                       ]
                    ],
                    'id' => 'about_us',
                ],
            ],
        ]);
    }
```
###  Config service
Menu class must register with tag ``wud_menu_manager.menus``
```
services:
  App\Menu\AppMenu:
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
use Wiredupdev\MenuManagerBundle\Menu\ProcessInterface;

class ExampleProcess implements ProcessInterface
{
    //...

    public function process(Item $item): void
    {
         // change menu item data.
    }
}

````
### Cacheable Process
You can implement \Wiredupdev\MenuManagerBundle\Menu\Cacheable for process that don't need to be executed everytime when menu is render. 

### Rendering menu
You can optionally set the template as second parameter.
````
    {{ menu('main_menu_site', 'some-template.html.twig') }}
````
For  template override see @WudMenuManager\default.html.twig