<?php
declare(strict_types=1);

namespace Enm\ExternalLayout\Finisher;

use Enm\ExternalLayout\Model\ValueObject\LayoutInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
interface FinisherInterface
{
    /**
     * Finish the plain text layout document
     *
     * @param string $document
     * @param LayoutInterface $layout
     * @return string
     */
    public function finish(string $document, LayoutInterface $layout): string;
}
