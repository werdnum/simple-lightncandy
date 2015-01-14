# simple-lightncandy

This is a simple, no-nonsense class that allows you to get started using
[LightnCandy](http://www.github.com/zordius/lightncandy) for your templating needs.

Example use:

```php
$templating = new SimpleLightNCandy( __DIR__ . "/templates" );
$templating->addHelper( 'foo', function() { return "foo"; } )
$templating->addBlockHelper( 'fooBlock', function() { return "fooblock"; } );

print $templating->renderTemplate( 'foo_template' );
```

If you want to do anything fancy (for example, change the compile options), then you can subclass it.

It's designed to be installed using Composer:

`composer require werdnum/simple-lightncandy:~0.1`
