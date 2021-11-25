<?php 

namespace App\Controllers;

use App\Models\Users_Model;
use App\Libraries\Service_Lib;


class LoginController extends BaseController
{

	public function __construct()
	{
		helper(['url','form', 'general']);
		$this->request = \Config\Services::request();
		$this->validation = \Config\Services::validation();
		$this->users = new Users_Model();
		$this->service_lib = new Service_Lib();
	}

	public function index(){
		if(session()->get('isLoggedIn') == TRUE){
			return redirect()->to(base_url('dashboard'));	
		}
		return view('login');
	}

	public function check()
	{
		$username 	= $this->request->getPost('username');
		$password 	= $this->request->getPost('password');

		$data = [
			'username' => $username,
			'password' => $password
		];

		if ($this->validation->run($data, 'loginUser') == FALSE){
            session()->setFlashdata('errors', 'Username, Password dan Akses User wajib diisi');
            return redirect()->to(base_url());
        } else {
        	
        	$query = $this->users->where('username', $username)->first();
        	
        	if ($query!=''){
        		$session = [
        				'uid'     	 => $query['id'],
				        'username'   => $query['username'],
				        'nama'       => $query['nama'],
				        'level'      => $query['hak_akses'],
				        'isLoggedIn' => TRUE
				];
				if (password_verify($password, $query['password'])){
					if ($query['deleted_at'] == NULL) {
						session()->set($session);
						return redirect()->to(base_url('dashboard'));
					} else {
						session()->setFlashdata('errors', 'Akun tidak aktif');
	                    return redirect()->to(base_url('login'));	
					}
				} else {
					session()->setFlashdata('errors', 'Username atau Password salah');
                    return redirect()->to(base_url('login'));
				}
        	} else {
        		session()->setFlashdata('errors', 'Akun anda belum terdaftar');
                return redirect()->to(base_url());
        	}
        }
	}

	public function forgot_password(){
		return view('forgot_password');
	}

	public function reset_password()
	{
		$email 	    = $this->request->getPost('email');
		$password  = random_string();

		$data = [
			'email'    => $email
		];

		if ($this->validation->run($data, 'resendPassword') == FALSE){
            session()->setFlashdata('errors', 'Email wajib diisi');
            return redirect()->to(base_url('login/forgot_password'));
        } else {
        	
        	$query = $this->users->where('email', $email)->first();
        	
        	if ($query!=''){
        		$message = 'Selamat <strong>'.$query['nama'].'</strong>, anda berhasil reset password. Berikut data untuk masuk ke dalam Manajemen Proyek :<br><br>
        				Username : <strong>'.$query['username'].'</strong><br>Password Baru : <strong>'.$password.'</strong><br><br>Setelah berhasil masuk, silakan untuk update password demi keamanan, terimakasih.';
        		$data = [
		        	'to' => $email,
		        	'subject' => 'Reset Password',
		        	'message' => $message
		        ];

		        
		        $this->users->where('id', $query['id'])->set(['password' => password_hash($password, PASSWORD_DEFAULT)])->update();
	

		       	if($this->service_lib->sendMail($data) == TRUE){
		       		session()->setFlashdata('success', 'Reset password berhasil, silakan periksa email anda');
		       		return redirect()->to(base_url('login/forgot_password'));
		       	} else {
		       		session()->setFlashdata('errors', 'Reset password gagal, coba periksa kembali koneksi anda');
		            return redirect()->to(base_url('login/forgot_password'));
		        }
		        
			} else {
				session()->setFlashdata('errors', 'Akun anda belum terdaftar');
                return redirect()->to(base_url('login/forgot_password'));
			}
        }
	}	

}