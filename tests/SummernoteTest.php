<?php

declare(strict_types=1);


use DI\Container;
use Enjoys\AssetsCollector\Assets;
use EnjoysCMS\Core\Components\Helpers;
use EnjoysCMS\WYSIWYG\Summernote\NotSetupVendor;
use EnjoysCMS\WYSIWYG\Summernote\Summernote;
use PHPUnit\Framework\TestCase;

class SummernoteTest extends TestCase
{

    protected function setUp(): void
    {
        $DI = new Container();
        Helpers\Assets::setContainer($DI);
    }

    public function testNotSetupVendor()
    {
        $this->expectException(NotSetupVendor::class);
        $this->removeDirectoryRecursive(__DIR__ . '/../node_modules', true);
        new Summernote();
    }

    public function testSetupVendor()
    {
        exec('cd ' . __DIR__ . '/.. && yarn install');
        $summernote = new Summernote('test');
        $this->assertSame('test', $summernote->getTwigTemplate());
    }


    public function testCheckAssets()
    {

        $assets = Helpers\Assets::getContainer()->get(Assets::class);
        new Summernote('test');
        $this->assertStringContainsString('node_modules/summernote/dist/summernote-bs4.min.css', $assets->get('css'));
        $this->assertStringContainsString('node_modules/summernote/dist/summernote-bs4.min.js', $assets->get('js'));
        $this->assertStringContainsString('node_modules/summernote/dist/lang/summernote-ru-RU.min.js', $assets->get('js'));
    }

    private function removeDirectoryRecursive($path, $removeParent = false)
    {
        if (!file_exists($path)) {
            return;
        }
        $di = new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS);
        $ri = new RecursiveIteratorIterator($di, RecursiveIteratorIterator::CHILD_FIRST);

        /** @var SplFileInfo $file */
        foreach ($ri as $file) {
            if ($file->isLink()) {
                $symlink = realpath($file->getPath()) . DIRECTORY_SEPARATOR . $file->getFilename();
                if (PHP_OS_FAMILY == 'Windows') {
                    (is_dir($symlink)) ? rmdir($symlink) : unlink($symlink);
                } else {
                    unlink($symlink);
                }
                continue;
            }
            $file->isDir() ? rmdir($file->getRealPath()) : unlink($file->getRealPath());
        }
        if ($removeParent) {
            rmdir($path);
        }
    }
}
