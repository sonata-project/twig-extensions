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

namespace Sonata\Twig\Status;

/**
 * @author Hugo Briand <briand@ekino.com>
 */
interface StatusClassRendererInterface
{
    /**
     * Tells if class may handle $object for status class rendering.
     */
    public function handlesObject(object $object, ?string $statusName = null): bool;

    /**
     * Returns the status CSS class for $object.
     */
    public function getStatusClass(object $object, ?string $statusName = null, string $default = ''): string;
}
