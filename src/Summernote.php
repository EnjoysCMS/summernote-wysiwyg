<?php

declare(strict_types=1);

namespace EnjoysCMS\WYSIWYG\Summernote;


use App\Components\Helpers\Assets;
use App\Components\WYSIWYG\WysiwygInterface;


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

        if(!file_exists( __DIR__ . '/../node_modules/summernote')){
            throw new \Exception('Выполните yarn install');
        }

        Assets::createSymlink(
            'assets/WYSIWYG/summernote/node_modules/summernote/dist',
            __DIR__ . '/../node_modules/summernote/dist'
        );
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