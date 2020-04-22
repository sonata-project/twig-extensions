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
     * @var LegacyTranslatorInterface|TranslatorInterface|null
     *
     * @deprecated translator property is deprecated since sonata-project/twig-extensions 0.x, to be removed in 1.0
     */
    protected $translator;

    /**
     * @param bool                                                                $debug                              Is Symfony debug enabled?
     * @param LegacyTranslatorInterface|TranslatorInterface|AdapterInterface|null $deprecatedTranslatorOrModelAdapter
     */
    public function __construct($debug, $deprecatedTranslatorOrModelAdapter = null, ?AdapterInterface $deprecatedModelAdapter = null)
    {
        $this->debug = $debug;

        if (
            !$deprecatedTranslatorOrModelAdapter instanceof LegacyTranslatorInterface &&
            !$deprecatedTranslatorOrModelAdapter instanceof TranslatorInterface &&
            !$deprecatedTranslatorOrModelAdapter instanceof AdapterInterface &&
            null !== $deprecatedTranslatorOrModelAdapter
        ) {
            throw new \InvalidArgumentException(sprintf(
                'Argument 2 should be an instance of %s or %s or %s or %s',
                LegacyTranslatorInterface::class,
                TranslatorInterface::class,
                'null',
                AdapterInterface::class
            ));
        }

        if (!$deprecatedTranslatorOrModelAdapter instanceof AdapterInterface && !$deprecatedModelAdapter instanceof AdapterInterface) {
            throw new \InvalidArgumentException(sprintf(
                'Argument 3 should be an instance of %s, %s given.',
                AdapterInterface::class,
                \get_class($deprecatedModelAdapter)
            ));
        }

        if ($deprecatedTranslatorOrModelAdapter instanceof AdapterInterface) {
            $this->modelAdapter = $deprecatedTranslatorOrModelAdapter;
        } else {
            $this->translator = $deprecatedTranslatorOrModelAdapter;
            $this->modelAdapter = $deprecatedModelAdapter;

            @trigger_error(
                'The translator dependency in '.__CLASS__.' is deprecated since 0.x and will be removed in 1.0. '.
                'Please prepare your dependencies for this change.',
                E_USER_DEPRECATED
            );
        }
    }

    /**
     * @return TwigFilter[]
     */
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
