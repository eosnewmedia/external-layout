<?php
declare(strict_types=1);

namespace Enm\ExternalLayout\Manipulator;

use Enm\ExternalLayout\Model\ValueObject\LayoutInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class BaseUrlManipulator implements ManipulatorInterface
{
    /**
     * Removes the base tag from document
     *
     * @param \DOMDocument $document
     * @param LayoutInterface $layout
     */
    public function manipulate(\DOMDocument $document, LayoutInterface $layout): void
    {
        $head = $document->getElementsByTagName('head')->item(0);
        $head->removeChild($head->getElementsByTagName('base')->item(0));
    }
}
