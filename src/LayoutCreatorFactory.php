<?php
declare(strict_types=1);

namespace Enm\ExternalLayout;

use Enm\ExternalLayout\Finisher\FinisherInterface;
use Enm\ExternalLayout\Loader\GuzzleLoader;
use Enm\ExternalLayout\Loader\LoaderInterface;
use Enm\ExternalLayout\Finisher\FinisherChain;
use Enm\ExternalLayout\Manipulator\ManipulatorChain;
use Enm\ExternalLayout\Manipulator\ManipulatorInterface;
use Enm\ExternalLayout\Manipulator\TwigManipulator;
use Enm\ExternalLayout\Manipulator\UrlManipulator;
use Enm\ExternalLayout\Finisher\WorkingTagFinisher;
use GuzzleHttp\Client;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class LayoutCreatorFactory
{
    /**
     * @var LoaderInterface
     */
    private $loader;

    /**
     * @var ManipulatorChain
     */
    private $manipulator;

    /**
     * @var FinisherChain
     */
    private $finisher;

    /**
     * @param LoaderInterface $loader
     */
    public function __construct(LoaderInterface $loader)
    {
        $this->loader = $loader;
        $this->manipulator = new ManipulatorChain();
        $this->finisher = new FinisherChain();
    }

    /**
     * @return LayoutCreatorFactory
     */
    public static function withGuzzleLoader(): self
    {
        return new self(new GuzzleLoader(new Client()));
    }

    /**
     * @return LayoutCreator
     */
    public function create(): LayoutCreator
    {
        return new LayoutCreator($this->loader, $this->manipulator, $this->finisher);
    }

    /**
     * @return LayoutCreatorFactory
     */
    public function enableTwigBlocks(): self
    {
        $this->manipulator->register(new TwigManipulator());
        $this->finisher->register(new WorkingTagFinisher());

        return $this;
    }

    /**
     * @return LayoutCreatorFactory
     */
    public function enableRelativeUrlReplacement(): self
    {
        $this->manipulator->register(new UrlManipulator());

        return $this;
    }

    /**
     * @param ManipulatorInterface $manipulator
     * @return LayoutCreatorFactory
     */
    public function addManipulator(ManipulatorInterface $manipulator): self
    {
        $this->manipulator->register($manipulator);

        return $this;
    }

    /**
     * @param FinisherInterface $finisher
     * @return LayoutCreatorFactory
     */
    public function addFinisher(FinisherInterface $finisher): self
    {
        $this->finisher->register($finisher);

        return $this;
    }
}
