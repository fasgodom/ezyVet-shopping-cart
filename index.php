<?php

error_reporting(E_STRICT);

require 'vendor/autoload.php';

use ShoppingCart\ShoppingCart;
use ShoppingCart\LineItem;

session_start();

function redirect($location = "/")
{
    header("Location: $location");
    exit();
}

$products = [
    [ "name" => "Sledgehammer", "price" => 125.75 ],
    [ "name" => "Axe", "price" => 190.50 ],
    [ "name" => "Bandsaw", "price" => 562.131 ],
    [ "name" => "Chisel", "price" => 12.9 ],
    [ "name" => "Hacksaw", "price" => 18.45 ],
    ];

$shopping_cart = new ShoppingCart;

if (isset($_GET['add'])) {
    $id = $_GET['add'];

    if ($id >= 0 && $id < count($products)) {
        $shopping_cart->add(new LineItem($id, $products[$id]['name'], $products[$id]['price'], 0));
    }

    redirect();
}

if (isset($_GET['remove'])) {
    $id = $_GET['remove'];

    if ($id >= 0 && $id < count($products)) {
        $shopping_cart->remove($id);
    }

    redirect();
}

if (isset($_GET['clear'])) {
    $shopping_cart->destroy();
    redirect();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ShoppingCart</title>
</head>
<body>
    <h2>Products</h2>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Price</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($products as $index => $product) : ?>
            <tr>
                <td><?= $product['name']?></td>
                <td><?= number_format($product['price'], 2, ".", "") ?></td>
                <td><a href="?add=<?= $index?>"">Add to cart</a></td>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>

    <?php if (!empty($shopping_cart->list())) : ?>
    <h2>Shopping Cart</h2>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($shopping_cart->list() as $index => $line_item) : ?>
            <tr>
                <td><?= $line_item->getName() ?></td>
                <td><?= $line_item->getPrice() ?></td>
                <td><?= $line_item->getQuantity() ?></td>
                <td><?= $line_item->getTotal(["formatted" => true]) ?></td>
                <td><a href="?remove=<?= $index?>"">Remove from cart</a></td>
            </tr>
        <?php endforeach ?>
            <tr>
                <th scope="row" colspan="3" >cart total:</th>
                <td><strong><?= $shopping_cart->getTotal() ?></strong></td>
                <td><a href="?clear">Clear cart</a></td>
            </tr>
        </tbody>
    </table>
    <?php endif ?>
</body>
</html>

