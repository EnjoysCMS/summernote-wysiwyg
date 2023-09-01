<?php

declare(strict_types=1);


namespace EnjoysCMS\ContentEditor\Summernote\Composer\Scripts;


use EnjoysCMS\Core\Console\Command\AbstractAssetsInstallCommand;

final class AssetsInstallCommand extends AbstractAssetsInstallCommand
{
    protected string $cwd = __DIR__ . '/..';
}
