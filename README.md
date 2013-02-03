RybakitNavigationBundle
=======================
[![Build Status](https://secure.travis-ci.org/rybakit/RybakitNavigationBundle.png?branch=filter)](http://travis-ci.org/rybakit/RybakitNavigationBundle)

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

...

$root = new Item(array(
    'label'    => 'root',
    'children' => array(
        array('label' => 'Item 1.1'),
        array(
            'label'    => 'Item 1.2',
            'children' => array(
                array('label' => 'Item 1.2.1'),
            ),
        ),
        array('label' => 'Item 1.3'),
    ),
));
```

### Match current item

``` php
// Acme/DemoBundle/Navigation/NavigationBuilder.php
<?php

namespace Acme\DemoBundle\Navigation;

use Rybakit\Bundle\NavigationBundle\Navigation\Item;
use Rybakit\Bundle\NavigationBundle\Navigation\Attribute\UpAttribute;
use Rybakit\Bundle\NavigationBundle\Navigation\Filter\MatchFilter;
use Rybakit\Bundle\NavigationBundle\Navigation\Filter\Matcher\RoutesMatcher;

class NavigationBuilder
{
    ...

    public function createNavigation()
    {
        $route = $this->request->attributes->get('_route');
        $routeParams = $this->request->attributes->get('_route_params', array());

        $filter = new MatchFilter(new RoutesMatcher($route, $routeParams));

        $array = array(
            'label'     => 'acme_demo.home',
            'route'     => 'acme_demo_home',
            'children'  => array(
                array(
                    'label'  => 'acme_demo.user_new',
                    'route'  => 'acme_demo_user_new',
                    'routes' => array('acme_demo_user_new', 'acme_demo_user_create'),
                ),
            ),
        );

        $root = new Item($array, $filter);

        if (!$current = $filter->getMatched()) {
            $current = $root;
        }
        $current->setAttribute('active', new UpAttribute(true));

        return array('root' => $root, 'current' => $current);
    }
}
```

### Default attributes

``` php
<?php

use Rybakit\Bundle\NavigationBundle\Navigation\Item;
use Rybakit\Bundle\NavigationBundle\Navigation\Filter\DefaultsFilter;

...

$filter = new DefaultsFilter(array('trans_domain' => 'AcmeDemoBundle'))
$root = new Item($array, $filter);
```

### Twig

```jinja
{# render custom block #}
{{ nav(nav.root, "custom_nav_block", { "opt1": "val1", "opt2": "val2" }) }}

{# render menu #}
{{ nav(nav.root, "nav") }}

{# render using tree filter with max depth = 1 #}
{{ nav(nav.root|tree(1), "custom_nav_block") }}

{# render only visible items, max depth = 1 #}
{{ nav(nav.root|tree(1)|items({ "visible": true }), "custom_nav_block") }}

{# render only hidden items #}
{{ nav(nav.root, "nav", { "visible": false }) }}

{# render breadcrumbs #}
{{ nav(nav.current, "breadcrumbs") }}

{# render breadcrumbs with custom title at the end #}
{{ nav(nav.current, "breadcrumbs", { "last": "Custom title" }) }}

{# get root #}
{{ nav.current|ancestor(0) }}

{# get second level ancestor #}
{{ nav.current|ancestor(2) }}

{# get parent #}
{{ nav.current|ancestor(-1) }}

{# render submenu #}
{{ nav(nav.current|ancestor(2), "navlist", { "visible": true }) }}

```

## License

RybakitNavigationBundle is released under the MIT License. See the bundled LICENSE file for details.
