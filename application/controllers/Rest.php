<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '../vendor/autoload.php';
require APPPATH . '/libraries/RestController.php';
require APPPATH . '/libraries/JWT.php';
use chriskacerguis\Restserver\RestController;
use \Firebase\JWT\JWT;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

class Rest extends RestController {
    private $secretkey = '10520';
    public function __construct(){
        parent::__construct();
        $this->load->library('form_validation');
      //$this->load->helper(['jwt', 'authorization']);  
    }

    public function generate_post(){
        $this->load->model('loginmodel');
        $date = new DateTime();
        $username = $this->post('username',TRUE); 
        $pass = $this->post('password',TRUE); 
        $dataadmin = $this->loginmodel->is_valid($username);
        if ($dataadmin) {
            if (password_verify($pass,$dataadmin->password)) {
                $payload['id'] = $dataadmin->id_user;
                $payload['username'] = $dataadmin->username;
                $payload['iat'] = $date->getTimestamp();
                $payload['exp'] = $date->getTimestamp() + 3600;
                $output['token'] = JWT::encode($payload,$this->secretkey);
                return $this->response([
                'status'=>TRUE,
                'username'=>$username,
                'token'=>JWT::encode($payload, $this->secretkey),
                ],RestController::HTTP_OK);
            } else {
            $this->viewtokenfail($username);
             }
        } else {
            $this->viewtokenfail($username);
        }
    }

    public function viewtokenfail($username){
        $this->response([
          'status'=>FALSE,
          'username'=>$username,
          'message'=>'Invalid!'
          ],RestController::HTTP_BAD_REQUEST);
    }

    public function cektoken(){
        $this->load->model('loginmodel');
        $jwt = $this->input->get_request_header('Authorization');
        try {
            $decode = JWT::decode($jwt,$this->secretkey,array('HS256'));
            if ($this->loginmodel->is_valid_num($decode->username)>0) {
                return true;
            }
        } catch (Exception $e) {
            exit('Wrong Token');
        }
    }
  }