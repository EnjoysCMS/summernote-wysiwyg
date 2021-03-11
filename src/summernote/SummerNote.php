<?php


namespace App\WYSIWYG;


use App\Components\Helpers\Assets;

class SummerNote
{
    private string $template;

    public function __construct(string $mode = 'basic')
    {
        $this->template = __DIR__ . '/template/' . $mode . '.tpl';

        if (!file_exists($this->template)) {
            throw new \Exception(sprintf('Нет шаблона в по указанному пути: %s', $this->template));
        }

        $this->initialize();
    }

    private function initialize()
    {
        Assets::css(
            [
                __DIR__ . '/node_modules/summernote/dist/summernote-bs4.min.css'
            ]
        );

        Assets::js(
            [
                __DIR__ . '/node_modules/summernote/dist/summernote-bs4.min.js',
                __DIR__ . '/node_modules/summernote/lang/summernote-ru-RU.js'
            ]
        );
    }

    /**
     * @return string
     */
    public function getTemplate(): string
    {
        return $this->template;
    }

}