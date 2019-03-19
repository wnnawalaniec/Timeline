<?php
declare(strict_types=1);

namespace Tests;

class BaseTestCase extends \PHPUnit\Framework\TestCase
{
    public function assertException(\Exception $exception, callable $acts): void
    {
        $this->expectExceptionObject($exception);
        $acts();
    }
}
