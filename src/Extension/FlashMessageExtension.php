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

namespace Sonata\TwigExtensions\Extension;

use Sonata\TwigExtensions\FlashMessage\FlashManager;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * This is the Sonata core flash message Twig extension.
 *
 * @author Vincent Composieux <composieux@ekino.com>
 */
class FlashMessageExtension extends AbstractExtension
{
    /**
     * @var FlashManager
     */
    protected $flashManager;

    public function __construct(FlashManager $flashManager)
    {
        $this->flashManager = $flashManager;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('sonata_flashmessages_get', [$this, 'getFlashMessages']),
            new TwigFunction('sonata_flashmessages_types', [$this, 'getFlashMessagesTypes']),
        ];
    }

    /**
     * Returns flash messages handled by Sonata core flash manager.
     */
    public function getFlashMessages(string $type, string $domain = null): string
    {
        return $this->flashManager->get($type, $domain);
    }

    /**
     * Returns flash messages types handled by Sonata core flash manager.
     */
    public function getFlashMessagesTypes(): string
    {
        return $this->flashManager->getHandledTypes();
    }

    public function getName(): string
    {
        return 'sonata_core_flashmessage';
    }
}
