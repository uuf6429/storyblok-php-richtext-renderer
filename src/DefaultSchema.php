<?php

namespace Storyblok\RichtextRender;

use Closure;
use Storyblok\RichtextRender\Utils\Utils;

class DefaultSchema implements SchemaInterface
{
    public function getMarks(): array
    {
        return [
            'bold' => $this->getTag('tag', 'b'),
            'strike' => $this->getTag('tag', 'strike'),
            'underline' => $this->getTag('tag', 'u'),
            'strong' => $this->getTag('tag', 'strong'),
            'code' => $this->getTag('tag', 'code'),
            'italic' => $this->getTag('tag', 'i'),
            'link' => $this->getLink('a'),
            'styled' => $this->getTagStyled('span'),
        ];
    }

    public function getNodes(): array
    {
        return [
            'blockquote' => $this->getTag('tag', 'blockquote'),
            'bullet_list' => $this->getTag('tag', 'ul'),
            'list_item' => $this->getTag('tag', 'li'),
            'ordered_list' => $this->getTag('tag', 'ol'),
            'paragraph' => $this->getTag('tag', 'p'),
            'horizontal_rule' => $this->getTag('single_tag', 'hr'),
            'hard_break' => $this->getTag('single_tag', 'br'),
            'image' => $this->getImage(),
            'code_block' => $this->getCodeBlock(),
            'heading' => $this->getHeading('tag'),
        ];
    }

    protected function getLink(string $tagName): Closure
    {
        return static function ($node) use ($tagName) {
            $attrs = $node['attrs'];
            $linkType = Utils::get($attrs, 'linktype', 'url');

            if (array_key_exists('anchor', $attrs)) {
                $anchor = $attrs['anchor'];

                if ($anchor !== '' && !is_null($anchor)) {
                    $attrs['href'] .= '#' . $anchor;
                }

                unset($attrs['anchor']);
            }

            if ($linkType === 'email') {
                $attrs['href'] = 'mailto:' . $attrs['href'];
            }

            if ($linkType === 'story') {
                unset($attrs['story'], $attrs['linktype'], $attrs['uuid']);
            }

            return [
                'tag' => [
                    [
                        'tag' => $tagName,
                        'attrs' => $attrs
                    ]
                ]
            ];
        };
    }

    protected function getHeading(string $tag): Closure
    {
        return function ($node) use ($tag) {
            return [
                $tag => "h{$this->getLevel($node)}",
            ];
        };
    }

    protected function getTag(string $tag, string $tagName): Closure
    {
        return static function () use ($tag, $tagName) {
            return [
                $tag => $tagName,
            ];
        };
    }

    protected function getTagStyled(string $tagName): Closure
    {
        return static function ($node) use ($tagName) {
            return [
                'tag' => [
                    [
                        'tag' => $tagName,
                        'attrs' => $node['attrs'],
                    ]
                ]
            ];
        };
    }

    protected function getImage(): Closure
    {
        return static function ($node) {
            return [
                'single_tag' => [
                    [
                        'tag' => 'img',
                        'attrs' => Utils::pick($node['attrs'], ['src', 'alt', 'title']),
                    ]
                ]
            ];
        };
    }

    protected function getCodeBlock(): Closure
    {
        return function ($node) {
            return [
                'tag' => [
                    'pre',
                    [
                        'tag' => 'code',
                        'attrs' => $this->getAttrs($node),
                    ]
                ]
            ];
        };
    }

    protected function getAttrs($node)
    {
        return Utils::get($node, 'attrs', []);
    }

    protected function getLevel($node): int
    {
        if ($node && array_key_exists('attrs', $node)) {
            $attrs = $node['attrs'];
            return Utils::get($attrs, 'level', 1);
        }

        return 1;
    }
}
