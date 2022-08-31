<?php

namespace Storyblok\RichtextRender;

use Storyblok\RichtextRender\Utils\Render;

class Resolver
{
    protected Render $renderer;
    protected array $marks;
    protected array $nodes;

    public function __construct(SchemaInterface $schema = null, Render $renderer = null)
    {
        $schema = $schema ?: new DefaultSchema();
        $this->renderer = $renderer ?: new Render();
        $this->marks = $schema->getMarks();
        $this->nodes = $schema->getNodes();
    }

    public function render($data): string
    {
        $html = '';
        $data = (array)$data;

        foreach ($data['content'] as $node) {
            $html .= $this->renderNode($node);
        }

        return $html;
    }

    protected function renderNode(array $item): string
    {
        $html = [];

        if (array_key_exists('marks', $item)) {
            foreach ($item['marks'] as $mark) {
                $matchingMark = $this->getMatchingMark($mark);
                if ($matchingMark) {
                    $html[] = $this->renderer->renderOpeningTag($matchingMark['tag']);
                }
            }
        }

        $node = $this->getMatchingNode($item);

        if ($node && array_key_exists('tag', $node)) {
            $html[] = $this->renderer->renderOpeningTag($node['tag']);
        }

        if (array_key_exists('content', $item)) {
            foreach ($item['content'] as $content) {
                $html[] = $this->renderNode($content);
            }
        } else if (array_key_exists('text', $item)) {
            $html[] = $this->renderer->escapeHTML($item['text']);
        } else if ($node && array_key_exists('single_tag', $node)) {
            $html[] = $this->renderer->renderTag($node['single_tag'], ' /');
        } else if ($node && array_key_exists('html', $node)) {
            $html[] = $node['html'];
        }

        if ($node && array_key_exists('tag', $node)) {
            $html[] = $this->renderer->renderClosingTag($node['tag']);
        }

        if (array_key_exists('marks', $item)) {
            foreach (array_reverse($item['marks']) as $mark) {
                $matchingMark = $this->getMatchingMark($mark);
                if ($matchingMark) {
                    $html[] = $this->renderer->renderClosingTag($matchingMark['tag']);
                }
            }
        }

        return implode('', $html);
    }

    protected function getMatchingNode(array $item): ?array
    {
        return ($fn = $this->nodes[$item['type']] ?? null) ? $fn($item) : null;
    }

    protected function getMatchingMark(array $item): ?array
    {
        return ($fn = $this->marks[$item['type']] ?? null) ? $fn($item) : null;
    }
}
