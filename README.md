RybakitNavigationBundle
=======================
[![Build Status](https://travis-ci.org/rybakit/RybakitNavigationBundle.svg?branch=master)](https://travis-ci.org/rybakit/RybakitNavigationBundle)

## Installation

The recommended way to install this bundle is through [Composer](http://getcomposer.org):

```sh
$ composer require rybakit/navigation-bundle:~1.0@dev
```

After you have installed the package, include RybakitNavigationBundle to your kernel class:

```php
public function registerBundles()
{
    $bundles = [
        ...
        new Rybakit\Bundle\RybakitNavigationBundle(),
    ];

    ...
}
```

## Usage Examples

> To see how to create a menu as a service and retrieve it from different parts of your application, follow the link: https://gist.github.com/rybakit/4491556.

### Build a navigation tree

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

### Create a tree from an array

``` php
<?php

use Rybakit\Bundle\NavigationBundle\Navigation\ItemFactory;
use Rybakit\Bundle\NavigationBundle\Navigation\Filter\BindFilter;

...

$array = [
    'label'    => 'root',
    'children' => [
        ['label' => 'Item 1.1'],
        ['label' => 'Item 1.2', 'children' => [['label' => 'Item 1.2.1']]],
        ['label' => 'Item 1.3'],
    ],
];

$factory = new ItemFactory(new BindFilter());
$root = $factory->create($array);
```

### Match the current item

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
        $request = $this->requestStack->getMasterRequest();
        $route = $request->attributes->get('_route');
        $routeParams = $this->request->attributes->get('_route_params', []);

        $filter = new FilterChain([
            $matchFilter = new MatchFilter(new RoutesMatcher($route, $routeParams)),
            new BindFilter(),
        ]);

        $factory = new ItemFactory($filter);
        $root = $factory->create([
            'label'     => 'acme_demo.home',
            'route'     => 'acme_demo_home',
            'children'  => [
                [
                    'label'  => 'acme_demo.user_new',
                    'route'  => 'acme_demo_user_new',
                    'routes' => ['acme_demo_user_create'],
                ],
            ],
        ]);

        if (!$current = $matchFilter->getMatched()) {
            $current = $root;
        }
        $current->setActive();

        return ['root' => $root, 'current' => $current];
    }
}
```

### Default item properties

``` php
<?php

use Rybakit\Bundle\NavigationBundle\Navigation\Item;
use Rybakit\Bundle\NavigationBundle\Navigation\ItemFactory;
use Rybakit\Bundle\NavigationBundle\Navigation\Filter\BindFilter;

...

$item = new Item();

// set translation domain to "AcmeDemoBundle" by default for all tree items
$item->transDomain = 'AcmeDemoBundle';

$factory = new ItemFactory(new BindFilter(), $item);
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

{# render breadcrumbs with custom title at the end #}
{{ nav(nav.current|breadcrumbs, "breadcrumbs", { "last": "Custom title" }) }}

{# get root #}
{{ nav.current|ancestor(0) }}

{# get second level ancestor #}
{{ nav.current|ancestor(2) }}

{# get parent #}
{{ nav.current|ancestor(-1) }}

{# render submenu #}
{{ nav(nav.current|ancestor(2)|tree(2)|visible, "navlist") }}
```


### Customising the navigation template

```yaml
# app/config/config.yml
rybakit_navigation:
    template: navigation.html.twig
```

```yaml
# app/Resources/views/navigation.html.twig
{% extends 'RybakitNavigationBundle::navigation.html.twig' %}

# override the "nav" block
{% block nav %}
    <ul class="nav navbar-nav">{{ block('nav_items') }}</ul>
{% endblock %}
```


## License

RybakitNavigationBundle is released under the MIT License. See the bundled [LICENSE](LICENSE) file for details.
