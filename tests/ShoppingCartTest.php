<?php

declare(strict_types=1);

namespace ShoppingCart;

use PHPUnit\Framework\TestCase;

final class ShoppingCartTest extends TestCase
{
    private $products;

    public function setUp() : void
    {
        $this->products = [
            [ "name" => "Sledgehammer", "price" => 125.75 ],
            [ "name" => "Axe", "price" => 190.50 ],
            [ "name" => "Bandsaw", "price" => 562.131 ],
            [ "name" => "Chisel", "price" => 12.9 ],
            [ "name" => "Hacksaw", "price" => 18.45 ],
        ];
    }

    public function testNewShoppingCartIsEmpty() : void
    {
        $this->assertEmpty((new ShoppingCart)->list());
    }

    public function testAddAProduct() : void
    {
        $shopping_cart = new ShoppingCart;
        $id = 0;
        $product = $this->products[$id];

        $line_item = new LineItem($id, $product['name'], $product['price']);

        $shopping_cart->add($line_item);

        $this->assertContainsOnlyInstancesOf(LineItem::class, $shopping_cart->list());
        $this->assertCount(1, $shopping_cart->list());
        $this->assertSame($line_item, $shopping_cart->list()[$id]);
    }

    public function testAddSameProductTwice() : void
    {
        $shopping_cart = new ShoppingCart;
        $id = 0;
        $product = $this->products[$id];

        $shopping_cart->add(new LineItem($id, $product['name'], $product['price']));
        $shopping_cart->add(new LineItem($id, $product['name'], $product['price']));

        $cart_items = $shopping_cart->list();

        $this->assertContainsOnlyInstancesOf(LineItem::class, $cart_items);
        $this->assertCount(1, $shopping_cart->list());
        $this->assertSame(2, $cart_items[$id]->getQuantity());
    }

    public function testGetShoppingCartTotal() : void
    {
        $shopping_cart = new ShoppingCart;
        $id = 0;
        $product = $this->products[$id];

        $shopping_cart->add(new LineItem($id, $product['name'], $product['price']));
        $shopping_cart->add(new LineItem($id, $product['name'], $product['price']));

        $this->assertSame($shopping_cart->getTotal(), "251.50");
    }

    public function testRemoveProductFromCart() : void
    {
        $shopping_cart = new ShoppingCart;
        $id = 0;
        $product = $this->products[$id];

        $shopping_cart->add(new LineItem($id, $product['name'], $product['price']));
        $this->assertCount(1, $shopping_cart->list());
        $shopping_cart->remove($id);
        $this->assertCount(0, $shopping_cart->list());
    }

    public function tearDown() : void
    {
        unset($this->products);
    }
}
