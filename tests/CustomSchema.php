<?php

namespace Storyblok\RichtextRender;

class CustomSchema implements SchemaInterface
{
    private $nodes;
    private $marks;

    public function __construct($nodes = [], $marks = [])
    {
        $this->nodes = $nodes;
        $this->marks = $marks;
    }

    public function getNodes()
    {
        return $this->nodes;
    }

    public function getMarks()
    {
        return $this->marks;
    }
}
