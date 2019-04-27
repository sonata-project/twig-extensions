.. index::
    single: Installation

Installation
============

The easiest way to install ``twig-extensions`` is to require it with Composer:

.. code-block:: bash

    $ composer require sonata-project/twig-extensions

Alternatively, you could add a dependency into your ``composer.json`` file directly.

Now, enable the bundle in ``bundles.php`` file:

.. code-block:: php

    <?php

    // config/bundles.php

    return [
        //...
        Sonata\Twig\Bridge\Symfony\Bundle\SonataTwigBundle::class => ['all' => true],
    ];

.. note::
    If you are not using Symfony Flex, you should enable bundles in your
    ``AppKernel.php``.

.. code-block:: php

    <?php
    // app/AppKernel.php

    public function registerBundles()
    {
        return array(
            // ...
            new Sonata\Twig\Bridge\Symfony\Bundle\SonataTwigBundle(),
            // ...
        );
    }
