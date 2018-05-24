<?php
declare(strict_types=1);

namespace Enm\ExternalLayout\Finisher;

use Enm\ExternalLayout\Manipulator\ManipulatorInterface;
use Enm\ExternalLayout\Model\ValueObject\LayoutInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class WorkingTagFinisher implements FinisherInterface
{
    /**
     * Finish the plain text layout document
     *
     * @param string $document
     * @param LayoutInterface $layout
     * @return string
     */
    public function finish(string $document, LayoutInterface $layout): string
    {
        return str_replace(
            [
                '<' . ManipulatorInterface::WORKING_TAG . '>',
                '</' . ManipulatorInterface::WORKING_TAG . '>'
            ],
            '',
            $document
        );
    }
}
