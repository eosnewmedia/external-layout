<?php
declare(strict_types=1);

namespace Enm\ExternalLayout\Model\ValueObject;

use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\UriInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class Layout implements LayoutInterface
{
    /**
     * @var UriInterface
     */
    private $source;

    /**
     * @var string
     */
    private $destination;

    /**
     * @var BlockInterface[]
     */
    private $blocks;

    /**
     * @param UriInterface $source
     * @param string $destination
     * @param BlockInterface[] $blocks
     */
    public function __construct(UriInterface $source, string $destination, array $blocks)
    {
        $this->source = $source;
        $this->destination = $destination;
        $this->blocks = $blocks;
    }

    /**
     * @param array $config
     * @return LayoutInterface|static
     * @throws \RuntimeException
     */
    public static function createFromConfig(array $config): LayoutInterface
    {
        if (!array_key_exists('source', $config) || !array_key_exists('destination', $config)) {
            throw new \RuntimeException('Invalid configuration.');
        }

        $blocks = [];
        if (array_key_exists('blocks', $config) && \is_array($config['blocks'])) {
            foreach ($config['blocks'] as $type => $block) {
                foreach ((array)$block as $key => $value) {
                    $blocks[] = new Block($type, $value, $key);
                }
            }
        }

        return new static(new Uri($config['source']), $config['destination'], $blocks);
    }

    /**
     * @return UriInterface
     */
    public function getSource(): UriInterface
    {
        return $this->source;
    }

    /**
     * @return string
     */
    public function getDestination(): string
    {
        return $this->destination;
    }

    /**
     * @return BlockInterface[]
     */
    public function getBlocks(): array
    {
        return $this->blocks;
    }
}
