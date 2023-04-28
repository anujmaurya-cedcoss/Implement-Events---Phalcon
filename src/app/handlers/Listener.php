<?php
namespace handler\Listener;

use Phalcon\Di\Injectable;

class Listener extends injectable
{
    public function beforeProductAdd()
    {
        $settings = $this->db->fetchAll("SELECT * FROM settings", \Phalcon\Db\Enum::FETCH_ASSOC);
        if ($settings[0]['title'] == 'with-tag') {
            // change name to name+tag
            $_POST['name'] = $this->request->getPost('name') . '-' . $this->request->getPost('tags');
        }
        if ($this->request->getPost('price') == 0 || !isset($_POST['price'])) {
            $_POST['price'] = $settings[0]['price'];
        }
        if ($this->request->getPost('stock') == 0 || !isset($_POST['stock'])) {
            $_POST['stock'] = $settings[0]['stock'];
        }
    }

    public function beforeOrderAdd()
    {
        $settings = $this->db->fetchAll("SELECT * FROM settings", \Phalcon\Db\Enum::FETCH_ASSOC);
        if ($_POST['zip'] == '') {
            $_POST['zip'] = $settings[0]['zip'];
        }
    }
}
