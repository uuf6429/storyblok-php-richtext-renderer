<?php

namespace Storyblok\RichtextRender\Utils;

class Render
{
    public function escapeHTMl(string $html): string
    {
        return htmlspecialchars($html, ENT_QUOTES);
    }

    public function renderClosingTag($tags): string
    {
        if (is_string($tags)) {
            return "</$tags>";
        }

        $all = [];

        foreach (array_reverse($tags) as $tag) {
            if (is_string($tag)) {
                $all[] = "</$tag>";
            } else {
                $all[] = "</{$tag['tag']}>";
            }
        }

        return implode('', $all);
    }

    public function renderTag($tags, string $ending): string
    {
        if (is_string($tags)) {
            return "<$tags$ending>";
        }

        $all = array_map(static function ($tag) use ($ending) {
            if (is_string($tag)) {
                return "<$tag>";
            }

            $result = "<{$tag['tag']}";

            if (array_key_exists('attrs', $tag)) {
                foreach ($tag['attrs'] as $key => $value) {
                    if (!is_null($value)) {
                        $result .= " $key=\"$value\""; // FIXME this looks insecure...shouldn't key and value be html-encoded??
                    }
                }
            }

            return "$result$ending>";
        }, $tags);

        return implode('', $all);
    }

    public function renderOpeningTag($tags): string
    {
        return $this->renderTag($tags, '');
    }
}
