<?php

namespace Storyblok\RichtextRender;

class CustomSchema implements SchemaInterface
{
    private array $nodes;
    private array $marks;

    public function __construct(array $nodes = [], array $marks = [])
    {
        $this->nodes = $nodes;
        $this->marks = $marks;
    }

    public function getNodes(): array
    {
        return $this->nodes;
    }

    public function getMarks(): array
    {
        return $this->marks;
    }
}
