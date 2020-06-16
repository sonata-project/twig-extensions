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

namespace Sonata\Twig\FlashMessage;

interface FlashManagerInterface
{
    /**
     * Tells if class may handle $type for Sonata flash message type.
     */
    public function handlesType(string $type): bool;

    /**
     * Returns the Sonata flash message CSS class.
     */
    public function getRenderedHtmlClassAttribute(string $type, string $default = ''): string;

    /**
     * Returns Sonata flash message types.
     */
    public function getTypes(): array;

    /**
     * Returns flash messages for correct type.
     */
    public function get(string $type): array;

    /**
     * Gets handled Sonata flash message types.
     */
    public function getHandledTypes(): array;

    /**
     * Add flash message.
     */
    public function addFlash(string $type, string $message): void;
}
