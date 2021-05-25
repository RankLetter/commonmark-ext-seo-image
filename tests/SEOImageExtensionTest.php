<?php

use League\CommonMark\CommonMarkConverter;
use League\CommonMark\ConfigurableEnvironmentInterface;
use League\CommonMark\Environment;
use PHPUnit\Framework\TestCase;
use RankLetter\CommonMarkImageExtension\SEOImageExtension;

class SEOImageExtensionTest extends TestCase
{

    protected $environment;

    protected function setUp(): void
    {
        parent::setUp();

        $this->environment = Environment::createCommonMarkEnvironment();
        $this->environment->addExtension(new SEOImageExtension);
    }

    public function testTheRendererIsAdded()
    {
        $this->assertCount(2, $this->getImageRenderers($this->environment));
    }

    public function testOnlyTheLazyAttributeIsAddedInDefaultConfig()
    {
        $converter = new CommonMarkConverter([], $this->environment);

        $html = $converter->convertToHtml('![alt text](/path/to/image.jpg)');

        $this->assertStringContainsString('<img src="/path/to/image.jpg" alt="alt text" loading="lazy" />', $html);
    }

    public function testTheSrcCanBeStripped()
    {
        $converter = new CommonMarkConverter([
            'image' => ['strip_src' => true]
        ], $this->environment);

        $html = $converter->convertToHtml('![alt text](/path/to/image.jpg)');

        $this->assertStringContainsString('<img src="" alt="alt text" loading="lazy" />', $html);
    }

    public function testTheDataSrcBeDefined()
    {
        $imageMarkdown = '![alt text](/path/to/image.jpg)';

        $html = (new CommonMarkConverter([
            'image' => ['data_attribute' => 'src']
        ], $this->environment))->convertToHtml($imageMarkdown);

        $this->assertStringContainsString('data-src="/path/to/image.jpg"', $html);
    }

    public function testTheClassCanBeAdded()
    {
        $imageMarkdown = '![alt text](/path/to/image.jpg)';

        $html = (new CommonMarkConverter([
            'image' => ['html_class' => 'lazy-loading-class']
        ], $this->environment))->convertToHtml($imageMarkdown);

        $this->assertStringContainsString('class="lazy-loading-class"', $html);
    }

    public function testLozadLibraryConfigurationAsExample()
    {
        $imageMarkdown = '![alt text](/path/to/image.jpg)';

        $html = (new CommonMarkConverter([
            'image' => [
                'strip_src' => true,
                'html_class' => 'lozad',
                'data_attribute' => 'src',
            ]
        ], $this->environment))->convertToHtml($imageMarkdown);

        $this->assertStringContainsString('src="" alt="alt text" loading="lazy" data-src="/path/to/image.jpg" class="lozad"', $html);
    }

    /**
     * @param ConfigurableEnvironmentInterface $environment
     * @return array
     */
    private function getImageRenderers(ConfigurableEnvironmentInterface $environment)
    {
        return iterator_to_array($environment->getInlineRenderersForClass('League\CommonMark\Inline\Element\Image'));
    }
}
