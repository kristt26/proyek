<?php 
namespace App\Controllers;

use App\Models\Dosen_Model;
use App\Models\Bandwidth_Model;
use App\Libraries\Service_Lib;
use Config\Services;


class DosenController extends BaseController
{

	public function __construct()
	{
		helper(['url','form', 'general']);
		$this->validation = \Config\Services::validation();
		$this->dosen = new Dosen_Model();
		$this->bandwidth = new Bandwidth_Model();
		$this->service_lib = new Service_Lib();
	}

	

	public function index()
	{
		$self = $this->dosen->find(session()->get('uid'));
		$data = [
			'title' => 'Layanan Internet',
			'bio' => $self,
			'lib' => $this->service_lib
		];
		return view('dosen/dashboard', $data);
	}

	public function profile(){
		$id = session()->get('uid');
		$data = [
			'title' => 'Layanan Internet',
			'data' => $this->dosen->find($id),
			'lib' => $this->service_lib
		];
		return view('dosen/profile', $data);
	}

	public function password_edit(){
		$id = session()->get('uid');
		$data = [
			'title' => 'Layanan Internet',
			'id' => enkrip($id)
		];
		return view('dosen/password_edit', $data);
	}

	public function password_update(){
		$dekrip = $this->request->getPost('id');
		$password_lama = $this->request->getPost('password_lama');
		$password_baru = $this->request->getPost('password_baru');
		$confirm_password_baru = $this->request->getPost('confirm_password_baru');
		$id = dekrip($dekrip);
		$data = $this->dosen->find($id);

		$validasi = [
			'password_lama' => $password_lama,
			'password_baru' => $password_baru,
			'confirm_password_baru' => $confirm_password_baru
		];

		if($this->validation->run($validasi, 'updatePassword') == FALSE){
			$data = [
				'title' => 'Layanan Internet',
				'validation' => $this->validation->getErrors(),
				'id' => $dekrip
			];
			return view('dosen/password_edit', $data);
		} else {
			if(password_verify($password_lama, $data['password']) == FALSE){
				$data = [
					'title' => 'Layanan Internet',
					'validation' => ['password_lama' => 'Password lama salah'],
					'id' => $dekrip
				];
				return view('dosen/password_edit', $data);
			} else {
				$update = [
		            'password' 			=> password_hash($password_baru, PASSWORD_DEFAULT)
	        	];
	        	$save_dosen = $this->dosen->where('id', $id)->set($update)->update();
	        	if($save_dosen){
	        		session()->setFlashdata('success', 'Update password berhasil');
	        		return redirect()->to(base_url('dosen/password_edit'));
	        	} else {
	        		session()->setFlashdata('errors', 'Update password gagal');
	        		return redirect()->to(base_url('dosen/password_edit'));
	        	}
			}
		}
	}

	public function layanan(){
		$type = 'dosen';
		$id_user = session()->get('uid');
		$data = [
			'title' => 'Layanan Internet',
			'data' => $this->bandwidth->where('type', $type)->where('id_user', $id_user)->first(),
			'lib' => $this->service_lib,
			'id' => $id_user
		];
		return view('dosen/layanan', $data);
	}

	public function layanan_submit(){
		$input = $this->request->getRawInput();
		$id_en = $input['id'];
		$id = dekrip($id_en);

		$insert = [
			'id_user' => $id,
			'type' => 'dosen',
			'status' => 0
		];
		
		$update = $this->bandwidth->save($insert);
		if($update){
			$response = [
				'status' => 201,
				'message' => 'Pengajuan Layanan Internet berhasil dikirim'
			];
		} else {
			$response = [
				'status' => 500,
				'message' => 'Pengajuan Layanan Internet gagal dikirim'
			];
		}
		
		return $this->response->setJSON($response);
	}

	public function layanan_cancel(){
		$input = $this->request->getRawInput();
		$id_en = $input['id'];
		$id = dekrip($id_en);
		$this->bandwidth->where('id_user', $id)->where('type', 'dosen')->set(['status' => 2])->update();
		$update = $this->bandwidth->where('id_user', $id)->where('type', 'dosen')->delete();
		if($update){
			$response = [
				'status' => 201,
				'message' => 'Pembatalan Layanan Internet berhasil'
			];
		} else {
			$response = [
				'status' => 500,
				'message' => 'Pembatalan Layanan Internet gagal'
			];
		}
		
		return $this->response->setJSON($response);
	}

	public function logout()
	{
		session()->destroy();
		return redirect()->to(base_url('login'));
	}

}
