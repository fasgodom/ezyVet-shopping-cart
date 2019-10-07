<?php

declare(strict_types=1);

namespace ShoppingCart;

use DomainException;
use LengthException;
use PHPUnit\Framework\TestCase;

final class LineItemTest extends TestCase
{
    public function testCreateNewLineItem()
    {
        $this->assertInstanceOf(LineItem::class, new LineItem(0, "Product", 9.99));
    }

    public function testIdCanNotBeNegative()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage("Id should not be negative.");
        new LineItem(-8, "Product", 9.99);
    }

    public function testNameCanNotBeEmpty()
    {
        $this->expectException(LengthException::class);
        $this->expectExceptionMessage("Name should not be empty.");
        new LineItem(3, "", 9.99);
    }

    public function testNameCanNotBeOnlyWhitespace()
    {
        $this->expectException(LengthException::class);
        $this->expectExceptionMessage("Name should not be empty.");
        new LineItem(3, "        ", 9.99);
    }

    public function testPriceCanNotBeNegative()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage("Price should not be negative.");
        new LineItem(3, "Product", -9.99);
    }

    public function testIncrementQuantityByOne()
    {
        $line_item = new LineItem(1, "Product", 9.99);
        $line_item->incrementQuantity();
        $this->assertSame(1, $line_item->getQuantity());
    }

    public function testGetTotal()
    {
        $line_item = new LineItem(1, "Product", 9.99);
        $line_item->incrementQuantity();
        $line_item->incrementQuantity();
        $line_item->incrementQuantity();
        $this->assertSame(29.97, $line_item->getTotal());
    }

    public function testGetTotalAsString()
    {
        $line_item = new LineItem(1, "Product", 9.99);
        $line_item->incrementQuantity();
        $line_item->incrementQuantity();
        $line_item->incrementQuantity();
        $this->assertSame("29.97", $line_item->getTotal(["formatted" => true]));
    }
}
