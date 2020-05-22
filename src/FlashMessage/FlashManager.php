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
use Symfony\Component\Translation\TranslatorInterface as LegacyTranslatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author Vincent Composieux <composieux@ekino.com>
 *
 * @final since sonata-project/twig-extensions 0.x
 */
class FlashManager implements StatusClassRendererInterface
{
    /**
     * @var SessionInterface
     */
    protected $session;

    /**
     * NEXT_MAJOR: remove this property.
     *
     * @var LegacyTranslatorInterface|TranslatorInterface
     *
     * @deprecated translator property is deprecated since sonata-project/twig-extensions 0.x, to be removed in 1.0
     */
    protected $translator;

    /**
     * @var array
     */
    protected $types;

    /**
     * @var array
     */
    protected $cssClasses;

    /**
     * @param LegacyTranslatorInterface|TranslatorInterface $translator
     * @param array                                         $types      Sonata core types array (defined in configuration)
     * @param array                                         $cssClasses Css classes associated with $types
     */
    public function __construct(
        SessionInterface $session,
        $translator,
        array $types,
        array $cssClasses
    ) {
        if (
            !$translator instanceof LegacyTranslatorInterface &&
            !$translator instanceof TranslatorInterface
        ) {
            throw new \InvalidArgumentException(sprintf(
                'Argument 2 should be an instance of %s or %s',
                LegacyTranslatorInterface::class,
                TranslatorInterface::class
            ));
        }

        $this->session = $session;
        $this->translator = $translator;
        $this->types = $types;
        $this->cssClasses = $cssClasses;
    }

    /**
     * @param mixed       $object
     * @param string|null $statusName
     *
     * @return bool
     */
    public function handlesObject($object, $statusName = null)
    {
        return \is_string($object) && \array_key_exists($object, $this->cssClasses);
    }

    /**
     * @param mixed       $object
     * @param string|null $statusName
     * @param string      $default
     *
     * @return string
     */
    public function getStatusClass($object, $statusName = null, $default = '')
    {
        return \array_key_exists($object, $this->cssClasses)
            ? $this->cssClasses[$object]
            : $default;
    }

    /**
     * Returns Sonata flash message types.
     *
     * @return array
     */
    public function getTypes()
    {
        return $this->types;
    }

    /**
     * Returns Symfony session service.
     *
     * @return SessionInterface
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * Returns Symfony translator service.
     *
     * @return LegacyTranslatorInterface|TranslatorInterface
     */
    public function getTranslator()
    {
        return $this->translator;
    }

    /**
     * Returns flash bag messages for correct type after renaming with Sonata type.
     *
     * @param string $type             Type of flash message
     * @param string $deprecatedDomain Translation domain to use
     *
     * @return array
     */
    public function get($type, $deprecatedDomain = null)
    {
        $this->handle($deprecatedDomain);

        return $this->getSession()->getFlashBag()->get($type);
    }

    /**
     * Gets handled message types.
     *
     * @return array
     */
    public function getHandledTypes()
    {
        return array_keys($this->getTypes());
    }

    /**
     * Handles flash bag types renaming.
     *
     * @param string $deprecatedDomain
     */
    protected function handle($deprecatedDomain = null)
    {
        foreach ($this->getTypes() as $type => $values) {
            foreach ($values as $value => $options) {
                $domainType = $deprecatedDomain ?: ($options['domain'] ?? null);
                $this->rename($type, $value, $domainType);
            }
        }
    }

    /**
     * Process flash message type rename.
     *
     * @param string      $type             Sonata flash message type
     * @param string      $value            Original flash message type
     * @param string|null $deprecatedDomain Translation domain to use
     */
    protected function rename($type, $value, $deprecatedDomain = null)
    {
        $flashBag = $this->getSession()->getFlashBag();

        foreach ($flashBag->get($value) as $message) {
            if (null !== $this->getTranslator() && null !== $deprecatedDomain) {
                $message = $this->getTranslator()->trans($message, [], $deprecatedDomain);
            }
            $flashBag->add($type, $message);
        }
    }
}
