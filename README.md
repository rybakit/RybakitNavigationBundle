RybakitNavigationBundle
=======================
[![Build Status](https://secure.travis-ci.org/rybakit/RybakitNavigationBundle.png?branch=master)](http://travis-ci.org/rybakit/RybakitNavigationBundle)

## Installation

Add the following to your composer.json file

``` json
{
    "require": {
        "rybakit/navigation-bundle": "*"
    }
}
```
and update composer dependencies:

```bash
$ php composer.phar update rybakit/navigation-bundle
```

After you have installed the package, include RybakitNavigationBundle to your kernel class:

```php
public function registerBundles()
{
    $bundles = array(
        ...
        new Rybakit\Bundle\RybakitNavigationBundle(),
    );

    ...
}
```

## Usage Examples

### Build navigation tree

``` php
<?php

use Rybakit\Bundle\NavigationBundle\Navigation\Item;

$root = new Item('root');
$child = new Item('child');
$root->add($child);

$parent = $child->getParent(); // $root
$has = $root->has($child); // true
$root->remove($child);
```

### Create tree from array

``` php
<?php

use Rybakit\Bundle\NavigationBundle\Navigation\ItemFactory;

...

$array = array(
    'label'    => 'root',
    'children' => array(
        array('label' => 'Item 1.1'),
        array('label' => 'Item 1.2', 'children' => array(array('label' => 'Item 1.2.1'))),
        array('label' => 'Item 1.3'),
    ),
);

$factory = new ItemFactory();
$root = $factory->create($array);
```

### Match current item

``` php
// Acme/DemoBundle/Navigation/NavigationBuilder.php
<?php

namespace Acme\DemoBundle\Navigation;

use Rybakit\Bundle\NavigationBundle\Navigation\ItemFactory;
use Rybakit\Bundle\NavigationBundle\Navigation\Filter\BindFilter;
use Rybakit\Bundle\NavigationBundle\Navigation\Filter\FilterChain;
use Rybakit\Bundle\NavigationBundle\Navigation\Filter\MatchFilter;
use Rybakit\Bundle\NavigationBundle\Navigation\Filter\Matcher\RoutesMatcher;

class NavigationBuilder
{
    ...

    public function createNavigation()
    {
        $route = $this->request->attributes->get('_route');
        $routeParams = $this->request->attributes->get('_route_params', array());

        $filter = new FilterChain(array(
            $matchFilter = new MatchFilter(new RoutesMatcher($route, $routeParams)),
            new BindFilter(),
        ));

        $factory = new ItemFactory($filter);
        $root = $factory->create(array(
            'label'     => 'acme_demo.home',
            'route'     => 'acme_demo_home',
            'children'  => array(
                array(
                    'label'  => 'acme_demo.user_new',
                    'route'  => 'acme_demo_user_new',
                    'routes' => array('acme_demo_user_new', 'acme_demo_user_create'),
                ),
            ),
        ));

        if (!$current = $matchFilter->getMatched()) {
            $current = $root;
        }
        $current->setActive();

        return array('root' => $root, 'current' => $current);
    }
}
```

### Default item properties

``` php
<?php

use Rybakit\Bundle\NavigationBundle\Navigation\Item;
use Rybakit\Bundle\NavigationBundle\Navigation\ItemFactory;

...

$item = new Item();

// set translation domain to "AcmeDemoBundle" by default for all tree items
$item->transDomain = 'AcmeDemoBundle';

$factory = new ItemFactory(null, $item);
$root = $factory->create($array);
```

### Twig

```jinja
{# simple render #}
{{ nav(nav.root, "my_block_name", { "opt1": "val1", "opt2": "val2" }) }}

{# render using tree filter #}
{{ nav(nav.root|tree, "nav") }}

{# render using tree filter with max depth = 1 #}
{{ nav(nav.root|tree(1), "nav") }}

{# render only visible items, max depth = 1 #}
{{ nav(nav.root|tree(1)|visible, "nav") }}

{# render only hidden items #}
{{ nav(nav.root|tree|visible(false), "nav") }}

{# render breadcrumbs #}
{{ nav(nav.current|breadcrumbs, "breadcrumbs") }}

{# get root #}
{{ nav.current|ancestor(0) }}

{# get second level ancestor #}
{{ nav.current|ancestor(2) }}

{# get parent #}
{{ nav.current|ancestor(-1) }}

{# render submenu #}
{{ nav(nav.current|ancestor(2)|tree(2)|visible, "navlist") }}

```

## License

RybakitNavigationBundle is released under the MIT License. See the bundled LICENSE file for details.
