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
     * @var LegacyTranslatorInterface|TranslatorInterface|null
     *
     * @deprecated translator property is deprecated since sonata-project/twig-extensions 0.x, to be removed in 1.0
     */
    protected $translator;

    /**
     * @param AbstractExpression                                        $message                              Node message to display
     * @param AbstractExpression|bool                                   $deprecatedTranslationBundleOrEnabled Node translation bundle to use for display
     * @param int                                                       $deprecatedEnabledOrLineno            Is Symfony debug enabled?
     * @param LegacyTranslatorInterface|TranslatorInterface|string|null $deprecatedTranslatorOrTag
     * @param int|string|null                                           $deprecatedLineno                     Symfony template line number
     * @param string|null                                               $deprecatedTag                        Symfony tag name
     */
    public function __construct(
        AbstractExpression $message,
        $deprecatedTranslationBundleOrEnabled,
        $deprecatedEnabledOrLineno,
        $deprecatedTranslatorOrTag,
        $deprecatedLineno = null,
        $deprecatedTag = null
    ) {
        if ($deprecatedTranslatorOrTag instanceof LegacyTranslatorInterface || $deprecatedTranslatorOrTag instanceof TranslatorInterface) {
            $this->deprecatedConstructor(
                $message,
                $deprecatedTranslationBundleOrEnabled,
                $deprecatedEnabledOrLineno,
                $deprecatedTranslatorOrTag,
                $deprecatedLineno,
                $deprecatedTag
            );

            @trigger_error(
                'The translator dependency in '.__CLASS__.' is deprecated since 0.x and will be removed in 1.0. '.
                'Please prepare your dependencies for this change.',
                E_USER_DEPRECATED
            );
        } else {
            $this->constructor(
                $message,
                $deprecatedTranslationBundleOrEnabled,
                $deprecatedEnabledOrLineno,
                $deprecatedTranslatorOrTag
            );
        }
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

        if (null !== $this->translator) {
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
    <div>{$this->translator->trans('sonata_core_template_box_file_found_in', [], 'SonataCoreBundle')} <code>{\$this->getTemplateName()}</code>.</div>
</div>"
CODE;
        } else {
            $message = <<<CODE
"<div class='alert alert-default alert-info'>
    <strong>{$value}</strong>
    <div>This file can be found in <code>{\$this->getTemplateName()}</code>.</div>
</div>"
CODE;
        }

        $compiler
            ->write("echo $message;");
    }

    /**
     * @deprecated since sonata-project/twig-extensions 0.x, to be removed with 1.0
     *
     * @param AbstractExpression      $message           Node message to display
     * @param AbstractExpression|null $translationBundle Node translation bundle to use for display
     * @param int                     $enabled           Is Symfony debug enabled?
     * @param string|null             $lineno            Symfony template line number
     * @param string|null             $tag               Symfony tag name
     */
    private function deprecatedConstructor(
        AbstractExpression $message,
        ?AbstractExpression $translationBundle = null,
        $enabled,
        TranslatorInterface $translator,
        $lineno,
        ?string $tag = null
    ) {
        $this->enabled = $enabled;
        $this->translator = $translator;

        $nodes = ['message' => $message];

        if ($translationBundle) {
            $nodes['translationBundle'] = $translationBundle;
        }

        parent::__construct($nodes, [], $lineno, $tag);
    }

    /**
     * @param AbstractExpression $message Node message to display
     * @param bool               $enabled Is Symfony debug enabled?
     * @param int                $lineno  Symfony template line number
     * @param ?string            $tag     Symfony tag name
     */
    private function constructor(AbstractExpression $message, bool $enabled, ?int $lineno, ?string $tag = null)
    {
        $this->enabled = $enabled;

        parent::__construct(['message' => $message], [], $lineno, $tag);
    }
}
