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

namespace Sonata\Twig\Node;

use Symfony\Component\Translation\TranslatorInterface as LegacyTranslatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Compiler;
use Twig\Node\Expression\AbstractExpression;
use Twig\Node\Node;

/**
 * @final since sonata-project/twig-extensions 0.x
 */
class TemplateBoxNode extends Node
{
    /**
     * @var int
     */
    protected $enabled;

    /**
     * NEXT_MAJOR: remove this property.
     *
     * @deprecated translator property is deprecated since sonata-project/twig-extensions 0.x, to be removed in 1.0
     *
     * @var LegacyTranslatorInterface|TranslatorInterface
     */
    protected $translator;

    /**
     * @param AbstractExpression $message           Node message to display
     * @param AbstractExpression $translationBundle Node translation bundle to use for display
     * @param int                $enabled           Is Symfony debug enabled?
     * @param string|null        $lineno            Symfony template line number
     * @param null               $tag               Symfony tag name
     */
    public function __construct(
        AbstractExpression $message,
        ?AbstractExpression $translationBundle = null,
        $enabled,
        $translator,
        $lineno,
        $tag = null
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

        $this->enabled = $enabled;
        $this->translator = $translator;

        $nodes = ['message' => $message];

        if ($translationBundle) {
            $nodes['translationBundle'] = $translationBundle;
        }

        parent::__construct($nodes, [], $lineno, $tag);
    }

    public function compile(Compiler $compiler)
    {
        $compiler
            ->addDebugInfo($this);

        if (!$this->enabled) {
            $compiler->write("// token for sonata_template_box, however the box is disabled\n");

            return;
        }

        $value = $this->getNode('message')->getAttribute('value');

        $translationBundle = null;

        if ($this->hasNode('translationBundle')) {
            $translationBundle = $this->getNode('translationBundle');
        }

        if ($translationBundle) {
            $translationBundle = $translationBundle->getAttribute('value');
        }

        $message = <<<CODE
"<div class='alert alert-default alert-info'>
    <strong>{$this->translator->trans($value, [], $translationBundle)}</strong>
    <div>{$this->translator->trans('sonata_core_template_box_file_found_in', [], 'SonataTwigBundle')} <code>{\$this->getTemplateName()}</code>.</div>
</div>"
CODE;

        $compiler
            ->write("echo $message;");
    }
}
