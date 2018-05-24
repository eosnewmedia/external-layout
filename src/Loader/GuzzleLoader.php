<?php
declare(strict_types=1);

namespace Enm\ExternalLayout\Loader;

use Enm\ExternalLayout\Model\ValueObject\LayoutInterface;
use GuzzleHttp\ClientInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class GuzzleLoader implements LoaderInterface
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @param LayoutInterface $layout
     * @return \DOMDocument
     * @throws \LogicException|\GuzzleHttp\Exception\GuzzleException
     */
    public function loadHtml(LayoutInterface $layout): \DOMDocument
    {
        $response = $this->client->request('GET', $layout->getSource());

        $contentType = strtolower($response->getHeader('Content-Type')[0]);
        if (strpos($contentType, 'text/html') !== 0) {
            throw new \LogicException('HTML could not be loaded from remote server!');
        }

        $html = (string)$response->getBody();
        if (strpos($contentType, 'charset=') !== false && strpos($contentType, 'utf-8') === false) {
            $html = (string)preg_replace('/charset=\"([-a-zA-Z0-9_]+)\"/', 'charset="utf-8"', $html);
            $html = (string)preg_replace('/charset=([-a-zA-Z0-9_]+)/', 'charset=utf-8', $html);
            $html = (string)preg_replace('/encoding=\"([-a-zA-Z0-9_]+)\"/', 'encoding="utf-8"', $html);
            $html = utf8_encode($html);
        }

        libxml_use_internal_errors(true);
        libxml_disable_entity_loader(true);
        $document = new \DOMDocument();
        $document->loadHTML($html);

        return $document;
    }
}
