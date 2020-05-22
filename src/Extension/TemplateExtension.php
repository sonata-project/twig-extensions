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

use Sonata\Doctrine\Adapter\AdapterInterface;
use Sonata\Twig\TokenParser\TemplateBoxTokenParser;
use Symfony\Component\Translation\TranslatorInterface as LegacyTranslatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * @final since sonata-project/twig-extensions 0.x
 */
class TemplateExtension extends AbstractExtension
{
    /**
     * @var bool
     */
    protected $debug;

    /**
     * @var AdapterInterface
     */
    protected $modelAdapter;

    /**
     * NEXT_MAJOR: remove this property.
     *
     * @deprecated translator property is deprecated since sonata-project/twig-extensions 0.x, to be removed in 1.0
     *
     * @var LegacyTranslatorInterface|TranslatorInterface
     */
    protected $translator;

    /**
     * @param bool                                          $debug      Is Symfony debug enabled?
     * @param LegacyTranslatorInterface|TranslatorInterface $translator
     */
    public function __construct($debug, $translator, AdapterInterface $modelAdapter)
    {
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

        $this->debug = $debug;
        $this->translator = $translator;
        $this->modelAdapter = $modelAdapter;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('sonata_slugify', [$this, 'slugify'], ['deprecated' => true, 'alternative' => 'slugify']),
            new TwigFilter('sonata_urlsafeid', [$this, 'getUrlsafeIdentifier']),
        ];
    }

    /**
     * @return array
     */
    public function getTokenParsers()
    {
        return [
            new TemplateBoxTokenParser($this->debug, $this->translator),
        ];
    }

    /**
     * Slugify a text.
     *
     * @deprecated Twig filter "sonata_slugify" is deprecated since sonata-project/twig-extensions 0.x, to be removed in 1.0. Use "slugify" instead.
     *
     * @param string $text
     *
     * @return string|null
     */
    public function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);

        // trim
        $text = trim($text, '-');

        // transliterate
        if (\function_exists('iconv')) {
            $text = iconv('UTF-8', 'ASCII//TRANSLIT', $text);
        }

        // lowercase
        $text = strtolower($text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        return $text;
    }

    /**
     * @param object $model
     *
     * @return string
     */
    public function getUrlsafeIdentifier($model)
    {
        return $this->modelAdapter->getUrlsafeIdentifier($model);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sonata_twig_template';
    }
}
