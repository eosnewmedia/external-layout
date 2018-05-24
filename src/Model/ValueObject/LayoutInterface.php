<?php
declare(strict_types=1);

namespace Enm\ExternalLayout\Model\ValueObject;

use Psr\Http\Message\UriInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
interface LayoutInterface
{
    /**
     * @return UriInterface
     */
    public function getSource(): UriInterface;

    /**
     * @return string
     */
    public function getDestination(): string;

    /**
     * @return BlockInterface[]
     */
    public function getBlocks(): array;
}
