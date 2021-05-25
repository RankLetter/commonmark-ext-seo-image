# SEO Image Extension for league/commonmark

**🚧️ This extension is work in progress. Until 1.0 is release breaking changes might happen. Use with caution.**

![Tests](https://github.com/RankLetter/commonmark-ext-seo-image/workflows/Tests/badge.svg)

This image extension for [league/commonmark](https://github.com/thephpleague/commonmark) adds:

 - the lazy loading attribute as well as
 - explicit `height` and `width` attributes to keep the layout shifts to a minimum. This works for both absolute URLs and local images.

The package is largely based on [simonvomeyser/commonmark-ext-lazy-image](https://github.com/simonvomeyser/commonmark-ext-lazy-image)! It is licensed under the same [MIT license](/license).


## Install

``` bash
composer require rankletter/commonmark-ext-seo-image
```

## Example

Assuming `/path/to/image.jpg` points to an image with a size of 1024x512 pixels, the following example

``` php
use League\CommonMark\Environment;
use RankLetter\CommonMarkExtension\ImageExtension;

$this->environment = Environment::createCommonMarkEnvironment();
$this->environment->addExtension(new ImageExtension());

$converter = new CommonMarkConverter([], $this->environment);
$html = $converter->convertToHtml('![alt text](/path/to/image.jpg)');
```

This creates the following HTML

```html
<img src="/path/to/image.jpg" alt="alt text" loading="lazy" width="1024" height="512" />
```

## Further Options

Further options to replicate the lazy loading using JavaScript packages are supported. For more details please check the [original package](https://github.com/simonvomeyser/commonmark-ext-lazy-image). This functionality will remain for now.
