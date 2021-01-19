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
use Sonata\Twig\FlashMessage\FlashManagerInterface;
use Sonata\Twig\Status\StatusClassRendererInterface;

/**
 * @author Hugo Briand <briand@ekino.com>
 * @author Titouan Galopin <galopintitouan@gmail.com>
 */
final class StatusRuntime
{
    /**
     * @var StatusClassRendererInterface[]
     */
    private $statusServices = [];

    /**
     * Adds a renderer to the status services list.
     */
    public function addStatusService(StatusClassRendererInterface $renderer): void
    {
        $this->statusServices[] = $renderer;
    }

    /**
     * @param object|string $object     Object for StatusClassRenderer or string for FlashManager
     * @param string|null   $statusType Object status type or Sonata flash message type
     * @param string        $default    Default status class
     * @param mixed         $statusType
     */
    public function statusClass($object, $statusType = null, string $default = ''): string
    {
        if ($object instanceof FlashManagerInterface) {
            return $this->statusClassForFlashManager($statusType, null, $default);
        }

        if (\is_object($object)) {
            return $this->statusClassForStatusClassRenderer($object, $statusType, $default);
        }

        if (\is_string($object)) {
            return $this->statusClassForFlashManager($object, $statusType, $default);
        }

        @trigger_error(sprintf(
            'Passing other type than object or string as argument 1 for "%s()" is deprecated since sonata-project/twig-extensions 1.4'
            .' and will throw an exception in 2.0.',
            __METHOD__
        ), \E_USER_DEPRECATED);

        return $default;
    }

    private function statusClassForStatusClassRenderer(object $object, $statusType = null, string $default = ''): string
    {
        foreach ($this->statusServices as $statusService) {
            \assert($statusService instanceof StatusClassRendererInterface);

            if ($statusService->handlesObject($object, $statusType)) {
                return $statusService->getStatusClass($object, $statusType, $default);
            }
        }

        return $default;
    }

    private function statusClassForFlashManager(string $object, ?string $statusType = null, string $default = ''): string
    {
        if ($flashManager = $this->getFlashManagerFromStatusServices()) {
            if (null === $statusType) {
                $statusType = $object;
            }

            if ($flashManager->handlesType($statusType)) {
                return $flashManager->getRenderedHtmlClassAttribute($statusType, $default);
            }
        }

        return $default;
    }

    /**
     * Get FlashManager if it is registered as StatusClassRenderer.
     */
    private function getFlashManagerFromStatusServices(): ?FlashManager
    {
        foreach ($this->statusServices as $statusService) {
            if ($statusService instanceof FlashManager) {
                return $statusService;
            }
        }

        return null;
    }
}
