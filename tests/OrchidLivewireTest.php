<?php

namespace AlexSabur\OrchidLivewire\Tests;

use AlexSabur\OrchidLivewire\ServiceProvider;
use Orchestra\Testbench\TestCase;

class OrchidLivewireTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [ServiceProvider::class];
    }

    public function testExample()
    {
        $this->assertEquals(1, 1);
    }
}
