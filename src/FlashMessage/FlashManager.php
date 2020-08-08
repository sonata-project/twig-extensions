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

use Sonata\Twig\Status\StatusClassRendererInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @author Vincent Composieux <composieux@ekino.com>
 *
 * NEXT_MAJOR: remove StatusClassRendererInterface implementation
 */
final class FlashManager implements FlashManagerInterface, StatusClassRendererInterface
{
    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var array
     */
    private $types;

    /**
     * @var array
     */
    private $cssClasses;

    /**
     * @param array $types      Sonata flash message types array (defined in configuration)
     * @param array $cssClasses Css classes associated with $types
     */
    public function __construct(SessionInterface $session, array $types, array $cssClasses)
    {
        $this->session = $session;
        $this->types = $types;
        $this->cssClasses = $cssClasses;
    }

    /**
     * Tells if class may handle $object for status class rendering.
     *
     * @deprecated since sonata-project/twig-extensions 1.4, will be removed in 2.0. Use handlesType() instead.
     *
     * NEXT_MAJOR: remove this method
     *
     * @param object|string $object     FlashManager or Sonata flash message type
     * @param string|null   $statusName Sonata flash message type
     *
     * @return bool
     */
    public function handlesObject($object, ?string $statusName = null)
    {
        @trigger_error(sprintf(
            'The "%s()" method is deprecated since sonata-project/twig-extensions 1.4'
            .' and will be removed in version 2.0. Use "%s" instead.',
            __METHOD__,
            'handlesType()'
        ), E_USER_DEPRECATED);

        if (\is_string($object)) {
            if (null === $statusName) {
                $statusName = $object;
            }
            $object = $this;
        }

        if (!$object instanceof self) {
            return false;
        }

        return $this->handlesType($statusName);
    }

    /**
     * Returns the status CSS class for $object.
     *
     * @deprecated since sonata-project/twig-extensions 1.4, will be removed in 2.0. Use getRenderedHtmlClassAttribute() instead.
     *
     * NEXT_MAJOR: remove this method
     *
     * @param object|string $object     FlashManager or Sonata flash message type
     * @param string|null   $statusName Sonata flash message type
     * @param string        $default    Default status class if Sonata flash message type do not exist
     *
     * @return string
     */
    public function getStatusClass($object, ?string $statusName = null, string $default = '')
    {
        @trigger_error(sprintf(
            'The "%s()" method is deprecated since sonata-project/twig-extensions 1.4'
            .' and will be removed in 2.0. Use "%s" instead.',
            __METHOD__,
            'getRenderedHtmlClassAttribute()'
        ), E_USER_DEPRECATED);

        if (\is_string($object)) {
            if (null === $statusName) {
                $statusName = $object;
            }
        }

        return $this->getRenderedHtmlClassAttribute($statusName, $default);
    }

    /**
     * Returns Sonata flash message types.
     */
    public function getTypes(): array
    {
        return $this->types;
    }

    /**
     * Returns Symfony session service.
     */
    public function getSession(): SessionInterface
    {
        return $this->session;
    }

    /**
     * Returns flash bag messages for correct type after renaming with Sonata flash message type.
     */
    public function get(string $type): array
    {
        $this->handle();

        return $this->getSession()->getFlashBag()->get($type);
    }

    /**
     * Gets handled Sonata flash message types.
     */
    public function getHandledTypes(): array
    {
        return array_keys($this->getTypes());
    }

    public function getRenderedHtmlClassAttribute(string $type, string $default = ''): string
    {
        return \array_key_exists($type, $this->cssClasses)
            ? $this->cssClasses[$type]
            : $default;
    }

    public function handlesType(string $type): bool
    {
        return \array_key_exists($type, $this->cssClasses);
    }

    /**
     * Add flash message to session.
     */
    public function addFlash(string $type, string $message): void
    {
        $this->session->getFlashBag()->add($type, $message);
    }

    /**
     * Handles flash bag types renaming.
     */
    private function handle(): void
    {
        foreach ($this->getTypes() as $type => $values) {
            foreach ($values as $value => $options) {
                $this->rename($type, $value);
            }
        }
    }

    /**
     * Process Sonata flash message type rename.
     *
     * @param string $type  Sonata flash message type
     * @param string $value Original flash message type
     */
    private function rename(string $type, string $value): void
    {
        $flashBag = $this->getSession()->getFlashBag();

        foreach ($flashBag->get($value) as $message) {
            $flashBag->add($type, $message);
        }
    }
}
