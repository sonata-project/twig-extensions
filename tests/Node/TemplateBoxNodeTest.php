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

use Sonata\Twig\Node\TemplateBoxNode;
use Twig\Environment;
use Twig\Node\Expression\ConstantExpression;
use Twig\Node\Node;
use Twig\Test\NodeTestCase;

final class TemplateBoxNodeTest extends NodeTestCase
{
    public function testConstructor(): void
    {
        $body = new TemplateBoxNode(
            new ConstantExpression('This is the default message', 1),
            true,
            1,
            'sonata_template_box'
        );

        static::assertSame(1, $body->getTemplateLine());
    }

    /**
     * @param mixed $node
     * @param mixed $source
     * @param mixed $environment
     * @param mixed $isPattern
     *
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
        $nodeEn = new TemplateBoxNode(
            new ConstantExpression('This is the default message', 1),
            true,
            1,
            'sonata_template_box'
        );

        $nodeFr = new TemplateBoxNode(
            new ConstantExpression('Ceci est le message par défaut', 1),
            true,
            1,
            'sonata_template_box'
        );

        return [
            [$nodeEn, <<<'EOF'
// line 1
echo "<div class='alert alert-default alert-info'>
    <strong>This is the default message</strong>
    <div>This file can be found in <code>{$this->getTemplateName()}</code>.</div>
</div>";
EOF, null, false,
            ],
            [$nodeFr, <<<'EOF'
// line 1
echo "<div class='alert alert-default alert-info'>
    <strong>Ceci est le message par défaut</strong>
    <div>This file can be found in <code>{$this->getTemplateName()}</code>.</div>
</div>";
EOF, null, false,
            ],
        ];
    }
}
