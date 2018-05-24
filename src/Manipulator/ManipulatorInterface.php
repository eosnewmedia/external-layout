<?php
declare(strict_types=1);

namespace Enm\ExternalLayout\Manipulator;

use Enm\ExternalLayout\Model\ValueObject\LayoutInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
interface ManipulatorInterface
{
    public const WORKING_TAG = 'externalLayout';

    /**
     * Manipulates the layout document with the requirements of the layout
     *
     * @param \DOMDocument $document
     * @param LayoutInterface $layout
     */
    public function manipulate(\DOMDocument $document, LayoutInterface $layout): void;
}
