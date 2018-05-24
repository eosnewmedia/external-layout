<?php
declare(strict_types=1);

namespace Enm\ExternalLayout\Loader;

use Enm\ExternalLayout\Model\ValueObject\LayoutInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
interface LoaderInterface
{
    /**
     * @param LayoutInterface $layout
     * @return \DOMDocument
     */
    public function loadHtml(LayoutInterface $layout): \DOMDocument;
}
