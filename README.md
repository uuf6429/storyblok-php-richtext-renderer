# Storyblok PHP Richtext Renderer

This package allows you to get an HTML string from the [richtext field](https://www.storyblok.com/docs/richtext-field) of Storyblok.

## Installation

```shell
composer require storyblok/richtext-resolver
```

## Usage

First, instantiate the `Resolver` class:

```php
use Storyblok\RichtextRender\Resolver;

$resolver = new Resolver();

```

Then use the `render()` method to get the html string from your richtext field.

```php
// previous code...

// Note that in php our objects use multidimensional array notation
$data = [
  "type" => "doc",
  "content" => [
    [
      "type" => "horizontal_rule"
    ]
  ]
];

$resolver->render($data) # renders a html string: '<hr />'
```

### Can I extend or replace the schema for a resolver?

Yes! Either create a class that [extends DefaultSchema](https://github.com/storyblok/storyblok-php-richtext-renderer/blob/master/src/DefaultSchema.php)
or a class that [implements SchemaInterface]() and then pass it as parameter to the Resolver class.
Here's an example:

```php
class MySchema extends \Storyblok\RichtextRender\DefaultSchema
{
    public function getNodes()
    {
        return array_merge(
            parent::getNodes(),
            [
                'my_component' =>  $this->getTag('tag', 'div'),
            ]
        );
    }
}

$resolver = new Resolver(new MySchema());
```

## Contribution

Fork me on [GitHub](https://github.com/storyblok/storyblok-php-richtext-renderer)

#### Testing

We use phpunit for tests. You can execute the following task to run the tests:

```shell
composer run test
```
