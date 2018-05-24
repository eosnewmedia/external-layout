<?php
declare(strict_types=1);

namespace Enm\ExternalLayout\Manipulator;

use Enm\ExternalLayout\Model\ValueObject\LayoutInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class ManipulatorChain implements ManipulatorInterface
{
    /**
     * @var ManipulatorInterface[]
     */
    private $manipulators = [];

    /**
     * @param ManipulatorInterface $manipulator
     */
    public function register(ManipulatorInterface $manipulator): void
    {
        $this->manipulators[] = $manipulator;
    }

    /**
     * @param \DOMDocument $document
     * @param LayoutInterface $layout
     */
    public function manipulate(\DOMDocument $document, LayoutInterface $layout): void
    {
        foreach ($this->manipulators as $manipulator) {
            $manipulator->manipulate($document, $layout);
        }
    }
}
