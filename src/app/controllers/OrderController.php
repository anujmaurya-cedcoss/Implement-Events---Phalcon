<?php
use Phalcon\Mvc\Controller;

class OrderController extends Controller
{
    public function IndexAction()
    {
        // generate dropdown here
        $product = $this->db->fetchAll(
            "SELECT * FROM products",
                \Phalcon\Db\Enum::FETCH_ASSOC
        );
        $output = "<label for=\"products\">Choose a product</label>
        <select name=\"products\" id=\"products\">";
        foreach ($product as $key => $value) {
            $output .= "<option value=\"$value[name]\">$value[name]</option>";
        }
        $output .= '</select>';
        $this->view->dropdown = $output;
    }

    public function addAction()
    {
        $this->db->insert(
            "orders",
            $this->request->getPost(),
            [
                'customerName',
                'customerAddress',
                'zip',
                'products',
                'quantity'
            ]
        );
        $this->response->redirect('/order/show');
        $this->view->disable();
    }

    public function showAction()
    {
        $order = $this->db->fetchAll(
            "SELECT * FROM orders",
                \Phalcon\Db\Enum::FETCH_ASSOC
        );
        $output = '<table class = "table table-bordered table-striped"><tr><th>Order Id</th>
        <th>Customer Name</th>
        <th>Customer Address</th>
        <th>Zip</th>
        <th>Product</th>
        <th>Quantity</th></tr>';
        foreach ($order as $key => $value) {
            $output .= '<tr>';
            $output .= "<td>$value[id]</td>";
            $output .= "<td>$value[customerName]</td>";
            $output .= "<td>$value[customerAddress]</td>";
            $output .= "<td>$value[zip]</td>";
            $output .= "<td>$value[products]</td>";
            $output .= "<td>$value[quantity]</td></tr>";
        }
        $output .= '</table>';
        $this->view->result = $output;
    }
}