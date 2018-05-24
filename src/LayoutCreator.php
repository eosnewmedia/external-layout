<?php
declare(strict_types=1);

namespace Enm\ExternalLayout;

use Enm\ExternalLayout\Finisher\FinisherInterface;
use Enm\ExternalLayout\Loader\LoaderInterface;
use Enm\ExternalLayout\Manipulator\ManipulatorInterface;
use Enm\ExternalLayout\Model\ValueObject\Layout;
use Enm\ExternalLayout\Model\ValueObject\LayoutInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class LayoutCreator
{
    /**
     * @var LoaderInterface
     */
    private $loader;

    /**
     * @var ManipulatorInterface
     */
    private $manipulator;

    /**
     * @var FinisherInterface
     */
    private $finisher;

    /**
     * @param LoaderInterface $loader
     * @param ManipulatorInterface $manipulator
     * @param FinisherInterface $finisher
     */
    public function __construct(
        LoaderInterface $loader,
        ManipulatorInterface $manipulator,
        FinisherInterface $finisher
    ) {
        $this->loader = $loader;
        $this->manipulator = $manipulator;
        $this->finisher = $finisher;
    }

    /**
     * @param array $layout
     * @throws \Exception
     */
    public function createFromConfig(array $layout): void
    {
        $this->create(Layout::createFromConfig($layout));
    }

    /**
     * @param LayoutInterface $layout
     * @throws \Exception
     */
    public function create(LayoutInterface $layout): void
    {
        $document = $this->loader->loadHtml($layout);

        $this->manipulator->manipulate($document, $layout);

        (new Filesystem())->dumpFile(
            $layout->getDestination(),
            $this->finisher->finish($document->saveHTML(), $layout)
        );
    }
}
