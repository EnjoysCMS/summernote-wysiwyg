<?php

declare(strict_types=1);

namespace EnjoysCMS\WYSIWYG\Summernote;


use EnjoysCMS\Core\Components\Helpers\Assets;
use EnjoysCMS\Core\Components\WYSIWYG\WysiwygInterface;


class Summernote implements WysiwygInterface
{
    private ?string $twigTemplate = null;

    /**
     * @throws NotSetupVendor
     * @throws \Exception
     */
    public function __construct()
    {
        if (!file_exists(__DIR__ . '/../node_modules/summernote')) {
            throw new NotSetupVendor(sprintf('Run: cd %s/../ && yarn install', __DIR__));
        }

        $this->initialize();
    }


    public function setTwigTemplate(?string $twigTemplate): void
    {
        $this->twigTemplate = $twigTemplate;
    }

    public function getTwigTemplate(): string
    {
        return $this->twigTemplate ?? '@wysiwyg/summernote/template/basic.tpl';
    }

    /**
     * @throws \Exception
     */
    private function initialize()
    {
        $path = str_replace(realpath($_ENV['PROJECT_DIR']), '', realpath(__DIR__.'/../'));

        Assets::createSymlink(sprintf('%s/assets%s/node_modules/summernote/dist', $_ENV['PUBLIC_DIR'], $path), __DIR__ . '/../node_modules/summernote/dist');

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



}
