<?php

declare(strict_types=1);

namespace ShoppingCart;

use DomainException;
use LengthException;

class LineItem
{
    private $id;
    private $name;
    private $price = 0;
    private $quantity = 0;

    public function __construct(int $id, string $name, float $price)
    {
        if ($id < 0) {
            throw new DomainException("Id should not be negative.");
        }

        $this->id = $id;

        if (trim($name) === '') {
            throw new LengthException("Name should not be empty.");
        }

        $this->name = $name;

        if ($price < 0) {
            throw new DomainException("Price should not be negative.");
        }

        $this->price = (int)($price * 100);
    }

    public function getId() : int
    {
        return $this->id;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getPrice() : float
    {
        return (float)number_format($this->price / 100, 2);
    }

    public function getQuantity() : int
    {
        return $this->quantity;
    }

    public function incrementQuantity() : void
    {
        $this->quantity += 1;
    }

    public function getTotal(array $options = ["formatted" => false])
    {
        $total = ($this->price * $this->quantity) / 100;

        return $options['formatted'] === true ? number_format($total, 2, ".", "") : $total;
    }
}
