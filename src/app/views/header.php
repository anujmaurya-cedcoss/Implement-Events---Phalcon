<?php
// add new product
echo $this->tag->linkTo([
    'product',
    'Add new product!',
    'class' => 'btn btn-info m-3'

]);
// show all products
echo $this->tag->linkTo([
    'product/show',
    'Show all products!',
    'class' => 'btn btn-info m-3'
]);

// place new order
echo $this->tag->linkTo([
    'order',
    'Place new Order!',
    'class' => 'btn btn-info m-3'
]);
// show all orders
echo $this->tag->linkTo([
    'order/show',
    'Show all Order!',
    'class' => 'btn btn-info m-3'
]);
// settings
echo $this->tag->linkTo([
    'setting',
    'Setting',
    'class' => 'btn btn-info m-3'
]);
