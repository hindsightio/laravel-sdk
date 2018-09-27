<?php

namespace Hindsight\Tests\Support;

use Hindsight\Support\DataMerger;
use Hindsight\Tests\BaseTest;

class DataMergerTest extends BaseTest
{
    public function test_it_merges_arrays()
    {
        $subject = new DataMerger();
        $a = ['foo' => 5, 'bar' => 10];
        $b = ['baz' => 15];

        $this->assertEquals(['foo' => 5, 'bar' => 10, 'baz' => 15], $subject->merge($a, $b));
    }

    public function test_it_replaces_scalar_values()
    {
        $subject = new DataMerger();
        $base = ['foo' => 5, 'bar' => 10];
        $top = ['foo' => 20, 'baz' => 15];

        $this->assertEquals(['foo' => 20, 'bar' => 10, 'baz' => 15], $subject->merge($base, $top));

    }

    public function test_it_appends_array_values()
    {
        $subject = new DataMerger();
        $base = ['foo' => [5], 'bar' => 10];
        $top = ['foo' => [20, 25], 'baz' => 15];

        $this->assertEquals(['foo' => [5, 20, 25], 'bar' => 10, 'baz' => 15], $subject->merge($base, $top));
    }

    public function test_it_appends_when_base_is_array_but_top_is_calar()
    {
        $subject = new DataMerger();
        $base = ['foo' => [5], 'bar' => 10];
        $top = ['foo' => 50, 'baz' => 15];

        $this->assertEquals(['foo' => [5, 50], 'bar' => 10, 'baz' => 15], $subject->merge($base, $top));
    }

    public function test_it_ignores_top_when_base_is_scalar_but_top_is_array()
    {
        $subject = new DataMerger();
        $base = ['foo' => 5, 'bar' => 10];
        $top = ['foo' => [50], 'baz' => 15];

        $this->assertEquals(['foo' => 5, 'bar' => 10, 'baz' => 15], $subject->merge($base, $top));
    }

    public function test_it_uses_null_as_passthrough()
    {
        $subject = new DataMerger();
        $base = ['foo' => 5, 'bar' => 10];
        $top = ['foo' => null, 'baz' => 15];

        $this->assertEquals(['foo' => 5, 'bar' => 10, 'baz' => 15], $subject->merge($base, $top));
    }

    public function test_it_works_recursively()
    {
        $subject = new DataMerger();
        $base = ['foo' => ['sta' => 'tic', 'exp' => [45]], 'bar' => 10];
        $top = ['foo' => ['exp' => [30, 35, 40], 'add' => [50, 55]], 'baz' => 15];

        $this->assertEquals([
            'foo' => [
                'sta' => 'tic',
                'exp' => [45, 30, 35, 40],
                'add' => [50, 55],
            ],
            'bar' => 10,
            'baz' => 15,
        ], $subject->merge($base, $top));
    }
}
