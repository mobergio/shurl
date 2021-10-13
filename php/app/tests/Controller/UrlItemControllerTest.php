<?php

namespace App\Tests\Controller;

use App\Controller\UrlItemController;
use PHPUnit\Framework\TestCase;

class UrlItemControllerTest extends TestCase
{

    private $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new UrlItemController();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->controller = null;
    }


    public function testIsSlugValid()
    {
        $slug = "abcd1234";
        $this->assertTrue($this->controller->isSlugValid($slug));
        $slug = "abcd-1234";
        $this->assertTrue($this->controller->isSlugValid($slug));
        $slug = "abcd-_1234";
        $this->assertTrue($this->controller->isSlugValid($slug));
        $slug = "abcd-_1234QED4";
        $this->assertTrue($this->controller->isSlugValid($slug));

        $slug = "abcd1234!";
        $this->assertFalse($this->controller->isSlugValid($slug));
        $slug = "abcd,1234";
        $this->assertFalse($this->controller->isSlugValid($slug));
        $slug = "ab/cd1234!";
        $this->assertFalse($this->controller->isSlugValid($slug));
        $slug = "ab\cd1234!";
        $this->assertFalse($this->controller->isSlugValid($slug));
    }

    public function testGeneratesValidSlug()
    {
        for ($i = 0; $i < 10000; $i++) {
            $randomSlug = $this->controller->createRandomSlug();
            $this->assertFalse(stristr($randomSlug, "\\"));
            $this->assertTrue($this->controller->isSlugValid($randomSlug));
        }
    }


}
