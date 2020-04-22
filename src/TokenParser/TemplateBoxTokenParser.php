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

namespace Sonata\Twig\TokenParser;

use Sonata\Twig\Node\TemplateBoxNode;
use Symfony\Component\Translation\TranslatorInterface as LegacyTranslatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Node\Expression\ConstantExpression;
use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;

/**
 * @final since sonata-project/twig-extensions 0.x
 */
class TemplateBoxTokenParser extends AbstractTokenParser
{
    /**
     * @var bool
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
     * @param bool $enabled Is Symfony debug enabled?
     */
    public function __construct($enabled, $deprecatedTranslator = null)
    {
        $this->enabled = $enabled;
        $this->translator = $deprecatedTranslator;

        if (null !== $deprecatedTranslator) {
            @trigger_error(
                'The translator dependency in '.__CLASS__.' is deprecated since 0.x and will be removed in 1.0. '.
                'Please prepare your dependencies for this change.',
                E_USER_DEPRECATED
            );
        }
    }

    /**
     * @throws \Twig\Error\SyntaxError,
     *
     * @return TemplateBoxNode
     */
    public function parse(Token $token)
    {
        if ($this->parser->getStream()->test(Token::STRING_TYPE)) {
            $message = $this->parser->getExpressionParser()->parseExpression();
        } else {
            $message = new ConstantExpression('Template information', $token->getLine());
        }

        $this->parser->getStream()->expect(Token::BLOCK_END_TYPE);

        if (null !== $this->translator) {
            if ($this->parser->getStream()->test(Token::STRING_TYPE)) {
                $translationBundle = $this->parser->getExpressionParser()->parseExpression();
            } else {
                $translationBundle = null;
            }

            return new TemplateBoxNode($message, $translationBundle, $this->enabled, $this->translator, $token->getLine(), $this->getTag());
        }

        return new TemplateBoxNode($message, $this->enabled, $token->getLine(), $this->getTag());
    }

    /**
     * @return string
     */
    public function getTag()
    {
        return 'sonata_template_box';
    }
}
