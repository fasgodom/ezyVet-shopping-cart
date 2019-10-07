<?php

declare(strict_types=1);

namespace ShoppingCart;

use ShoppingCart\LineItem;

class ShoppingCart
{
    private $line_items;

    public function __construct()
    {
        $this->line_items = $_SESSION['cart'] ?? [];
    }

    /**
     * List shopping cart.
     *
     * @return array Collection of LineItems in the cart.
     */
    public function list() : array
    {
        return $this->line_items;
    }

    /**
     * Return the total of shopping cart.
     *
     * @return string $total Total of shopping cart.
     */
    public function getTotal() : string
    {
        $cart_total = array_reduce($this->line_items, function ($total, $line_item) {
            return $total + $line_item->getTotal();
        }, 0);

        return number_format($cart_total, 2, ".", "");
    }

    /**
     * Add line item to shopping cart.
     *
     * If product is already in the cart increment quantity of line item.
     * Create new line item if product is not in cart.
     *
     * @param LineItem $line_item Line item to add to cart.
     */
    public function add(LineItem $line_item) : void
    {
        $id = $line_item->getId();

        if ($this->exists($id)) {
            $line_item = $this->line_items[$id];
        }

        $line_item->incrementQuantity();
        $this->line_items[$id] = $line_item;
        $this->save();
    }

    /**
     * Remove line item from shopping cart.
     *
     * @param int $id Id of line item to remove.
     *
     */
    public function remove(int $id) : void
    {
        unset($this->line_items[$id]);
        $this->save();
    }

    /**
     * Empty shopping cart.
     *
     */
    public function destroy() : void
    {
        unset($_SESSION['cart']);
    }

    /**
     * Saves cart to session.
     *
     */
    private function save() : void
    {
        $_SESSION['cart'] = $this->line_items;
    }

    /**
     * Checks if LineItem is already in the cart.
     *
     * @param int $id Id of line item to check.
     *
     * @return bool true if LineItem exists in cart false if not.
     */
    private function exists(int $id) : bool
    {
        return array_key_exists($id, $this->line_items);
    }
}
