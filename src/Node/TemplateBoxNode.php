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

namespace Sonata\TwigExtensions\Node;

use Twig\Compiler;
use Twig\Node\Expression\AbstractExpression;
use Twig\Node\Node;

class TemplateBoxNode extends Node
{
    /**
     * @var bool
     */
    protected $enabled;

    /**
     * @param AbstractExpression $message Node message to display
     * @param int                $enabled Is Symfony debug enabled?
     * @param null|string        $lineno  Symfony template line number
     * @param null               $tag     Symfony tag name
     */
    public function __construct(AbstractExpression $message, bool $enabled, ?string $line, ?string $tag = null)
    {
        $this->enabled = $enabled;

        parent::__construct(['message' => $message], [], $line, $tag);
    }

    public function compile(Compiler $compiler): void
    {
        $compiler
            ->addDebugInfo($this);

        if (!$this->enabled) {
            $compiler->write("// token for sonata_template_box, however the box is disabled\n");

            return;
        }

        $value = $this->getNode('message')->getAttribute('value');

        $message = <<<CODE
"<div class='alert alert-default alert-info'>
    <strong>{$value}</strong>
    <div>This file can be found in <code>{\$this->getTemplateName()}</code>.</div>
</div>"
CODE;

        $compiler
            ->write("echo $message;");
    }
}
