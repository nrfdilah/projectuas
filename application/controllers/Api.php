<?php
defined('BASEPATH') OR exit('No direct script access allowed');
   
require APPPATH . 'controllers/Rest.php';
class api extends Rest {
    function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->database();
        $this->cektoken();
    }
    function index_get($table = '', $id = '') {
        if ($table == '') {
            redirect(base_url());
        } else {
            $get_id = 'id_'.$table;
            if ($id == '') {

                $data = $this->db->get($table)->result();
            } else {

                $this->db->where($get_id, $id);
                $data = $this->db->get($table)->result();
            }
            $this->response($data, 200);
        }
    }
    function index_post($table = '') {
        $insert = $this->db->insert($table, $this->post());
        $id = $this->db->insert_id();
        if ($insert) {
            $response = array(
                'data' => $this->post(),
                'table' => $table,
                'id' => $id,
                'status' => 'success'
                );
            $this->response($response, 200);
        } else {
            $this->response(array('status' => 'fail', 502));
        }
    }
    function index_put($table = '', $id = '') {
        $get_id = 'id_'.$table;
        $this->db->where($get_id, $id);
        $update = $this->db->update($table, $this->put());
        if ($update) {
            $response = array(
                'data' => $this->put(),
                'table' => $table,
                'id' => $id,
                'status' => 'success'
                );
            $this->response($response, 200);
        } else {
            $this->response(array('status' => 'fail', 502));
        }
    }
    function index_delete($table = '', $id = '') {
        $get_id = 'id_'.$table;
        $this->db->where($get_id, $id);
        $delete = $this->db->delete($table);
        if ($delete) {
            $response = array(
                'table' => $table,
                'id' => $id,
                'status' => 'success'
                );
            $this->response($response, 201);
        } else {
            $this->response(array('status' => 'fail', 502));
        }
    }
}