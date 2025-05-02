<?php

declare(strict_types=1);

namespace EnjoysCMS\ContentEditor\Summernote;


use Enjoys\AssetsCollector;
use Enjoys\AssetsCollector\Assets;
use EnjoysCMS\Core\ContentEditor\ContentEditorInterface;
use Exception;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

use function Enjoys\FileSystem\makeSymlink;


class Summernote implements ContentEditorInterface
{
    private ?string $selector = null;

    /**
     * @throws NotSetupVendor
     * @throws Exception
     */
    public function __construct(
        private Environment $twig,
        private AssetsCollector\Assets $assets,
        private LoggerInterface $logger,
        private ?string $template = null
    ) {
        if (!file_exists(__DIR__ . '/../node_modules/summernote')) {
            throw new \RuntimeException(sprintf('Run: cd %s && yarn install', realpath(__DIR__ . '/..')));
        }

        $this->initialize();
    }

    private function getTemplate(): ?string
    {
        return $this->template ?? __DIR__.'/../template/basic.twig';
    }

    /**
     * @throws Exception
     */
    private function initialize(): void
    {
        $path = str_replace(getenv('ROOT_PATH'), '', realpath(__DIR__ . '/../'));

        $link = sprintf('%s/assets%s/node_modules/summernote/dist', $_ENV['PUBLIC_DIR'], $path);
        $target = __DIR__ . '/../node_modules/summernote/dist';

        try {
            if (makeSymlink($link, $target)) {
                $this->logger->info(sprintf('Created symlink: %s', $link));
            }
        } catch (\Exception $e) {
            $this->logger->notice($e->getMessage());
        }


        $this->assets->add(AssetsCollector\AssetType::CSS,
            [
                __DIR__ . '/../node_modules/summernote/dist/summernote-bs4.min.css'
            ]
        );

        $this->assets->add(AssetsCollector\AssetType::JS,
            [
                __DIR__ . '/../node_modules/summernote/dist/summernote-bs4.min.js',
                __DIR__ . '/../node_modules/summernote/dist/lang/summernote-ru-RU.min.js'
            ]
        );
    }


    public function setSelector(string $selector): void
    {
        $this->selector = $selector;
    }

    public function getSelector(): string
    {
        if ($this->selector === null) {
            throw new RuntimeException('Selector not set');
        }
        return $this->selector;
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function getEmbedCode(): string
    {
        $twigTemplate = $this->getTemplate();
        if (!$this->twig->getLoader()->exists($twigTemplate)) {
            throw new RuntimeException(
                sprintf("ContentEditor: (%s): Нет шаблона в по указанному пути: %s", self::class, $twigTemplate)
            );
        }
        return $this->twig->render(
            $twigTemplate,
            [
                'editor' => $this,
                'selector' => $this->getSelector()
            ]
        );
    }
}
