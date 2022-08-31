<?php

namespace Storyblok\RichtextRender;

use Closure;

interface SchemaInterface
{
    /**
     * @return array<string, Closure>
     */
    public function getMarks();

    /**
     * @return array<string, Closure>
     */
    public function getNodes();
}
