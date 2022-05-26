<?php

declare(strict_types=1);

namespace Tests\EnjoysCMS\WYSIWYG\Summernote;

final class SummernoteTestLogger extends \Psr\Log\NullLogger
{
    public function withName(?string $name)
    {
        return $this;
    }
}
