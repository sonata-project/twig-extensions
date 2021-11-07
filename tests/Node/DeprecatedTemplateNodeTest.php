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

namespace Sonata\Twig\Tests\Node;

use Sonata\Twig\Node\DeprecatedTemplateNode;
use Twig\Environment;
use Twig\Node\Expression\ConstantExpression;
use Twig\Node\Node;
use Twig\Test\NodeTestCase;

/**
 * @author Marko Kunic <kunicmarko20@gmail.com>
 *
 * NEXT_MAJOR: Remove this test
 *
 * @group legacy
 *
 * @psalm-suppress DeprecatedClass
 */
final class DeprecatedTemplateNodeTest extends NodeTestCase
{
    public function testConstructor(): void
    {
        $body = $this->getNode();

        static::assertSame(1, $body->getTemplateLine());
    }

    /**
     * @param mixed $node
     * @param mixed $source
     * @param mixed $environment
     * @param mixed $isPattern
     *
     * @expectedDeprecation The "" template is deprecated. Use "new.html.twig" instead.
     * @group legacy
     * @dataProvider getTests
     */
    public function testCompile($node, $source, $environment = null, $isPattern = false): void
    {
        parent::testCompile($node, $source, $environment, $isPattern);
    }

    /**
     * @return iterable<array-key, array{Node, string, Environment|null, bool}>
     */
    public function getTests(): iterable
    {
        return [
            [$this->getNode(), '', null, false],
        ];
    }

    private function getNode(): Node
    {
        return new DeprecatedTemplateNode(
            new ConstantExpression('new.html.twig', 1),
            1,
            'sonata_template_deprecate'
        );
    }
}
