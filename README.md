External Layout
===============
This library loads html layouts from remote, modify them for local usage and stores the result into a new file.

## Installation
```bash
composer require enm/external-layout
```
If you want to use the default (guzzle) loader:
```bash
composer require guzzlehttp/guzzle
```

## Usage

Their are different ways to use this library, depending on your needs.

### Simple
The simplest way of usage is to use the layout creator factory with the default guzzle loader (requires `guzzlehttp/guzzle`).
For simplest usage you should build your layout definitions from a config array.

```php
$factory = \Enm\ExternalLayout\LayoutCreatorFactory::withGuzzleLoader();
$factory->enableRelativeUrlReplacement(); // adds the "UrlManipulator"
$factory->enableTwigBlocks(); // adds the "TwigManipulator" and the "WorkingTagFinisher"

$layoutCreator = $factory->create(); // create a configured instance of the LayoutCreator

$layoutCreator->createFromConfig( // load the original layout, manipulate the content and stores the modified content to the configured file
  [
      'source' => 'http://example.com', // your source url, with username and password (Basic Auth) if needed
      'destination' => __DIR__ . '/test.html.twig', // your destination file
      'blocks' => [
          'prepend' => [
              'headline' => 'body' // add block "body" as first child of html element "headline"
          ],
          'append' => [
              'stylesheets' => 'head', // add block "stylesheets" as last child of html element "head"
              'javascripts' => 'body' // add block "javascripts" as last child of html element "body"
          ],
          'replace' => [
              'title' => '%title%', // replace string "%title%" with block "title"
              'content' => '$$content$$' // replace string "$$content$$" with block "content"
          ]
      ]
  ]
);
```

### Customized
If you want to customize loading, manipulation or finishing for the layout, you can also use the factory and configure your custom requirements.

```php
$factory = new \Enm\ExternalLayout\LayoutCreatorFactory(
    new YourLoader() // here you can set an instance of a different loader, if you don' want to use the guzzle loader
);
$factory->addManipulator(
    new YourManipulator() // it is possible to set any number of custom manipulators
);
$factory->addFinisher(
    new YourFinisher() // it is possible to set any number of custom finishers
);

$layoutCreator = $factory->create();

// ... usage the same as above

```

### Fully Customized
If you want a fully customized implementation (for example for usage with a dependency injection service container), you could create all instances without factory by yourself.

```php
$layoutCreator = new \Enm\ExternalLayout\LayoutCreator(
    new YourLoader(), // use your own loader or an instance of "Enm\ExternalLayout\Loader\GuzzleLoader"
    new YourManipulator(), // use your own manipulator or for example an instance of "Enm\ExternalLayout\Loader\ManipulatorChain"
    new YourFinisher() // use your own finisher or for example an instance of "Enm\ExternalLayout\Loader\FinisherChain"
);

// ... usage the same as above
```

## Customization

The library works as follows:
1. Load contents from source url into a \DomDocument ("loaders")
1. Manipulate the \DomDocument for example with content replacements or block for your templating ("manipulators")
1. Finish the layout with simple plain text processing ("finishers")
1. Store the manipulated and finished html content into the configured file

### Loaders
Loaders are responsible for loading the html content of the given uri into a \DomDocument.

You can use the default loader (`Enm\ExternalLayout\Loader\GuzzleLoader`) which requires an instance of (`GuzzleHttp\ClientInterface`; composer: `guzzlehttp/guzzle`) or you
can implement the `Enm\ExternalLayout\Loader\LoaderInterface` by yourself.

### Manipulators
Manipulators are responsible for manipulation of the loaded \DomDocument. Manipulators could change contents, remove or 
add new dom elements or insert blocks for different templating languages (default Twig).

If you want to use more than one manipulator you can add all your manipulators to an instance of `Enm\ExternalLayout\Manipulator\ManipulatorChain`.
A manipulator must implement `Enm\ExternalLayout\Manipulator\ManipulatorInterface`.

Available manipulators are:
- `UrlManipulator`: Replace relative urls with absolute urls (needed because assets normally are farther loaded from original source)
- `TwigManipulator`: Replace strings with twig blocks; prepend or append twig blocks to html elements
- `BaseUrlManipulator`: Removes the base tag to avoid invalid local relative paths

### Finishers
Finishers are responsible for cleanup and string replacements which are not possible in a \DomDocument.

If you want to use more than one finisher you can add all your finishers to an instance of `Enm\ExternalLayout\Finisher\FinisherChain`.
A finisher must implement `Enm\ExternalLayout\Finisher\FinisherInterface`.

Available finishers are:
- `WorkingTagFinisher`: Remove "working tags", which are used to generate valid xml content for the \DomDocument where actually no tag in the finished content is needed
