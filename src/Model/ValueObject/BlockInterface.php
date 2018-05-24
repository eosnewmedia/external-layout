<?php
declare(strict_types=1);


namespace Enm\ExternalLayout\Model\ValueObject;


/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
interface BlockInterface
{
    public const PREPEND = 'prepend';
    public const APPEND = 'append';
    public const REPLACE = 'replace';

    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @return string
     */
    public function getElement(): string;

    /**
     * @return string
     */
    public function getName(): string;
}