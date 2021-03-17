<?php

declare(strict_types=1);

namespace EnjoysCMS\WYSIWYG\Summernote;


use EnjoysCMS\Core\Components\Helpers\Assets;
use EnjoysCMS\Core\Components\WYSIWYG\WysiwygInterface;


class Summernote implements WysiwygInterface
{
    private string $twigTemplate;

    public function __construct(string $twigTemplate = null)
    {
        $this->twigTemplate = $twigTemplate ?? '@wysisyg/summernote/src/template/basic.tpl';
        $this->initialize();
    }

    private function initialize()
    {

        if(!file_exists( __DIR__ . '/../assets/summernote')){
            exec('cd '. __DIR__.'/../ && yarn install');
            //throw new \Exception('Выполните yarn install');
        }

        Assets::createSymlink(
            'assets/WYSIWYG/summernote/assets/summernote/dist',
            __DIR__ . '/../assets/summernote/dist'
        );
        Assets::css(
            [
                __DIR__ . '/../assets/summernote/dist/summernote-bs4.min.css'
            ]
        );

        Assets::js(
            [
                __DIR__ . '/../assets/summernote/dist/summernote-bs4.min.js',
                __DIR__ . '/../assets/summernote/dist/lang/summernote-ru-RU.min.js'
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
