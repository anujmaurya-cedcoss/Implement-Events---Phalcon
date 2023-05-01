<?php
// add new product
$class = 'btn btn-info m-3';
echo $this->tag->linkTo(['product', 'Add new product!', 'class' => $class]);
// show all products
echo $this->tag->linkTo(['product/show', 'Show all products!', 'class' => $class]);
// place new order
echo $this->tag->linkTo(['order', 'Place new Order!', 'class' => $class]);
// show all orders
echo $this->tag->linkTo(['order/show', 'Show all Order!', 'class' => $class]);
// settings
echo $this->tag->linkTo(['setting', 'Setting', 'class' => $class]);
// add component
echo $this->tag->linkTo(['addcomponent', 'Add Component!', 'class' => $class]);
// add role
echo $this->tag->linkTo(['role', 'Add New Role', 'class' => $class]);
// acl page
echo $this->tag->linkTo(['aclpage/acl', 'Access Control', 'class' => $class]);
