.. index::
    single: Installation

Installation
============

The easiest way to install ``twig-extensions`` is to require it with Composer:

.. code-block:: bash

    composer require sonata-project/twig-extensions

Alternatively, you could add a dependency into your ``composer.json`` file directly.

Now, enable the bundle in ``bundles.php`` file::

    // config/bundles.php

    return [
        // ...
        Sonata\Twig\Bridge\Symfony\Bundle\SonataTwigBundle::class => ['all' => true],
    ];
