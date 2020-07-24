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

use Sonata\Twig\FlashMessage\FlashManagerInterface;

/**
 * This is the Sonata flash message Twig runtime.
 *
 * @author Vincent Composieux <composieux@ekino.com>
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */
final class FlashMessageRuntime
{
    /**
     * @var FlashManagerInterface
     */
    private $flashManager;

    public function __construct(FlashManagerInterface $flashManager)
    {
        $this->flashManager = $flashManager;
    }

    /**
     * Returns flash messages handled by Sonata flash manager.
     */
    public function getFlashMessages(string $type): array
    {
        return $this->flashManager->get($type);
    }

    /**
     * Returns Sonata flash messages types handled by Sonata flash manager.
     */
    public function getFlashMessagesTypes(): array
    {
        return $this->flashManager->getHandledTypes();
    }

    /**
     * Returns Sonata flash message css class.
     */
    public function getFlashMessagesClass(string $type, string $default = ''): string
    {
        return $this->flashManager->getRenderedHtmlClassAttribute($type, $default);
    }
}
