<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

/**
 * @author Jeidison Farias <jeidison.farias@gmail.com>
 */
class FiltrableTest extends TestCase
{

    public function testFilter()
    {
        $response = ModelTest::filter(['field_one'])->get();
        $this->assertNotNull($response);
    }

}
