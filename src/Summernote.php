<?php

declare(strict_types=1);

namespace EnjoysCMS\WYSIWYG\Summernote;


use EnjoysCMS\Core\Components\Helpers\Assets;
use EnjoysCMS\Core\Components\WYSIWYG\WysiwygInterface;


class Summernote implements WysiwygInterface
{
    private string $twigTemplate = '@wysisyg/summernote/src/template/basic.tpl';

    /**
     * @throws NotSetupVendor
     * @throws \Exception
     */
    public function __construct(string $twigTemplate = null)
    {
        if ($twigTemplate !== null) {
            $this->setTwigTemplate($twigTemplate);
        }

        if (!file_exists(__DIR__ . '/../node_modules/summernote')) {
            throw new NotSetupVendor(sprintf('Run: cd %s/../ && yarn install', __DIR__));
        }

        $this->initialize();
    }

    /**
     * @param string $twigTemplate
     */
    public function setTwigTemplate(string $twigTemplate): void
    {
        $this->twigTemplate = $twigTemplate;
    }

    /**
     * @throws \Exception
     */
    private function initialize()
    {
        Assets::css(
            [
                __DIR__ . '/../node_modules/summernote/dist/summernote-bs4.min.css'
            ]
        );

        Assets::js(
            [
                __DIR__ . '/../node_modules/summernote/dist/summernote-bs4.min.js',
                __DIR__ . '/../node_modules/summernote/dist/lang/summernote-ru-RU.min.js'
            ]
        );
    }

    /**
     * @return string
     */
    public function getTwigTemplate(): string
    {
        return $this->twigTemplate;
    }

}
