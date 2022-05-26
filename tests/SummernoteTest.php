<?php

declare(strict_types=1);

namespace Tests\EnjoysCMS\WYSIWYG\Summernote;

use DI\Container;
use DI\Definition\Source\DefinitionArray;
use Enjoys\AssetsCollector\Assets;
use Enjoys\AssetsCollector\Environment;
use EnjoysCMS\Core\Components\Helpers;
use EnjoysCMS\WYSIWYG\Summernote\NotSetupVendor;
use EnjoysCMS\WYSIWYG\Summernote\Summernote;
use Exception;
use FilesystemIterator;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

class SummernoteTest extends TestCase
{

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $_ENV['PROJECT_DIR'] = __DIR__;
        $_ENV['PUBLIC_DIR'] = __DIR__.'/_compile';

        $DI = new Container(
            new DefinitionArray([
                Assets::class => function () {
                    $env = new Environment('_compile');
                    $env->setBaseUrl('_compile');
                    return new Assets($env);
                },
                LoggerInterface::class => function(){
                    return new SummernoteTestLogger();
                }
            ])
        );

        Helpers\Assets::setContainer($DI);
        Helpers\Assets::setContainer($DI);
    }

    protected function tearDown(): void
    {
        $this->removeDirectoryRecursive(__DIR__ . '/_compile', true);
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
        $summernote = new Summernote();
        $summernote->setTwigTemplate('test');
        $this->assertSame('test', $summernote->getTwigTemplate());
    }


    public function testCheckAssets()
    {
        $assets = Helpers\Assets::getContainer()->get(Assets::class);
        new Summernote('test');
        $this->assertStringContainsString('node_modules/summernote/dist/summernote-bs4.min.css', $assets->get('css'));
        $this->assertStringContainsString('node_modules/summernote/dist/summernote-bs4.min.js', $assets->get('js'));
        $this->assertStringContainsString(
            'node_modules/summernote/dist/lang/summernote-ru-RU.min.js',
            $assets->get('js')
        );
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
