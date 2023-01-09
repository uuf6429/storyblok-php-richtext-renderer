<?php

namespace Storyblok\RichtextRender;

use Closure;

interface SchemaInterface
{
    /**
     * @return array<string, Closure>
     */
    public function getMarks(): array;

    /**
     * @return array<string, Closure>
     */
    public function getNodes(): array;
}
