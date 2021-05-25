<?php

namespace RankLetter\CommonMarkImageExtension;

use League\CommonMark\ConfigurableEnvironmentInterface;
use League\CommonMark\Extension\ExtensionInterface;
use League\CommonMark\Inline\Renderer\ImageRenderer;

class SEOImageExtension implements ExtensionInterface
{
    public function register(ConfigurableEnvironmentInterface $environment)
    {
        $imageRenderer = new SEOImageRenderer(
            $environment,
            new ImageRenderer,
        );

        $environment->addInlineRenderer(
            'League\CommonMark\Inline\Element\Image',
            $imageRenderer,
            10
        );
    }
}
