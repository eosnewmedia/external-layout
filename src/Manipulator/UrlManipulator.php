<?php
declare(strict_types=1);

namespace Enm\ExternalLayout\Manipulator;

use Enm\ExternalLayout\Model\ValueObject\LayoutInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class UrlManipulator implements ManipulatorInterface
{
    /**
     * Manipulates the layout document with the requirements of the layout
     *
     * @param \DOMDocument $document
     * @param LayoutInterface $layout
     */
    public function manipulate(\DOMDocument $document, LayoutInterface $layout): void
    {
        $host = '//' . $layout->getSource()->getHost();

        /** @var \DOMElement $anchor */
        foreach ($document->getElementsByTagName('a') as $anchor) {
            $this->replaceElementAttributeWithAbsoluteUri($anchor, 'href', $host);
        }

        /** @var \DOMElement $anchor */
        foreach ($document->getElementsByTagName('link') as $anchor) {
            $this->replaceElementAttributeWithAbsoluteUri($anchor, 'href', $host);
        }

        /** @var \DOMElement $anchor */
        foreach ($document->getElementsByTagName('img') as $anchor) {
            $this->replaceElementAttributeWithAbsoluteUri($anchor, 'src', $host);
        }

        /** @var \DOMElement $anchor */
        foreach ($document->getElementsByTagName('script') as $anchor) {
            $this->replaceElementAttributeWithAbsoluteUri($anchor, 'src', $host);
        }

        $xPath = new \DOMXPath($document);
        /** @var \DOMComment[] $comments */
        $comments = $xPath->query('//comment()');
        foreach ($comments as $comment) {
            $this->replaceCommentWithAbsoluteUri($comment, $host);
        }
    }

    /**
     * @param \DOMElement $element
     * @param $attribute
     * @param $host
     *
     * @return void
     */
    protected function replaceElementAttributeWithAbsoluteUri(
        \DOMElement $element,
        string $attribute,
        string $host
    ): void {
        if ($element->hasAttribute($attribute)) {
            $uri = $element->getAttribute($attribute);

            $uri = $this->convertToAbsoluteUri($host, $uri);

            $element->setAttribute($attribute, $uri);
        }
    }

    /**
     * @param \DOMComment $comment
     * @param string $host
     * @return void
     */
    protected function replaceCommentWithAbsoluteUri(\DOMComment $comment, string $host): void
    {
        if (preg_match_all('/(href|src)=\"([a-zA-Z0-9\-\/\.\?\#]+)\"/', $comment->textContent, $matches)) {
            /** @var array $uris */
            $uris = array_key_exists(2, $matches) && \is_array($matches[2]) ? $matches[2] : [];
            foreach ($uris as $uri) {
                $comment->textContent = str_replace(
                    $uri,
                    $this->convertToAbsoluteUri($host, $uri),
                    $comment->textContent
                );
            }
        }
    }

    /**
     * @param $host
     * @param $uri
     * @return string
     */
    protected function convertToAbsoluteUri(string $host, string $uri): string
    {
        $components = parse_url($uri);
        $noScheme = !array_key_exists('scheme', $components);
        $noHost = !array_key_exists('host', $components);

        if ($noScheme && $noHost) {
            $uri = $host . (strpos($uri, '/') !== 0 ? '/' : '') . $uri;
        }

        return $uri;
    }
}
