<?php

namespace RankLetter\CommonMarkImageExtension;

use League\CommonMark\ConfigurableEnvironmentInterface;
use League\CommonMark\HtmlElement;
use League\CommonMark\Inline\Renderer\ImageRenderer;
use League\CommonMark\Util\ConfigurationAwareInterface;
use League\CommonMark\ElementRendererInterface;
use League\CommonMark\Util\ConfigurationInterface;
use League\CommonMark\Inline\Element\AbstractInline;
use League\CommonMark\Inline\Renderer\InlineRendererInterface;

class SEOImageRenderer implements InlineRendererInterface, ConfigurationAwareInterface
{
    /**
     * @var ConfigurationInterface
     */
    protected $config;

    /**
     * @var ImageRenderer
     */
    protected $baseImageRenderer;

    /**
     * @var ConfigurableEnvironmentInterface
     */
    private $environment;

    /**
     * @param ConfigurableEnvironmentInterface $environment
     * @param ImageRenderer $baseImageRenderer
     */
    public function __construct(
        ConfigurableEnvironmentInterface $environment,
        ImageRenderer $baseImageRenderer
    ) {
        $this->baseImageRenderer = $baseImageRenderer;
        $this->environment = $environment;
    }

    /**
     * @param AbstractInline $inline
     * @param ElementRendererInterface $htmlRenderer
     *
     * @return HtmlElement
     */
    public function render(
        AbstractInline $inline,
        ElementRendererInterface $htmlRenderer
    ) {
        // Get config values
        $stripSrc = $this->environment->getConfig('image/strip_src', false);
        $dataAttribute = $this->environment->getConfig('image/data_attribute', '');
        $htmlClass = $this->environment->getConfig('image/html_class', '');

        // Prepare the renderer.
        $this->baseImageRenderer->setConfiguration($this->config);
        $baseImage = $this->baseImageRenderer->render($inline, $htmlRenderer);

        // Get the sizes
        $src = $baseImage->getAttribute('src');
        $url = (substr($src, 0, 4) === 'http') ? $src : 'public/' . $src;

        if (file_exists($url)) {
            $sizes = getimagesize($url);
            if ($sizes[0] !== 0 && $sizes[1] !== 0) {
                $baseImage->setAttribute('width', $sizes[0]);
                $baseImage->setAttribute('height', $sizes[1]);
            }
        }

        // Append lazy loading information
        $baseImage->setAttribute('loading', 'lazy');

        if ($dataAttribute) {
            $baseImage->setAttribute("data-$dataAttribute", $baseImage->getAttribute('src'));
        }

        if ($htmlClass) {
            $baseImage->setAttribute('class', $htmlClass);
        }

        if ($stripSrc) {
            $baseImage->setAttribute('src', '');
        }

        return $baseImage;
    }

    public function setConfiguration(ConfigurationInterface $configuration)
    {
        $this->config = $configuration;
    }
}
