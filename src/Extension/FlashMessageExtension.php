<?php

declare(strict_types=1);

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\Twig\Extension;

use Sonata\Twig\FlashMessage\FlashManager;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * This is the Sonata flash message Twig extension.
 *
 * @author Vincent Composieux <composieux@ekino.com>
 * @author Titouan Galopin <galopintitouan@gmail.com>
 *
 * @final since sonata-project/twig-extensions 0.x
 */
class FlashMessageExtension extends AbstractExtension
{
    /**
     * @deprecated since sonata-project/twig-extensions 0.x, to be removed in 1.0.
     *
     * @var FlashManager
     */
    protected $flashManager;

    public function __construct(?FlashManager $flashManager = null)
    {
        $this->flashManager = $flashManager;

        if ($this->flashManager) {
            @trigger_error(
                'Argument "flashManager" in FlashMessageExtension is deprecated since sonata-project/twig-extensions 0.x'.
                ' and will be removed in 1.0.',
                E_USER_DEPRECATED
            );
        }
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('sonata_flashmessages_get', [FlashMessageRuntime::class, 'getFlashMessages']),
            new TwigFunction('sonata_flashmessages_types', [FlashMessageRuntime::class, 'getFlashMessagesTypes']),
        ];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sonata_twig_flashmessage';
    }
}
