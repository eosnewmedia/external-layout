<?php
declare(strict_types=1);

namespace Enm\ExternalLayout\Finisher;

use Enm\ExternalLayout\Model\ValueObject\LayoutInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class FinisherChain implements FinisherInterface
{
    /**
     * @var FinisherInterface[]
     */
    private $finishers = [];

    /**
     * @param FinisherInterface $finisher
     */
    public function register(FinisherInterface $finisher): void
    {
        $this->finishers[] = $finisher;
    }

    /**
     * @param string $document
     * @param LayoutInterface $layout
     * @return string
     */
    public function finish(string $document, LayoutInterface $layout): string
    {
        foreach ($this->finishers as $finisher) {
            $document = $finisher->finish($document, $layout);
        }

        return $document;
    }
}
