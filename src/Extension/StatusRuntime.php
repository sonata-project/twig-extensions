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
     * @param object $object
     * @param mixed  $statusType
     */
    public function statusClass($object, $statusType = null, string $default = ''): string
    {
        foreach ($this->statusServices as $statusService) {
            \assert($statusService instanceof StatusClassRendererInterface);

            if ($statusService->handlesObject($object, $statusType)) {
                return $statusService->getStatusClass($object, $statusType, $default);
            }
        }

        return $default;
    }
}
