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
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

final class TemplateExtension extends AbstractExtension
{
    /**
     * @var bool
     */
    protected $debug;

    /**
     * NEXT_MAJOR: Remove this property.
     *
     * @var AdapterInterface
     */
    protected $modelAdapter;

    /**
     * NEXT_MAJOR: Remove the second argument.
     *
     * @param bool             $debug        Is Symfony debug enabled?
     * @param AdapterInterface $modelAdapter A Sonata model adapter
     */
    public function __construct(bool $debug, AdapterInterface $modelAdapter)
    {
        $this->debug = $debug;
        $this->modelAdapter = $modelAdapter;
    }

    /**
     * NEXT_MAJOR: Remove this method.
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('sonata_urlsafeid', [$this, 'getUrlsafeIdentifier']),
        ];
    }

    public function getTokenParsers(): array
    {
        return [
            new TemplateBoxTokenParser($this->debug),
        ];
    }

    /**
     * NEXT_MAJOR: Remove this method.
     *
     * @deprecated since sonata-project/twig-extensions 1.7, to be removed in 2.0.
     *
     * @param object $model
     */
    public function getUrlsafeIdentifier($model): ?string
    {
        @trigger_error(sprintf(
            'Method "%s()" is deprecated since sonata-project/twig-extension 1.7 in favor of the "sonata_urlsafeid"'
            .' Twig filter defined by SonataAdminBundle and will be removed in version 2.0.'
            .' You can solve this deprecation by enabling this bundle before SonataAdminBundle.',
            __METHOD__
        ), \E_USER_DEPRECATED);

        return $this->modelAdapter->getUrlsafeIdentifier($model);
    }

    public function getName(): string
    {
        return 'sonata_twig_template';
    }
}
