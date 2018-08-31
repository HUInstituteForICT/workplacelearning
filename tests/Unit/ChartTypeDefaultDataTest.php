<?php

namespace Tests\Unit;

use App\ChartType;
use Tests\TestCase;

class ChartTypeDefaultDataTest extends TestCase
{
    public function testDefaultDataExists(): void
    {
        $this->assertCount(3, ChartType::all());
    }
}
