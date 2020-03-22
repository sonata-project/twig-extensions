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

namespace Sonata\Twig\Tests\TokenParser;

use PHPUnit\Framework\TestCase;
use Sonata\Twig\Node\TemplateBoxNode;
use Sonata\Twig\TokenParser\TemplateBoxTokenParser;
use Twig\Environment;
use Twig\Error\SyntaxError;
use Twig\Loader\ArrayLoader;
use Twig\Node\Expression\ConstantExpression;
use Twig\Parser;
use Twig\Source;

class TemplateBoxTokenParserTest extends TestCase
{
    /**
     * @dataProvider getTestsForRender
     *
     * @param bool            $enabled
     * @param string          $source
     * @param TemplateBoxNode $expected
     *
     * @throws SyntaxError
     */
    public function testCompile($enabled, $source, $expected): void
    {
        $env = new Environment(new ArrayLoader([]), ['cache' => false, 'autoescape' => false, 'optimizations' => 0]);
        $env->addTokenParser(new TemplateBoxTokenParser($enabled));
        $source = new Source($source, 'test');
        $stream = $env->tokenize($source);
        $parser = new Parser($env);

        // "0" is passed as string due an issue with the allowed node name types.
        // @see https://github.com/twigphp/Twig/issues/3294
        $actual = $parser->parse($stream)->getNode('body')->getNode('0');
        $this->assertSame(
            $expected->getIterator()->getFlags(),
            $actual->getIterator()->getFlags()
        );
        $this->assertSame($expected->getTemplateLine(), $actual->getTemplateLine());
        $this->assertSame($expected->count(), $actual->count());
    }

    public function getTestsForRender()
    {
        return [
            [
                true,
                '{% sonata_template_box %}',
                new TemplateBoxNode(
                    new ConstantExpression('Template information', 1),
                    true,
                    1,
                    'sonata_template_box'
                ),
            ],
            [
                true,
                '{% sonata_template_box "This is the basket delivery address step page" %}',
                new TemplateBoxNode(
                    new ConstantExpression('This is the basket delivery address step page', 1),
                    true,
                    1,
                    'sonata_template_box'
                ),
            ],
            [
                false,
                '{% sonata_template_box "This is the basket delivery address step page" %}',
                new TemplateBoxNode(
                    new ConstantExpression('This is the basket delivery address step page', 1),
                    false,
                    1,
                    'sonata_template_box'
                ),
            ],
        ];
    }
}
