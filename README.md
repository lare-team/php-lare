# php-lare

## How to install php-lare?

There are just two steps needed to install php-lare:

1. Add php-lare to your composer.json:

	```json
	{
        "require": {
            "lare_team/php_lare": ">=1.0.0",
        }
    }
	```

2. Set the current Lare namespace in your View or anywhere before your templates get rendered:

    ```php
    use Lare_Team\Lare\Lare as Lare;
    ...
    Lare::set_current_namespace('Current.Lare.Namespace');
	```

## How do i use php-lare?

This example seems to show a huge overhead, actually only the if-else tags make it that big.

```php
// View:
if (!Lare::matches('Lare')) {
    // Do everything you need only for the whole site.
}
if (!Lare::matches('Lare.Home')) {
    // Do everything you need to handle the home page.
}

// Head-Template:
if (!Lare::matches('Lare')) {
    <html>
    <head>
    // Scripts and Stylesheets could be loaded here, no need to reload them when changing the page
    <script...
    <link...
<?php } else { ?>
    <lare-head>
<?php }
...
if (!Lare::matches('Lare')) {
    </head>
<?php } else { ?>
    </lare-head>
<?php }

// Body-Template:
if (!Lare::matches('Lare')) {
    <body>
        <header>...</header>
        <div id="site">
            ...
            <div id="page">
<?php } else { ?>
    <lare-body>
<?php }

// Render everything you need in the page container.

if (!Lare::matches('Lare')) { ?>
            </div>
            ...//site content could still be here
            <footer></footer>
        </div>
    </body>
<?php } else { ?>
    </lare-body>
<?php } ?>
```
If this seems to be too complex for you, try [twig](https://github.com/twigphp/Twig) in combination with [twig-lare](https://github.com/lare-team/twig-lare) for templating.

## What do you need for php-lare?

1. [PHP](http://php.net) >= 5.3.29
2. [lare.js](https://github.com/lare-team/lare.js)

## Projects using php-lare

1. [iekadou.com](http://iekadou.com)

If you are using php-lare, please contact us, and tell us in which projects you are using it. Thank you!

Happy speeding up your php project!

For further information read [php-lare on iekadou.com](http://www.iekadou.com/programming/php-lare)
