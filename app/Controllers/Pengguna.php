<?php

namespace App\Controllers;

use App\Models\Pegawai_Model;
use App\Models\Dosen_Model;
use App\Models\Mahasiswa_Model;
use App\Models\Bandwidth_Model;
use App\Libraries\Service_Lib;
use App\Libraries\Mikrotik;

class Pengguna extends BaseController
{

	public function __construct()
	{
		helper(['url','general']);
		$this->service_lib = new Service_Lib();
		$this->mikrotik = new Mikrotik();
		$this->pegawai = new Pegawai_Model();
		$this->dosen = new Dosen_Model();
		$this->mahasiswa = new Mahasiswa_Model();
		$this->bandwidth = new Bandwidth_Model();
	}

	public function index()
	{
		if(session()->get('level') == 'pegawai'){
			$self = $this->pegawai->find(session()->get('uid'));
		} else if(session()->get('level') == 'dosen'){
			$self = $this->dosen->find(session()->get('uid'));
		} else {
			$self = $this->mahasiswa->find(session()->get('uid'));
		}
		
		$data = [
			'title' => 'Layanan Internet',
			'bio' => $self,
			'lib' => $this->service_lib
		];
		return view('pengguna/index', $data);
	}

	public function logout()
	{
		$this->bandwidth->where('id_user', session()->get('uid'))->where('type', session()->get('level'))->set(['last_logout' => date('Y-m-d H:i:s')])->update();
		session()->destroy();
		return redirect()->to(base_url(''));
	}
}
