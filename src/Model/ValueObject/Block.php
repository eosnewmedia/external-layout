<?php
declare(strict_types=1);

namespace Enm\ExternalLayout\Model\ValueObject;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class Block implements BlockInterface
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $element;

    /**
     * @var string
     */
    private $name;

    /**
     * @param string $type
     * @param string $element
     * @param string $name
     */
    public function __construct(string $type, string $element, string $name)
    {
        $this->type = $type;
        $this->element = $element;
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getElement(): string
    {
        return $this->element;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
