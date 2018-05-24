<?php
declare(strict_types=1);

namespace Enm\ExternalLayout\Manipulator;

use Enm\ExternalLayout\Model\ValueObject\BlockInterface;
use Enm\ExternalLayout\Model\ValueObject\LayoutInterface;
use Symfony\Component\CssSelector\CssSelectorConverter;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class TwigManipulator implements ManipulatorInterface
{
    /**
     * Manipulates the layout document with the requirements of the layout
     *
     * @param \DOMDocument $document
     * @param LayoutInterface $layout
     */
    public function manipulate(\DOMDocument $document, LayoutInterface $layout): void
    {
        foreach ($layout->getBlocks() as $block) {
            switch ($block->getType()) {
                case BlockInterface::PREPEND:
                    $this->prependBlock($document, $block);
                    break;
                case BlockInterface::APPEND:
                    $this->appendBlock($document, $block);
                    break;
                case BlockInterface::REPLACE:
                    $this->replaceBlock($document, $block);
                    break;
            }
        }
    }

    /**
     * @param \DOMDocument $html
     * @param BlockInterface $block
     */
    protected function prependBlock(\DOMDocument $html, BlockInterface $block): void
    {
        $node = $this->getSelectedNode($html, $block->getElement());

        $node->insertBefore(
            $html->createElement(self::WORKING_TAG, $this->buildTwigBlock($block->getName())),
            $node->firstChild
        );
    }

    /**
     * @param \DOMDocument $html
     * @param BlockInterface $block
     */
    protected function appendBlock(\DOMDocument $html, BlockInterface $block): void
    {
        $node = $this->getSelectedNode($html, $block->getElement());

        $node->appendChild(
            $html->createElement(self::WORKING_TAG, $this->buildTwigBlock($block->getName()))
        );
    }

    /**
     * @param \DOMDocument $html
     * @param BlockInterface $block
     */
    protected function replaceBlock(\DOMDocument $html, BlockInterface $block): void
    {
        $html->loadHTML(
            str_replace(
                $block->getElement(),
                '<' . self::WORKING_TAG . '>' . $this->buildTwigBlock($block->getName()) . '</' . self::WORKING_TAG . '>',
                $html->saveHTML()
            )
        );
    }

    /**
     * @param string $block
     *
     * @return string
     */
    protected function buildTwigBlock(string $block): string
    {
        return '{% block ' . $block . ' %}{% endblock %}';
    }

    /**
     * @param \DOMDocument $dom
     * @param string $selector
     *
     * @return \DOMNode
     */
    protected function getSelectedNode(\DOMDocument $dom, string $selector): \DOMNode
    {
        $xPath = new \DOMXPath($dom);

        return $xPath->query((new CssSelectorConverter())->toXPath($selector))->item(0);
    }
}
