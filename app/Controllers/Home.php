<?php

namespace App\Controllers;

use App\Models\Pegawai_Model;
use App\Models\Dosen_Model;
use App\Models\Mahasiswa_Model;
use App\Models\Bandwidth_Model;
use Config\Services;
use App\Libraries\Service_Lib;
use App\Libraries\Mikrotik;

class Home extends BaseController
{

	public function __construct()
	{
		helper(['url','form', 'general']);
		$this->validation = \Config\Services::validation();
		$this->security = \Config\Services::security();
		$this->pegawai = new Pegawai_Model();
		$this->dosen = new Dosen_Model();
		$this->mahasiswa = new Mahasiswa_Model();
		$this->bandwidth = new Bandwidth_Model();
	}

	public function index()
	{
		return view('welcome_message');
	}

	public function check(){
		$username 	= $this->request->getPost('username');
		$password 	= $this->request->getPost('password');
		$type 	    = $this->request->getPost('type');

		$data = [
			'username' => $username,
			'password' => $password,
			'type'     => $type
		];

		if ($this->validation->run($data, 'loginUser') == FALSE){
            session()->setFlashdata('errors', 'Username, Password dan Akses User wajib diisi');
            return redirect()->to(base_url('login'));
        } else {
        	if($type == 'dosen'){
        		$query = $this->dosen->where('username', $username)->withDeleted()->first();
        	} else if($type == 'mahasiswa'){
        		$query = $this->mahasiswa->where('username', $username)->withDeleted()->first();
        	} else {
        		$query = $this->pegawai->where('username', $username)->withDeleted()->first();
        	}
        	
        	if ($query!=''){
        		$session = [
        				'uid'     	 => $query['id'],
				        'username'   => $query['username'],
				        'nama'       => $query['nama'],
				        'level'      => $type,
				        'isLoggedIn' => TRUE
				];
				if (password_verify($password, $query['password'])){
					if ($query['deleted_at'] == NULL) {
						$data_bandwidth = $this->bandwidth->where('type', $type)->where('id_user', $query['id'])->first();
						if($data_bandwidth != NULL){
							$this->bandwidth->where('id', $data_bandwidth['id'])->set(['last_login' => date('Y-m-d H:i:s')])->update();
							session()->set($session);
							return redirect()->to(base_url('pengguna'));
						} else {
						  session()->setFlashdata('errors', 'Anda belum mengajukan layanan internet');
						  return redirect()->to(base_url(''));
						}
					} else {
						session()->setFlashdata('errors', 'Akun tidak aktif');
	                    return redirect()->to(base_url(''));	
					}
				} else {
					session()->setFlashdata('errors', 'Username, Password atau Akses User salah');
                    return redirect()->to(base_url(''));
				}
        	} else {
        		session()->setFlashdata('errors', 'Akun anda belum terdaftar');
                return redirect()->to(base_url(''));
        	}
        }
	}
}
