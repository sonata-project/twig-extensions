UPGRADE 1.x
===========
### Moved SonataTwigBundle

`Sonata\Twig\Bridge\Symfony\Bundle\SonataTwigBundle` has been deprecated. Use `Sonata\Twig\Bridge\Symfony\SonataTwigBundle` instead.

Before:
```php
return [
    //...
    Sonata\Twig\Bridge\Symfony\Bundle\SonataTwigBundle::class => ['all' => true],
    //...
];
```

After:
```php
return [
    //...
    Sonata\Twig\Bridge\Symfony\SonataTwigBundle::class => ['all' => true],
    //...
];
```
