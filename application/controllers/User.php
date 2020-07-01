<?php

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '../vendor/autoload.php';
require APPPATH . '/libraries/RestController.php';

use chriskacerguis\Restserver\RestController;

class User extends RestController
{

  function __construct($config = 'rest')
  {
    parent::__construct($config);
    $this->load->database();
  }

  function index_get()
  {
    $id = $this->get('id_user');
    if ($id == '') {
      $kontak = $this->db->get('user_api')->result();
    } else {
      $this->db->where('id_user', $id);
      $kontak = $this->db->get('user_api')->result();
    }
    $this->response($kontak, 200);
  }

}
