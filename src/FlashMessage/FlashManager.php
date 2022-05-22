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

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @author Vincent Composieux <composieux@ekino.com>
 */
final class FlashManager implements FlashManagerInterface
{
    /**
     * @param array<string, array<string>> $types      Sonata flash message types array (defined in configuration)
     * @param array<string, string>        $cssClasses Css classes associated with $types
     */
    public function __construct(
        private RequestStack $requestStack,
        private array $types,
        private array $cssClasses
    ) {
    }

    /**
     * Returns Sonata flash message types.
     */
    public function getTypes(): array
    {
        return $this->types;
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
        $this->getSession()->getFlashBag()->add($type, $message);
    }

    /**
     * Handles flash bag types renaming.
     */
    private function handle(): void
    {
        foreach ($this->getTypes() as $type => $values) {
            foreach ($values as $value) {
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

    /**
     * Returns Symfony session service.
     *
     * @return Session
     */
    private function getSession(): SessionInterface
    {
        // @phpstan-ignore-next-line
        if (method_exists($this->requestStack, 'getMainRequest')) {
            $request = $this->requestStack->getMainRequest();
        } else {
            // @phpstan-ignore-next-line
            $request = $this->requestStack->getMasterRequest();
        }

        if (null === $request) {
            throw new \RuntimeException('No request was found.');
        }

        $session = $request->getSession();
        if (!$session instanceof Session) {
            throw new \UnexpectedValueException(sprintf(
                'The flash manager only works with a "%s" session.',
                Session::class
            ));
        }

        return $session;
    }
}
