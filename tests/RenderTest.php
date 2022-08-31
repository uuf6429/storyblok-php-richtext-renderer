<?php

namespace Storyblok\RichtextRender;

use PHPUnit\Framework\TestCase;
use Storyblok\RichtextRender\Utils\Render;

class RenderTest extends TestCase
{
    public function testEscapeHml(): void
    {
        $renderer = new Render();

        $this->assertSame('&gt;', $renderer->escapeHTMl('>'));
    }

    /**
     * @dataProvider renderOpeningTagDataProvider
     */
    public function testRenderOpeningTag(string $expected, $input): void
    {
        $renderer = new Render();

        $this->assertEquals($expected, $renderer->renderOpeningTag($input));
    }

    public function renderOpeningTagDataProvider(): array
    {
        return [
            'without argument' => [
                '$expected' => '<>',
                '$input' => '',
            ],
            'paragraph' => [
                '$expected' => '<p>',
                '$input' => 'p',
            ],
            'list of objects' => [
                '$expected' => '<p><pre>',
                '$input' => [['tag' => 'p'], ['tag' => 'pre']],
            ],
            'list of strings' => [
                '$expected' => '<p><pre>',
                '$input' => ['p', 'pre'],
            ],
            'list of objects with attrs' => [
                '$expected' => '<p class="is-active"><pre>',
                '$input' => [
                    [
                        'tag' => 'p',
                        'attrs' => [
                            'class' => 'is-active'
                        ]
                    ],
                    ['tag' => 'pre']
                ],
            ],
        ];
    }

    /**
     * @dataProvider renderClosingTagDataProvider
     */
    public function testRenderClosingTag(string $expected, $input): void
    {
        $renderer = new Render();

        $this->assertEquals($expected, $renderer->renderClosingTag($input));
    }

    public function renderClosingTagDataProvider(): array
    {
        return [
            'without argument' => [
                '$expected' => '</>',
                '$input' => '',
            ],
            'paragraph' => [
                '$expected' => '</p>',
                '$input' => 'p',
            ],
            'italic' => [
                '$expected' => '</i>',
                '$input' => 'i',
            ],
            'pre' => [
                '$expected' => '</pre>',
                '$input' => 'pre',
            ],
            'list of objects' => [
                '$expected' => '</pre></p>',
                '$input' => [['tag' => 'p'], ['tag' => 'pre']],
            ],
            'list of strings' => [
                '$expected' => '</pre></p>',
                '$input' => ['p', 'pre'],
            ],
        ];
    }
}
