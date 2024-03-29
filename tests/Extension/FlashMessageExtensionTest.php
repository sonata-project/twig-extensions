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

namespace Sonata\Twig\Tests\Extension;

use PHPUnit\Framework\TestCase;
use Sonata\Twig\Extension\FlashMessageExtension;
use Twig\TwigFunction;

final class FlashMessageExtensionTest extends TestCase
{
    private FlashMessageExtension $extension;

    protected function setUp(): void
    {
        $this->extension = new FlashMessageExtension();
    }

    public function testFunctionsArePrefixed(): void
    {
        foreach ($this->extension->getFunctions() as $function) {
            static::assertSame(
                0,
                strpos($function->getName(), 'sonata_flashmessages_'),
                'All function names should start with a standard prefix'
            );
        }
    }

    public function testGetFunctions(): void
    {
        $filters = $this->extension->getFunctions();

        static::assertContainsOnlyInstancesOf(TwigFunction::class, $filters);
    }
}
