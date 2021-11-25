<?php 
namespace App\Controllers;

use App\Models\Pegawai_Model;
use App\Models\Dosen_Model;
use App\Models\Mahasiswa_Model;
use App\Models\Unit_Model;
use App\Models\Fakultas_Model;
use App\Models\Prodi_Model;
use App\Models\Bandwidth_Model;
use Config\Services;
use App\Libraries\Service_Lib;
use App\Libraries\Mikrotik;
use App\Libraries\PDF;


class PegawaiController extends BaseController
{

	public function __construct()
	{
		helper(['url','form', 'general']);
		$this->validation = \Config\Services::validation();
		$this->security = \Config\Services::security();
		$this->pegawai = new Pegawai_Model();
		$this->dosen = new Dosen_Model();
		$this->mahasiswa = new Mahasiswa_Model();
		$this->unit = new Unit_Model();
		$this->fakultas = new Fakultas_Model();
		$this->prodi = new Prodi_Model();
		$this->bandwidth = new Bandwidth_Model();
		$this->service_lib = new Service_Lib();
		$this->mikrotik = new Mikrotik();
	}

	public function cetak_pdf(){
		$pdf = new PDF('L', 'mm', 'a4', true, 'UTF-8', false);

		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('Indras');
		$pdf->SetTitle('Contoh');
		$pdf->SetSubject('Contoh');

		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);

		$pdf->addPage();
		$html = '<h1>Test</h1>';
		// output the HTML content
		$pdf->writeHTML($html, true, false, true, false, '');
		//line ini penting
		$this->response->setContentType('application/pdf');
		//Close and output PDF document
		$pdf->Output('invoice.pdf', 'I');
	}

	

	public function index()
	{
		$self = $this->pegawai->find(session()->get('uid'));
		$data = [
			'title' => 'Layanan Internet',
			'bio' => $self,
			'lib' => $this->service_lib
		];
		return view('pegawai/dashboard', $data);
	}

	public function profile(){
		$id = session()->get('uid');
		$data = [
			'title' => 'Layanan Internet',
			'data' => $this->pegawai->find($id),
			'lib' => $this->service_lib
		];
		return view('pegawai/profile', $data);
	}

	public function password_edit(){
		$id = session()->get('uid');
		$data = [
			'title' => 'Layanan Internet',
			'id' => enkrip($id)
		];
		return view('pegawai/password_edit', $data);
	}

	public function password_update(){
		$dekrip = $this->request->getPost('id');
		$password_lama = $this->request->getPost('password_lama');
		$password_baru = $this->request->getPost('password_baru');
		$confirm_password_baru = $this->request->getPost('confirm_password_baru');
		$id = dekrip($dekrip);
		$data = $this->pegawai->find($id);

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
			return view('pegawai/password_edit', $data);
		} else {
			if(password_verify($password_lama, $data['password']) == FALSE){
				$data = [
					'title' => 'Layanan Internet',
					'validation' => ['password_lama' => 'Password lama salah'],
					'id' => $dekrip
				];
				return view('pegawai/password_edit', $data);
			} else {
				$update = [
		            'password' 			=> password_hash($password_baru, PASSWORD_DEFAULT)
	        	];
	        	$save_pegawai = $this->pegawai->where('id', $id)->set($update)->update();
	        	if($save_pegawai){
	        		session()->setFlashdata('success', 'Update password berhasil');
	        		return redirect()->to(base_url('pegawai/password_edit'));
	        	} else {
	        		session()->setFlashdata('errors', 'Update password gagal');
	        		return redirect()->to(base_url('pegawai/password_edit'));
	        	}
			}
		}
	}

	public function pegawai_list(){
	  $request = Services::request();
	  $m_pegawai = new Pegawai_Model($request);
	  if($request->getMethod(true)=='POST'){
	    $lists = $m_pegawai->get_datatables();
	        $data = [];
	        $no = $request->getPost("start");
	        foreach ($lists as $list) {
	                $no++;
	                $row = [];
	                $row[] = $no;
	                $row[] = $list->nip;
	                $row[] = $list->nama;
	                $row[] = $list->email;
	                $row[] = ucwords($list->level);
	                $row[] = $list->nama_unit;
	                $row[] = $list->status == 'Y' ? 'Active' : 'Not Active';
	                $row[] = '<a href="'.base_url('pegawai/pegawai_edit/'.enkrip($list->id)).'" class="text-secondary"><i class="fa fa-pencil-alt"></i></a> &nbsp; <a href="#" onClick="return deletePegawai('.$list->id.')" class="text-secondary"><i class="fa fa-trash"></i></a>';
	                $data[] = $row;
	    }
	    $output = ["draw" => $request->getPost('draw'),
	                        "recordsTotal" => $m_pegawai->count_all(),
	                        "recordsFiltered" => $m_pegawai->count_filtered(),
	                        "data" => $data];
	    echo json_encode($output);
	  }
	}

	public function pegawai(){
		$data = [
			'title' => 'Layanan Internet'
		];
		return view('pegawai/pegawai', $data);
	}

	public function pegawai_ver_list(){
	  $request = Services::request();
	  $m_pegawai = new Pegawai_Model($request);
	  if($request->getMethod(true)=='POST'){
	    $lists = $m_pegawai->get_datatables2();
	        $data = [];
	        $no = $request->getPost("start");
	        foreach ($lists as $list) {
	                $no++;
	                $row = [];
	                $row[] = $no;
	                $row[] = $list->nip;
	                $row[] = $list->nama;
	                $row[] = $list->email;
	                $row[] = ucwords($list->level);
	                $row[] = $list->nama_unit;
	                $row[] = $list->status == 'Y' ? 'Approved' : 'Need Approval';
	                $row[] = '<a href="#" onClick="return approvePegawai('.$list->id.')" class="badge badge-success">Approve</a> &nbsp; <a href="#" onClick="return rejectPegawai('.$list->id.')" class="badge badge-danger">Reject</a>';
	                $data[] = $row;
	    }
	    $output = ["draw" => $request->getPost('draw'),
	                        "recordsTotal" => $m_pegawai->count_all2(),
	                        "recordsFiltered" => $m_pegawai->count_filtered2(),
	                        "data" => $data];
	    echo json_encode($output);
	  }
	}

	public function pegawai_ver(){
		$data = [
			'title' => 'Layanan Internet'
		];
		return view('pegawai/pegawai_ver', $data);
	}

	public function pegawai_approve(){
		$input = $this->request->getRawInput();
		$id_en = $input['id'];
		$id = dekrip($id_en);
		$data_pegawai = $this->pegawai->find($id);
		$password = random_string();
		$update = $this->pegawai->where('id', $id)->set(['status' => 'Y', 'password' => password_hash($password, PASSWORD_DEFAULT)])->update();
		if($update){
			$message = 'Selamat <strong>'.$data_pegawai['nama'].'</strong>, anda berhasil registrasi di Layanan Internet. Berikut data untuk masuk ke dalam Layanan Internet:<br><br>
        				Username : <strong>'.$data_pegawai['username'].'</strong><br>Password : <strong>'.$password.'</strong><br><br>Silakan untuk update password demi keamanan, terimakasih.';
        	
        	$data = [
        			'to' => $data_pegawai['email'],
        			'subject' => 'Registrasi Pegawai',
        			'message' => $message
        	];
        	if($this->service_lib->sendMail($data) == TRUE){
        		$response = [
						'status' => 201,
						'message' => 'Approve pegawai berhasil'
				];
        	} else {
        		$response = [
						'status' => 500,
						'message' => 'Approve pegawai gagal'
				];
        	}
		} else {
			$response = [
					'status' => 500,
					'message' => 'Approve pegawai gagal'
			];
		}
		return $this->response->setJSON($response);
	}

	public function pegawai_reject(){
		$input = $this->request->getRawInput();
		$id_en = $input['id'];
		$id = dekrip($id_en);
		$this->pegawai->where('id', $id)->set(['status' => 'N'])->update();
		$update = $this->pegawai->delete($id);
		if($update){
			$response = [
					'status' => 201,
					'message' => 'Reject pegawai berhasil'
			];
		} else {
			$response = [
					'status' => 500,
					'message' => 'Reject pegawai gagal'
			];
		}
		return $this->response->setJSON($response);
	}

	public function pegawai_add(){
		$data = [
			'title' => 'Layanan Internet',
			'unit' => $this->unit->findAll()
		];
		return view('pegawai/pegawai/pegawai_add', $data);
	}

	public function pegawai_store(){
		$nip = $this->request->getPost('nip');
		$nama = $this->request->getPost('nama');
		$username = $this->request->getPost('username');
		$email = $this->request->getPost('email');
		$level = $this->request->getPost('level');
		$id_unit = $this->request->getPost('id_unit');
		$status = 'Y';

		$validasi = [
            'nip'   	=> $nip,
            'nama'  	=> $nama,
            'username'	=> $username,
            'email'   	=> $email,
            'username'  => $username,
            'level'		=> $level
        ];

        $password = random_string();

        if($this->validation->run($validasi, 'regisPegawai') == FALSE){
        	$data = [
				'title' => 'Layanan Internet',
				'validation' => $this->validation->getErrors(),
				'unit' => $this->unit->findAll()
			];
			return view('pegawai/pegawai/pegawai_add', $data);
        } else {
        	$insert = [
        		'nip'   	=> $nip,
	            'nama'  	=> $nama,
	            'username'	=> $username,
	            'email'   	=> $email,
	            'username'  => $username,
	            'level'     => $level,
	            'id_unit'   => $id_unit,
	            'status'    => $status,
	            'password'  => password_hash($password, PASSWORD_DEFAULT)
        	];
        	$save_pegawai = $this->pegawai->save($insert);
        	$message = 'Selamat '.$nama.', anda berhasil registrasi di Layanan Internet. Berikut data untuk masuk ke dalam Layanan Internet:<br><br>
        				Username : <strong>'.$username.'</strong><br>Password : <strong>'.$password.'</strong><br><br>Setelah berhasil masuk, silakan untuk update password demi keamanan, terimakasih.';
        	if($save_pegawai){
        		$data = [
        			'to' => $email,
        			'subject' => 'Registrasi Pegawai',
        			'message' => $message
        		];
        		if($this->service_lib->sendMail($data) == TRUE){
        			session()->setFlashdata('success', 'Tambah data pegawai berhasil');
        			return redirect()->to(base_url('pegawai/pegawai'));
        		} else {
        			session()->setFlashdata('errors', 'Tambah data pegawai gagal, coba periksa kembali koneksi anda');
        		    return redirect()->to(base_url('pegawai/pegawai'));
        		}
        	} else {
        		session()->setFlashdata('errors', 'Tambah data pegawai gagal');
        		return redirect()->to(base_url('pegawai/pegawai'));
        	}
        }
		
	}

	public function pegawai_edit($id){
		$data = [
			'title' => 'Layanan Internet',
			'data' => $this->pegawai->find(dekrip($id)),
			'unit' => $this->unit->findAll(),
			'id' => $id
		];
		return view('pegawai/pegawai/pegawai_edit', $data);
		
	}

	public function pegawai_update(){
		$dekrip = $this->request->getPost('id');
		$nip = $this->request->getPost('nip');
		$nama = $this->request->getPost('nama');
		$email = $this->request->getPost('email');
		$email_old = $this->request->getPost('email_old');
		$username = $this->request->getPost('username');
		$username_old = $this->request->getPost('username_old');
		$level = $this->request->getPost('level');
		$status = $this->request->getPost('status');
		$id_unit = $this->request->getPost('id_unit');
		$id = dekrip($dekrip);

		$validasi = [
            'nip'      => $nip,
            'nama'     => $nama,
            'email'    => $email,
            'username' => $username,
            'level'    => $level,
            'status'   => $status
        ];

        if($this->validation->run($validasi, 'updatePegawai') == FALSE){
        	$data = [
				'title' => 'Layanan Internet',
				'validation' => $this->validation->getErrors(),
				'data' => $this->pegawai->find($id),
				'unit' => $this->unit->findAll(),
				'id' => $dekrip
			];
			return view('pegawai/pegawai/pegawai_edit', $data);
        } else {
        	if($email_old != $email && $this->pegawai->where('email', $email)->countAllResults() > 0){
        		$data = [
					'title' => 'Layanan Internet',
					'validation' => ['email' => 'Email sudah terdaftar di database'],
					'data' => $this->pegawai->find($id),
					'unit' => $this->unit->findAll(),
					'id' => $dekrip
				];
				return view('pegawai/pegawai/pegawai_edit', $data);
        	} else if($username_old != $username && $this->pegawai->where('username', $username)->countAllResults() > 0){
        		$data = [
					'title' => 'Layanan Internet',
					'validation' => ['username' => 'Username sudah terdaftar di database'],
					'data' => $this->pegawai->find($id),
					'unit' => $this->unit->findAll(),
					'id' => $dekrip
				];
				return view('pegawai/pegawai/pegawai_edit', $data);
        	} else {
        		$update = [
        			'nip'     => $nip,
	        		'nama'    => $nama,
		            'email'   => $email,
		            'username' => $username,
		            'level'   => $level,
		            'id_unit' => $id_unit,
		            'status'  => $status
	        	];
	        	$update_pegawai = $this->pegawai->where('id', $id)->set($update)->update();
	        	if($update_pegawai){
	        		session()->setFlashdata('success', 'Update data pegawai berhasil');
	        		return redirect()->to(base_url('pegawai/pegawai'));
	        	} else {
	        		session()->setFlashdata('errors', 'Update data pegawai gagal');
	        		return redirect()->to(base_url('pegawai/pegawai'));
	        	}
        	}
        }
	}

	public function pegawai_delete(){
		$input = $this->request->getRawInput();
		$id_en = $input['id'];
		$id = dekrip($id_en);
		if($id == session()->get('uid')){
			$response = [
				'status' => 500,
				'message' => 'Anda tidak bisa menghapus data sendiri'
			];
		} else {
			$delete = $this->pegawai->delete($id);
			if($delete){
				$this->pegawai->where('id', $id)->set(['status' => 'N'])->update();
				$response = [
					'status' => 201,
					'message' => 'Data pegawai berhasil dihapus'
				];
			} else {
				$response = [
					'status' => 500,
					'message' => 'Data pegawai gagal dihapus'
				];
			}	
		}
		
		return $this->response->setJSON($response);
	}

	public function dosen_list(){
	  $request = Services::request();
	  $m_dosen = new Dosen_Model($request);
	  if($request->getMethod(true)=='POST'){
	    $lists = $m_dosen->get_datatables();
	        $data = [];
	        $no = $request->getPost("start");
	        foreach ($lists as $list) {
	                $no++;
	                $row = [];
	                $row[] = $no;
	                $row[] = $list->nidn;
	                $row[] = $list->nama;
	                $row[] = $list->email;
	                $row[] = $list->status == 'Y' ? 'Active' : 'Not Active';
	                $row[] = '<a href="'.base_url('pegawai/dosen_edit/'.enkrip($list->id)).'" class="text-secondary"><i class="fa fa-pencil-alt"></i></a> &nbsp; <a href="#" onClick="return deleteDosen('.$list->id.')" class="text-secondary"><i class="fa fa-trash"></i></a>';
	                $data[] = $row;
	    }
	    $output = ["draw" => $request->getPost('draw'),
	                        "recordsTotal" => $m_dosen->count_all(),
	                        "recordsFiltered" => $m_dosen->count_filtered(),
	                        "data" => $data];
	    echo json_encode($output);
	  }
	}

	public function dosen(){
		$data = [
			'title' => 'Layanan Internet'
		];
		return view('pegawai/dosen', $data);
	}

	public function dosen_ver_list(){
	  $request = Services::request();
	  $m_dosen = new Dosen_Model($request);
	  if($request->getMethod(true)=='POST'){
	    $lists = $m_dosen->get_datatables2();
	        $data = [];
	        $no = $request->getPost("start");
	        foreach ($lists as $list) {
	                $no++;
	                $row = [];
	                $row[] = $no;
	                $row[] = $list->nidn;
	                $row[] = $list->nama;
	                $row[] = $list->email;
	                $row[] = $list->status == 'Y' ? 'Approved' : 'Nedd Approval';
	                $row[] = '<a href="#" onClick="return approveDosen('.$list->id.')" class="badge badge-success">Approve</a> &nbsp; <a href="#" onClick="return rejectDosen('.$list->id.')" class="badge badge-danger">Reject</a>';
	                $data[] = $row;
	    }
	    $output = ["draw" => $request->getPost('draw'),
	                        "recordsTotal" => $m_dosen->count_all2(),
	                        "recordsFiltered" => $m_dosen->count_filtered2(),
	                        "data" => $data];
	    echo json_encode($output);
	  }
	}

	public function dosen_ver(){
		$data = [
			'title' => 'Layanan Internet'
		];
		return view('pegawai/dosen_ver', $data);
	}

	public function dosen_approve(){
		$input = $this->request->getRawInput();
		$id_en = $input['id'];
		$id = dekrip($id_en);
		$data_dosen = $this->dosen->find($id);
		$password = random_string();
		$update = $this->dosen->where('id', $id)->set(['status' => 'Y', 'password' => password_hash($password, PASSWORD_DEFAULT)])->update();
		if($update){
			$message = 'Selamat <strong>'.$data_dosen['nama'].'</strong>, anda berhasil registrasi di Layanan Internet. Berikut data untuk masuk ke dalam Layanan Internet:<br><br>
        				Username : <strong>'.$data_dosen['username'].'</strong><br>Password : <strong>'.$password.'</strong><br><br>Silakan untuk update password demi keamanan, terimakasih.';
        	
        	$data = [
        			'to' => $data_dosen['email'],
        			'subject' => 'Registrasi Dosen',
        			'message' => $message
        	];
        	if($this->service_lib->sendMail($data) == TRUE){
        		$response = [
						'status' => 201,
						'message' => 'Approve dosen berhasil'
				];
        	} else {
        		$response = [
						'status' => 500,
						'message' => 'Approve dosen gagal'
				];
        	}
		} else {
			$response = [
					'status' => 500,
					'message' => 'Approve dosen gagal'
			];
		}
		return $this->response->setJSON($response);
	}

	public function dosen_reject(){
		$input = $this->request->getRawInput();
		$id_en = $input['id'];
		$id = dekrip($id_en);
		$this->dosen->where('id', $id)->set(['status' => 'N'])->update();
		$update = $this->dosen->delete($id);
		if($update){
			$response = [
					'status' => 201,
					'message' => 'Reject dosen berhasil'
			];
		} else {
			$response = [
					'status' => 500,
					'message' => 'Reject dosen gagal'
			];
		}
		return $this->response->setJSON($response);
	}

	public function dosen_add(){
		$data = [
			'title' => 'Layanan Internet'
		];
		return view('pegawai/dosen/dosen_add', $data);
	}

	public function dosen_store(){
		$nidn = $this->request->getPost('nidn');
		$nama = $this->request->getPost('nama');
		$username = $this->request->getPost('username');
		$email = $this->request->getPost('email');
		$status = 'Y';

		$validasi = [
			'nidn'     => $nidn,
            'nama'     => $nama,
            'username' => $username,
            'email'    => $email
        ];

        $password = random_string();

        if($this->validation->run($validasi, 'regisDosen') == FALSE){
        	$data = [
				'title' => 'Layanan Internet',
				'validation' => $this->validation->getErrors()
			];
			return view('pegawai/dosen/dosen_add', $data);
        } else {
        	$insert = [
        		'nidn'     => $nidn,
	            'nama'     => $nama,
	            'username' => $username,
	            'email'    => $email,
	            'status'   => $status,
	            'password' => password_hash($password, PASSWORD_DEFAULT)
        	];
        	$save_dosen = $this->dosen->save($insert);
        	$message = 'Selamat <strong>'.$nama.'</strong>, anda berhasil registrasi di Layanan Internet. Berikut data untuk masuk ke dalam Layanan Internet:<br><br>
        				Username : <strong>'.$username.'</strong><br>Password : <strong>'.$password.'</strong><br><br>Silakan untuk update password demi keamanan, terimakasih.';
        	if($save_dosen){
        		$data = [
        			'to' => $email,
        			'subject' => 'Registrasi Dosen',
        			'message' => $message
        		];
        		if($this->service_lib->sendMail($data) == TRUE){
        			session()->setFlashdata('success', 'Tambah data dosen berhasil');
        			return redirect()->to(base_url('pegawai/dosen'));
        		} else {
        			session()->setFlashdata('errors', 'Tambah data dosen gagal, coba periksa kembali koneksi anda');
        		    return redirect()->to(base_url('pegawai/dosen'));
        		}
        	} else {
        		session()->setFlashdata('errors', 'Tambah data dosen gagal');
        		return redirect()->to(base_url('pegawai/dosen'));
        	}
        }
	}

	public function dosen_edit($id){
		$data = [
			'title' => 'Layanan Internet',
			'data' => $this->dosen->find(dekrip($id)),
			'id' => $id
		];
		return view('pegawai/dosen/dosen_edit', $data);
	}

	public function dosen_update(){
		$dekrip = $this->request->getPost('id');
		$nidn = $this->request->getPost('nidn');
		$nama = $this->request->getPost('nama');
		$username = $this->request->getPost('username');
		$username_old = $this->request->getPost('username_old');
		$email = $this->request->getPost('email');
		$email_old = $this->request->getPost('email_old');
		$status = $this->request->getPost('status');
		$id = dekrip($dekrip);

		$validasi = [
			'nidn'    	=> $nidn,
            'nama'    	=> $nama,
            'username'	=> $username,
            'email'  	=> $email,
            'status' 	=> $status
        ];

        if($this->validation->run($validasi, 'updateDosen') == FALSE){
        	$data = [
				'title' => 'Layanan Internet',
				'validation' => $this->validation->getErrors(),
				'data' => $this->dosen->find($id),
				'id' => $dekrip
			];
			return view('pegawai/dosen/dosen_edit', $data);
        } else {
        	if($username_old != $username && $this->dosen->where('username', $username)->countAllResults() > 0){
        		$data = [
					'title' => 'Layanan Internet',
					'validation' => ['username' => 'Username sudah terdaftar di database'],
					'data' => $this->dosen->find($id),
					'id' => $dekrip
				];
				return view('pegawai/dosen/dosen_edit', $data);
        	} else if($email_old != $email && $this->dosen->where('email', $email)->countAllResults() > 0){
        		$data = [
					'title' => 'Layanan Internet',
					'validation' => ['email' => 'Email sudah terdaftar di database'],
					'data' => $this->dosen->find($id),
					'id' => $dekrip
				];
				return view('pegawai/dosen/dosen_edit', $data);
        	} else {
        		$update = [
        			'nidn'    	=> $nidn,
		            'nama'    	=> $nama,
		            'username'	=> $username,
		            'email'  	=> $email,
		            'status' 	=> $status
	        	];
	        	$update_dosen = $this->dosen->where('id', $id)->set($update)->update();
	        	if($update_dosen){
	        		session()->setFlashdata('success', 'Update data dosen berhasil');
	        		return redirect()->to(base_url('pegawai/dosen'));
	        	} else {
	        		session()->setFlashdata('errors', 'Update data dosen gagal');
	        		return redirect()->to(base_url('pegawai/dosen'));
	        	}
        	}
        }
	}

	public function dosen_delete(){
		$input = $this->request->getRawInput();
		$id_en = $input['id'];
		$id = dekrip($id_en);
		
		$delete = $this->dosen->delete($id);
		if($delete){
			$this->dosen->where('id', $id)->set(['status' => 'N'])->update();
			$response = [
				'status' => 201,
				'message' => 'Data dosen berhasil dihapus'
			];
		} else {
			$response = [
				'status' => 500,
				'message' => 'Data dosen gagal dihapus'
			];
		}	
		
		return $this->response->setJSON($response);
	}

	public function mahasiswa_list(){
	  $request = Services::request();
	  $m_mahasiswa = new Mahasiswa_Model($request);
	  if($request->getMethod(true)=='POST'){
	    $lists = $m_mahasiswa->get_datatables();
	        $data = [];
	        $no = $request->getPost("start");
	        foreach ($lists as $list) {
	                $no++;
	                $row = [];
	                $row[] = $no;
	                $row[] = $list->npm;
	                $row[] = $list->nama;
	                $row[] = $list->email;
	                $row[] = $list->nama_prodi;
	                $row[] = $list->nama_fakultas;
	                $row[] = $list->status == 'Y' ? 'Active' : 'Not Active';
	                $row[] = '<a href="'.base_url('pegawai/mahasiswa_edit/'.enkrip($list->id)).'" class="text-secondary"><i class="fa fa-pencil-alt"></i></a> &nbsp; <a href="#" onClick="return deleteMahasiswa('.$list->id.')" class="text-secondary"><i class="fa fa-trash"></i></a>';
	                $data[] = $row;
	    }
	    $output = ["draw" => $request->getPost('draw'),
	                        "recordsTotal" => $m_mahasiswa->count_all(),
	                        "recordsFiltered" => $m_mahasiswa->count_filtered(),
	                        "data" => $data];
	    echo json_encode($output);
	  }
	}

	public function mahasiswa(){
		$data = [
			'title' => 'Layanan Internet'
		];
		return view('pegawai/mahasiswa', $data);
	}

	public function mahasiswa_ver_list(){
	  $request = Services::request();
	  $m_mahasiswa = new Mahasiswa_Model($request);
	  if($request->getMethod(true)=='POST'){
	    $lists = $m_mahasiswa->get_datatables2();
	        $data = [];
	        $no = $request->getPost("start");
	        foreach ($lists as $list) {
	                $no++;
	                $row = [];
	                $row[] = $no;
	                $row[] = $list->npm;
	                $row[] = $list->nama;
	                $row[] = $list->email;
	                $row[] = $list->nama_prodi;
	                $row[] = $list->nama_fakultas;
	                $row[] = $list->status == 'Y' ? 'Approved' : 'Nedd Approval';
	                $row[] = '<a href="#" onClick="return approveMahasiswa('.$list->id.')" class="badge badge-success">Approve</a> &nbsp; <a href="#" onClick="return rejectMahasiswa('.$list->id.')" class="badge badge-danger">Reject</a>';
	                $data[] = $row;
	    }
	    $output = ["draw" => $request->getPost('draw'),
	                        "recordsTotal" => $m_mahasiswa->count_all2(),
	                        "recordsFiltered" => $m_mahasiswa->count_filtered2(),
	                        "data" => $data];
	    echo json_encode($output);
	  }
	}

	public function mahasiswa_ver(){
		$data = [
			'title' => 'Layanan Internet'
		];
		return view('pegawai/mahasiswa_ver', $data);
	}

	public function mahasiswa_approve(){
		$input = $this->request->getRawInput();
		$id_en = $input['id'];
		$id = dekrip($id_en);
		$data_mahasiswa = $this->mahasiswa->find($id);
		$password = random_string();
		$update = $this->mahasiswa->where('id', $id)->set(['status' => 'Y', 'password' => password_hash($password, PASSWORD_DEFAULT)])->update();
		if($update){
			$message = 'Selamat <strong>'.$data_mahasiswa['nama'].'</strong>, anda berhasil registrasi di Layanan Internet. Berikut data untuk masuk ke dalam Layanan Internet:<br><br>
        				Username : <strong>'.$data_mahasiswa['username'].'</strong><br>Password : <strong>'.$password.'</strong><br><br>Silakan untuk update password demi keamanan, terimakasih.';
        	
        	$data = [
        			'to' => $data_mahasiswa['email'],
        			'subject' => 'Registrasi Mahasiswa',
        			'message' => $message
        	];
        	if($this->service_lib->sendMail($data) == TRUE){
        		$response = [
						'status' => 201,
						'message' => 'Approve mahasiswa berhasil'
				];
        	} else {
        		$response = [
						'status' => 500,
						'message' => 'Approve mahasiswa gagal'
				];
        	}
		} else {
			$response = [
					'status' => 500,
					'message' => 'Approve mahasiswa gagal'
			];
		}
		return $this->response->setJSON($response);
	}

	public function mahasiswa_reject(){
		$input = $this->request->getRawInput();
		$id_en = $input['id'];
		$id = dekrip($id_en);
		$this->mahasiswa->where('id', $id)->set(['status' => 'N'])->update();
		$update = $this->mahasiswa->delete($id);
		if($update){
			$response = [
					'status' => 201,
					'message' => 'Reject mahasiswa berhasil'
			];
		} else {
			$response = [
					'status' => 500,
					'message' => 'Reject mahasiswa gagal'
			];
		}
		return $this->response->setJSON($response);
	}

	public function mahasiswa_add(){
		$data = [
			'title' => 'Layanan Internet',
			'fakultas' => $this->fakultas->findAll()
		];
		return view('pegawai/mahasiswa/mahasiswa_add', $data);
	}

	public function mahasiswa_store(){
		$npm = $this->request->getPost('npm');
		$nama = $this->request->getPost('nama');
		$email = $this->request->getPost('email');
		$username = $this->request->getPost('username');
		$id_fakultas = $this->request->getPost('id_fakultas');
		$id_prodi = $this->request->getPost('id_prodi');
		$status = 'Y';

		$validasi = [
			'npm'     		=> $npm,
            'nama'     		=> $nama,
            'email'     	=> $email,
            'username'     	=> $username,
            'id_fakultas'   => $id_fakultas,
            'id_prodi'     	=> $id_prodi
        ];

        $password = random_string();

        if($this->validation->run($validasi, 'regisMahasiswa') == FALSE){
        	$data = [
				'title' => 'Layanan Internet',
				'validation' => $this->validation->getErrors(),
				'fakultas' => $this->fakultas->findAll()
			];
			return view('pegawai/mahasiswa/mahasiswa_add', $data);
        } else {
        	$insert = [
        		'npm'     		=> $npm,
	            'nama'     		=> $nama,
	            'email'     	=> $email,
	            'username'     	=> $username,
	            'id_fakultas'  	=> $id_fakultas,
	            'id_prodi'      => $id_prodi,
	            'status'    	=> $status,
	            'password' 		=> password_hash($password, PASSWORD_DEFAULT)
        	];
        	$save_mahasiswa = $this->mahasiswa->save($insert);
        	$message = 'Selamat <strong>'.$nama.'</strong>, anda berhasil registrasi di Layanan Internet. Berikut data untuk masuk ke dalam Layanan Internet:<br><br>
        				Username : <strong>'.$username.'</strong><br>Password : <strong>'.$password.'</strong><br><br>Silakan untuk update password demi keamanan, terimakasih.';
        	if($save_mahasiswa){
        		$data = [
        			'to' => $email,
        			'subject' => 'Registrasi Mahasiswa',
        			'message' => $message
        		];
        		if($this->service_lib->sendMail($data) == TRUE){
        			session()->setFlashdata('success', 'Tambah data mahasiswa berhasil');
        			return redirect()->to(base_url('pegawai/mahasiswa'));
        		} else {
        			session()->setFlashdata('errors', 'Tambah data dosen gagal, coba periksa kembali koneksi anda');
        		    return redirect()->to(base_url('pegawai/mahasiswa'));
        		}
        	} else {
        		session()->setFlashdata('errors', 'Tambah data mahasiswa gagal');
        		return redirect()->to(base_url('pegawai/mahasiswa'));
        	}
        }
	}

	public function mahasiswa_edit($id){
		$data = [
			'title' => 'Layanan Internet',
			'fakultas' => $this->fakultas->findAll(),
			'data' => $this->mahasiswa->find(dekrip($id)),
			'lib' => $this->service_lib,
			'id' => $id
		];
		return view('pegawai/mahasiswa/mahasiswa_edit', $data);
	}

	public function mahasiswa_update(){
		$dekrip = $this->request->getPost('id');
		$npm = $this->request->getPost('npm');
		$nama = $this->request->getPost('nama');
		$email = $this->request->getPost('email');
		$email_old = $this->request->getPost('email_old');
		$username = $this->request->getPost('username');
		$username_old = $this->request->getPost('username_old');
		$id_fakultas = $this->request->getPost('id_fakultas');
		$id_prodi = $this->request->getPost('id_prodi');
		$status = $this->request->getPost('status');
		$id = dekrip($dekrip);

		$validasi = [
			'npm'     		=> $npm,
            'nama'     		=> $nama,
            'email'     	=> $email,
            'username'     	=> $username,
            'id_fakultas'   => $id_fakultas,
            'id_prodi'     	=> $id_prodi,
            'status'		=> $status
        ];

        if($this->validation->run($validasi, 'updateMahasiswa') == FALSE){
        	$data = [
				'title' => 'Layanan Internet',
				'validation' => $this->validation->getErrors(),
				'data' => $this->mahasiswa->find($id),
				'fakultas' => $this->fakultas->findAll(),
				'lib' => $this->service_lib,
				'id' => $dekrip
			];
			return view('pegawai/mahasiswa/mahasiswa_edit', $data);
        } else {
        	if($username_old != $username && $this->mahasiswa->where('username', $username)->countAllResults() > 0){
        		$data = [
					'title' => 'Layanan Internet',
					'validation' => ['username' => 'Username sudah terdaftar di database'],
					'data' => $this->mahasiswa->find($id),
					'fakultas' => $this->fakultas->findAll(),
					'id' => $dekrip
				];
				return view('pegawai/mahasiswa/mahasiswa_edit', $data);
        	} else if($email_old != $email && $this->mahasiswa->where('email', $email)->countAllResults() > 0){
        		$data = [
					'title' => 'Layanan Internet',
					'validation' => ['email' => 'Email sudah terdaftar di database'],
					'data' => $this->mahasiswa->find($id),
					'fakultas' => $this->fakultas->findAll(),
					'lib' => $this->service_lib,
					'id' => $dekrip
				];
				return view('pegawai/mahasiswa/mahasiswa_edit', $data);
        	} else {
        		$update = [
        			'npm'     		=> $npm,
		            'nama'     		=> $nama,
		            'email'     	=> $email,
		            'username'     	=> $username,
		            'id_fakultas'   => $id_fakultas,
		            'id_prodi'     	=> $id_prodi,
		            'status'		=> $status
	        	];
	        	$update_mahasiswa = $this->mahasiswa->where('id', $id)->set($update)->update();
	        	if($update_mahasiswa){
	        		session()->setFlashdata('success', 'Update data mahasiswa berhasil');
	        		return redirect()->to(base_url('pegawai/mahasiswa'));
	        	} else {
	        		session()->setFlashdata('errors', 'Update data mahasiswa gagal');
	        		return redirect()->to(base_url('pegawai/mahasiswa'));
	        	}
        	}
        }
	}

	public function mahasiswa_delete(){
		$input = $this->request->getRawInput();
		$id_en = $input['id'];
		$id = dekrip($id_en);
		
		$delete = $this->mahasiswa->delete($id);
		if($delete){
			$this->mahasiswa->where('id', $id)->set(['status' => 'N'])->update();
			$response = [
				'status' => 201,
				'message' => 'Data mahasiswa berhasil dihapus'
			];
		} else {
			$response = [
				'status' => 500,
				'message' => 'Data mahasiswa gagal dihapus'
			];
		}	
		
		return $this->response->setJSON($response);
	}

	public function unit_list(){
	  $request = Services::request();
	  $m_unit = new Unit_Model($request);
	  if($request->getMethod(true)=='POST'){
	    $lists = $m_unit->get_datatables();
	        $data = [];
	        $no = $request->getPost("start");
	        foreach ($lists as $list) {
	                $no++;
	                $row = [];
	                $row[] = $no;
	                $row[] = $list->kode_unit;
	                $row[] = $list->nama_unit;
	                $row[] = '<a href="'.base_url('pegawai/unit_edit/'.enkrip($list->id)).'" class="text-secondary"><i class="fa fa-pencil-alt"></i></a> &nbsp; <a href="#" onClick="return deleteUnit('.$list->id.')" class="text-secondary"><i class="fa fa-trash"></i></a>';
	                $data[] = $row;
	    }
	    $output = ["draw" => $request->getPost('draw'),
	                        "recordsTotal" => $m_unit->count_all(),
	                        "recordsFiltered" => $m_unit->count_filtered(),
	                        "data" => $data];
	    echo json_encode($output);
	  }
	}

	public function unit(){
		$data = [
			'title' => 'Layanan Internet'
		];
		return view('pegawai/unit', $data);
	}

	public function unit_add(){
		$data = [
			'title' => 'Layanan Internet'
		];
		return view('pegawai/unit/unit_add', $data);
	}

	public function unit_store(){
		$kode_unit = $this->request->getPost('kode_unit');
		$nama_unit = $this->request->getPost('nama_unit');

		$validasi = [
            'kode_unit' => $kode_unit,
            'nama_unit' => $nama_unit
        ];

        if($this->validation->run($validasi, 'insertUnit') == FALSE){
        	$data = [
				'title' => 'Layanan Internet',
				'validation' => $this->validation->getErrors()
			];
			return view('pegawai/unit/unit_add', $data);
        } else {
        	$insert = [
        		'kode_unit' => $kode_unit,
            	'nama_unit' => $nama_unit
        	];
        	$save_unit = $this->unit->save($insert);
        	if($save_unit){
        		session()->setFlashdata('success', 'Tambah data unit berhasil');
        		return redirect()->to(base_url('pegawai/unit'));		
        	} else {
        		session()->setFlashdata('errors', 'Tambah data unit gagal');
        		return redirect()->to(base_url('pegawai/unit'));
        	}
        }
	}

	public function unit_edit($id){
		$data = [
			'title' => 'Layanan Internet',
			'data' => $this->unit->find(dekrip($id)),
			'id' => $id
		];
		return view('pegawai/unit/unit_edit', $data);
	}

	public function unit_update(){
		$dekrip = $this->request->getPost('id');
		$kode_unit = $this->request->getPost('kode_unit');
		$kode_unit_old = $this->request->getPost('kode_unit_old');
		$nama_unit = $this->request->getPost('nama_unit');
		$id = dekrip($dekrip);

		$validasi = [
			'kode_unit' => $kode_unit,
            'nama_unit' => $nama_unit
        ];

        if($this->validation->run($validasi, 'updateUnit') == FALSE){
        	$data = [
				'title' => 'Layanan Internet',
				'validation' => $this->validation->getErrors(),
				'data' => $this->unit->find($id),
				'id' => $dekrip
			];
			return view('pegawai/unit/unit_edit', $data);
        } else {
        	if($kode_unit_old != $kode_unit && $this->unit->where('kode_unit', $kode_unit)->countAllResults() > 0){
        		$data = [
					'title' => 'Layanan Internet',
					'validation' => ['kode_unit' => 'Kode Unit sudah terdaftar di database'],
					'data' => $this->unit->find($id),
					'id' => $dekrip
				];
				return view('pegawai/unit/unit_edit', $data);
        	} else {
        		$update = [
	        		'kode_unit' => $kode_unit,
	                'nama_unit' => $nama_unit
		        ];
		        $update_unit = $this->unit->where('id', $id)->set($update)->update();
		        if($update_unit){
		        	session()->setFlashdata('success', 'Update data unit berhasil');
		        	return redirect()->to(base_url('pegawai/unit'));
		        } else {
		        	session()->setFlashdata('errors', 'Update data unit gagal');
		        	return redirect()->to(base_url('pegawai/unit'));
		        }
        	}
        }
	}

	public function unit_delete(){
		$input = $this->request->getRawInput();
		$id_en = $input['id'];
		$id = dekrip($id_en);
		
		$delete = $this->unit->delete($id);
		if($delete){
			$response = [
				'status' => 201,
				'message' => 'Data unit berhasil dihapus'
			];
		} else {
			$response = [
				'status' => 500,
				'message' => 'Data unit gagal dihapus'
			];
		}
		return $this->response->setJSON($response);	
	}

	public function fakultas_list(){
	  $request = Services::request();
	  $m_fakultas = new Fakultas_Model($request);
	  if($request->getMethod(true)=='POST'){
	    $lists = $m_fakultas->get_datatables();
	        $data = [];
	        $no = $request->getPost("start");
	        foreach ($lists as $list) {
	                $no++;
	                $row = [];
	                $row[] = $no;
	                $row[] = $list->kode_fakultas;
	                $row[] = $list->nama_fakultas;
	                $row[] = '<a href="'.base_url('pegawai/fakultas_edit/'.enkrip($list->id)).'" class="text-secondary"><i class="fa fa-pencil-alt"></i></a> &nbsp; <a href="#" onClick="return deleteFakultas('.$list->id.')" class="text-secondary"><i class="fa fa-trash"></i></a>';
	                $data[] = $row;
	    }
	    $output = ["draw" => $request->getPost('draw'),
	                        "recordsTotal" => $m_fakultas->count_all(),
	                        "recordsFiltered" => $m_fakultas->count_filtered(),
	                        "data" => $data];
	    echo json_encode($output);
	  }
	}

	public function fakultas(){
		$data = [
			'title' => 'Layanan Internet'
		];
		return view('pegawai/fakultas', $data);
	}

	public function fakultas_add(){
		$data = [
			'title' => 'Layanan Internet'
		];
		return view('pegawai/fakultas/fakultas_add', $data);
	}

	public function fakultas_store(){
		$kode_fakultas = $this->request->getPost('kode_fakultas');
		$nama_fakultas = $this->request->getPost('nama_fakultas');

		$validasi = [
            'kode_fakultas' => $kode_fakultas,
            'nama_fakultas' => $nama_fakultas
        ];

        if($this->validation->run($validasi, 'insertFakultas') == FALSE){
        	$data = [
				'title' => 'Layanan Internet',
				'validation' => $this->validation->getErrors()
			];
			return view('pegawai/fakultas/fakultas_add', $data);
        } else {
        	$insert = [
        		'kode_fakultas' => $kode_fakultas,
            	'nama_fakultas' => $nama_fakultas
        	];
        	$save_fakultas = $this->fakultas->save($insert);
        	if($save_fakultas){
        		session()->setFlashdata('success', 'Tambah data fakultas berhasil');
        		return redirect()->to(base_url('pegawai/fakultas'));		
        	} else {
        		session()->setFlashdata('errors', 'Tambah data fakultas gagal');
        		return redirect()->to(base_url('pegawai/fakultas'));
        	}
        }
	}

	public function fakultas_edit($id){
		$data = [
			'title' => 'Layanan Internet',
			'data' => $this->fakultas->find(dekrip($id)),
			'id' => $id
		];
		return view('pegawai/fakultas/fakultas_edit', $data);
	}

	public function fakultas_update(){
		$dekrip = $this->request->getPost('id');
		$kode_fakultas = $this->request->getPost('kode_fakultas');
		$kode_fakultas_old = $this->request->getPost('kode_fakultas_old');
		$nama_fakultas = $this->request->getPost('nama_fakultas');
		$id = dekrip($dekrip);

		$validasi = [
			'kode_fakultas' => $kode_fakultas,
            'nama_fakultas' => $nama_fakultas
        ];

        if($this->validation->run($validasi, 'updateFakultas') == FALSE){
        	$data = [
				'title' => 'Layanan Internet',
				'validation' => $this->validation->getErrors(),
				'data' => $this->fakultas->find($id),
				'id' => $dekrip
			];
			return view('pegawai/fakultas/fakultas_edit', $data);
        } else {
        	if($kode_fakultas_old != $kode_fakultas && $this->fakultas->where('kode_fakultas', $kode_fakultas)->countAllResults() > 0){
        		$data = [
					'title' => 'Layanan Internet',
					'validation' => ['kode_fakultas' => 'Kode Fakultas sudah terdaftar di database'],
					'data' => $this->fakultas->find($id),
					'id' => $dekrip
				];
				return view('pegawai/fakultas/fakultas_edit', $data);
        	} else {
        		$update = [
	        		'kode_fakultas' => $kode_fakultas,
	                'nama_fakultas' => $nama_fakultas
		        ];
		        $update_fakultas = $this->fakultas->where('id', $id)->set($update)->update();
		        if($update_fakultas){
		        	session()->setFlashdata('success', 'Update data fakultas berhasil');
		        	return redirect()->to(base_url('pegawai/fakultas'));
		        } else {
		        	session()->setFlashdata('errors', 'Update data fakultas gagal');
		        	return redirect()->to(base_url('pegawai/fakultas'));
		        }
        	}
        }
	}

	public function fakultas_delete(){
		$input = $this->request->getRawInput();
		$id_en = $input['id'];
		$id = dekrip($id_en);
		
		$delete = $this->fakultas->delete($id);
		if($delete){
			$response = [
				'status' => 201,
				'message' => 'Data fakultas berhasil dihapus'
			];
		} else {
			$response = [
				'status' => 500,
				'message' => 'Data fakultas gagal dihapus'
			];
		}	
		return $this->response->setJSON($response);
	}

	public function prodi_list(){
	  $request = Services::request();
	  $m_prodi = new Prodi_Model($request);
	  if($request->getMethod(true)=='POST'){
	    $lists = $m_prodi->get_datatables();
	        $data = [];
	        $no = $request->getPost("start");
	        foreach ($lists as $list) {
	                $no++;
	                $row = [];
	                $row[] = $no;
	                $row[] = $list->kode_prodi;
	                $row[] = $list->nama_prodi;
	                $row[] = $list->nama_fakultas;
	                $row[] = '<a href="'.base_url('pegawai/prodi_edit/'.enkrip($list->id)).'" class="text-secondary"><i class="fa fa-pencil-alt"></i></a> &nbsp; <a href="#" onClick="return deleteProdi('.$list->id.')" class="text-secondary"><i class="fa fa-trash"></i></a>';
	                $data[] = $row;
	    }
	    $output = ["draw" => $request->getPost('draw'),
	                        "recordsTotal" => $m_prodi->count_all(),
	                        "recordsFiltered" => $m_prodi->count_filtered(),
	                        "data" => $data];
	    echo json_encode($output);
	  }
	}

	public function get_prodi(){
		$id_fakultas = $this->request->getPost('id_fakultas');
		$data = $this->prodi->where('id_fakultas', $id_fakultas)->find();
		$result[] = '<option value="">Pilih</option>';
		foreach ($data as $key) {
		 	$result[] .= '<option value="'.$key['id'].'">'.$key['nama_prodi'].'</option>';
		}
		echo json_encode($result);
	}

	public function prodi(){
		$data = [
			'title' => 'Layanan Internet'
		];
		return view('pegawai/prodi', $data);
	}

	public function prodi_add(){
		$data = [
			'title' => 'Layanan Internet',
			'fakultas' => $this->fakultas->findAll()
		];
		return view('pegawai/prodi/prodi_add', $data);
	}

	public function prodi_store(){
		$kode_prodi = $this->request->getPost('kode_prodi');
		$nama_prodi = $this->request->getPost('nama_prodi');
		$id_fakultas = $this->request->getPost('id_fakultas');

		$validasi = [
			'kode_prodi'  => $kode_prodi,
			'nama_prodi'  => $nama_prodi,
	        'id_fakultas' => $id_fakultas
        ];

        if($this->validation->run($validasi, 'insertProdi') == FALSE){
        	$data = [
				'title' => 'Layanan Internet',
				'validation' => $this->validation->getErrors()
			];
			return view('pegawai/prodi/prodi_add', $data);
        } else {
        	$insert = [
        		'kode_prodi'  => $kode_prodi,
				'nama_prodi'  => $nama_prodi,
		        'id_fakultas' => $id_fakultas
	        ];
	        $save_prodi = $this->prodi->save($insert);
	        if($save_prodi){
	        	session()->setFlashdata('success', 'Tambah data prodi berhasil');
	        	return redirect()->to(base_url('pegawai/prodi'));
	        } else {
	        	session()->setFlashdata('errors', 'Tambah data prodi gagal');
	        	return redirect()->to(base_url('pegawai/prodi'));
	        }
        }
	}

	public function prodi_edit($id){
		$data = [
			'title' => 'Layanan Internet',
			'data' => $this->prodi->find(dekrip($id)),
			'fakultas' => $this->fakultas->findAll(),
			'lib' => $this->service_lib,
			'id' => $id
		];
		return view('pegawai/prodi/prodi_edit', $data);
	}

	public function prodi_update(){
		$dekrip = $this->request->getPost('id');
		$kode_prodi = $this->request->getPost('kode_prodi');
		$kode_prodi_old = $this->request->getPost('kode_prodi_old');
		$nama_prodi = $this->request->getPost('nama_prodi');
		$id_fakultas = $this->request->getPost('id_fakultas');

		$id = dekrip($dekrip);

		$validasi = [
			'kode_prodi'  => $kode_prodi,
			'nama_prodi'  => $nama_prodi,
	        'id_fakultas' => $id_fakultas
        ];

        if($this->validation->run($validasi, 'updateProdi') == FALSE){
        	$data = [
				'title' => 'Layanan Internet',
				'validation' => $this->validation->getErrors(),
				'data' => $this->prodi->find($id),
				'fakultas' => $this->fakultas->findAll(),
				'id' => $dekrip
			];
			return view('pegawai/prodi/prodi_edit', $data);
        } else {
        	if($kode_prodi_old != $kode_prodi && $this->prodi->where('kode_prodi', $kode_fakultas)->countAllResults() > 0){
        		$data = [
					'title' => 'Layanan Internet',
					'validation' => ['kode_prodi' => 'Kode Prodi sudah terdaftar di database'],
					'data' => $this->prodi->find($id),
					'fakultas' => $this->fakultas->findAll(),
					'id' => $dekrip
				];
				return view('pegawai/prodi/prodi_edit', $data);
        	} else {
        		$update = [
	        		'kode_prodi'  => $kode_prodi,
					'nama_prodi'  => $nama_prodi,
			        'id_fakultas' => $id_fakultas
		        ];
		        $update_prodi = $this->prodi->where('id', $id)->set($update)->update();
		        if($update_prodi){
		        	session()->setFlashdata('success', 'Update data prodi berhasil');
		        	return redirect()->to(base_url('pegawai/prodi'));
		        } else {
		        	session()->setFlashdata('errors', 'Update data prodi gagal');
		        	return redirect()->to(base_url('pegawai/prodi'));
		        }
        	}
        }
	}

	public function prodi_delete(){
		$input = $this->request->getRawInput();
		$id_en = $input['id'];
		$id = dekrip($id_en);
		
		$delete = $this->prodi->delete($id);
		if($delete){
			$response = [
				'status' => 201,
				'message' => 'Data prodi berhasil dihapus'
			];
		} else {
			$response = [
				'status' => 500,
				'message' => 'Data prodi gagal dihapus'
			];
		}	
		return $this->response->setJSON($response);
	}

	public function pengajuan_list(){
	  $data_status = ["0" => "need approval", "1" => "approved", "2" => "rejected"];
	  $request = Services::request();
	  $m_bandwidth = new Bandwidth_Model($request);
	  if($request->getMethod(true)=='POST'){
	    $lists = $m_bandwidth->get_datatables();
	        $data = [];
	        $no = $request->getPost("start");
	        foreach ($lists as $list) {
	                $no++;
	                $row = [];
	                $row[] = $no;
	                $row[] = (($list->type == 'pegawai') ? $this->service_lib->getUsernamePegawai($list->id_user) : (($list->type == 'dosen') ? $this->service_lib->getUsernameDosen($list->id_user) : $this->service_lib->getUsernameMahasiswa($list->id_user)));
	                $row[] = $list->type;
	                $row[] = $list->bandwidth_upload;
	                $row[] = $list->bandwidth_download;
	                $row[] = $data_status[$list->status];
	                $row[] = '<a href="'.base_url('pegawai/bandwidth_approval/'.enkrip($list->id)).'" class="badge badge-success">Approve</a> &nbsp; <a href="#" onClick="return rejectBandwidth('.$list->id.')" class="badge badge-danger">Reject</a>';
	                $data[] = $row;
	    }
	    $output = ["draw" => $request->getPost('draw'),
	                        "recordsTotal" => $m_bandwidth->count_all(),
	                        "recordsFiltered" => $m_bandwidth->count_filtered(),
	                        "data" => $data];
	    echo json_encode($output);
	  }
	}

	public function pengajuan(){
		$data = [
			'title' => 'Layanan Internet'
		];
		return view('pegawai/pengajuan', $data);
	}

	public function bandwidth_list(){
	  $data_status = ["0" => "need approval", "1" => "approved", "2" => "rejected"];
	  $request = Services::request();
	  $m_bandwidth = new Bandwidth_Model($request);
	  if($request->getMethod(true)=='POST'){
	    $lists = $m_bandwidth->get_datatables2();
	        $data = [];
	        $no = $request->getPost("start");
	        foreach ($lists as $list) {
	                $no++;
	                $row = [];
	                $row[] = $no;
	                $row[] = (($list->type == 'pegawai') ? $this->service_lib->getUsernamePegawai($list->id_user) : (($list->type == 'dosen') ? $this->service_lib->getUsernameDosen($list->id_user) : $this->service_lib->getUsernameMahasiswa($list->id_user)));
	                $row[] = $list->type;
	                $row[] = $list->bandwidth_upload;
	                $row[] = $list->bandwidth_download;
	                $row[] = $data_status[$list->status];
	                $row[] = '<a href="'.base_url('pegawai/bandwidth_edit/'.enkrip($list->id)).'" class="text-secondary"><i class="fa fa-pencil-alt"></i></a> &nbsp; <a href="#" onClick="return deleteBandwidth('.$list->id.')" class="text-secondary"><i class="fa fa-trash"></i></a>';
	                $data[] = $row;
	    }
	    $output = ["draw" => $request->getPost('draw'),
	                        "recordsTotal" => $m_bandwidth->count_all2(),
	                        "recordsFiltered" => $m_bandwidth->count_filtered2(),
	                        "data" => $data];
	    echo json_encode($output);
	  }
	}

	public function bandwidth(){
		$data = [
			'title' => 'Layanan Internet'
		];
		return view('pegawai/bandwidth', $data);
	}

	public function monitoring_list(){
	  $data_status = ["0" => "need approval", "1" => "approved", "2" => "rejected"];
	  $request = Services::request();
	  $m_bandwidth = new Bandwidth_Model($request);
	  if($request->getMethod(true)=='POST'){
	    $lists = $m_bandwidth->get_datatables2();
	        $data = [];
	        $no = $request->getPost("start");
	        foreach ($lists as $list) {
	                $no++;
	                $row = [];
	                $row[] = $no;
	                $row[] = (($list->type == 'pegawai') ? $this->service_lib->getUsernamePegawai($list->id_user) : (($list->type == 'dosen') ? $this->service_lib->getUsernameDosen($list->id_user) : $this->service_lib->getUsernameMahasiswa($list->id_user)));
	                $row[] = $list->type;
	                $row[] = $list->last_login != NULL ? date('d-m-Y H:i:s', strtotime($list->last_login)) : '-';
	                $row[] = $list->last_logout != NULL ? date('d-m-Y H:i:s', strtotime($list->last_logout)) : '-';
	                $data[] = $row;
	    }
	    $output = ["draw" => $request->getPost('draw'),
	                        "recordsTotal" => $m_bandwidth->count_all2(),
	                        "recordsFiltered" => $m_bandwidth->count_filtered2(),
	                        "data" => $data];
	    echo json_encode($output);
	  }
	}

	public function monitoring(){
		$data = [
			'title' => 'Layanan Internet'
		];
		return view('pegawai/monitoring', $data);
	}

	public function bandwidth_add(){
		$data = [
			'title' => 'Layanan Internet'
		];
		return view('pegawai/bandwidth/bandwidth_add', $data);
	}

	public function bandwidth_store(){
		$type = $this->request->getPost('type');
		$id_user = $this->request->getPost('id_user');
		$bandwidth_upload = $this->request->getPost('bandwidth_upload');
		$bandwidth_download = $this->request->getPost('bandwidth_download');
		$status = 1;

		$validasi = [
            'id_user' => $id_user,
            'type' => $type,
            'bandwidth_upload' => $bandwidth_upload,
            'bandwidth_download' => $bandwidth_download
        ];

        if($this->validation->run($validasi, 'insertBandwidth') == FALSE){
        	$data = [
				'title' => 'Layanan Internet',
				'validation' => $this->validation->getErrors()
			];
			return view('pegawai/bandwidth/bandwidth_add', $data);
        } else {
        	$insert = [
        		'id_user' => $id_user,
	            'type' => $type,
	            'bandwidth_upload' => $bandwidth_upload,
	            'bandwidth_download' => $bandwidth_download,
	            'status' => $status
        	];
        	$save_bandwidth = $this->bandwidth->save($insert);
        	if($save_bandwidth){
        		session()->setFlashdata('success', 'Tambah data bandwidth berhasil');
        		return redirect()->to(base_url('pegawai/bandwidth'));		
        	} else {
        		session()->setFlashdata('errors', 'Tambah data bandwidth gagal');
        		return redirect()->to(base_url('pegawai/bandwidth'));
        	}
        }
	}

	public function bandwidth_edit($id){
		$data = [
			'title' => 'Layanan Internet',
			'data' => $this->bandwidth->find(dekrip($id)),
			'lib' => $this->service_lib,
			'id' => $id
		];
		return view('pegawai/bandwidth/bandwidth_edit', $data);
	}

	public function bandwidth_update(){
		$dekrip = $this->request->getPost('id');
		$bandwidth_upload = $this->request->getPost('bandwidth_upload');
		$bandwidth_download = $this->request->getPost('bandwidth_download');
		$status =  $this->request->getPost('status');
		$id = dekrip($dekrip);

		$validasi = [
			'bandwidth_upload' => $bandwidth_upload,
            'bandwidth_download' => $bandwidth_download,
            'status' => $status
        ];

        if($this->validation->run($validasi, 'updateBandwidth') == FALSE){
        	$data = [
				'title' => 'Layanan Internet',
				'validation' => $this->validation->getErrors(),
				'data' => $this->bandwidth->find($id),
				'lib' => $this->service_lib,
				'id' => $dekrip
			];
			return view('pegawai/bandwidth/bandwidth_edit', $data);
        } else {

        	$update = [
	        	'bandwidth_upload' => $bandwidth_upload,
		        'bandwidth_download' => $bandwidth_download,
		        'status' => $status
		    ];
		    $update_bandwidth = $this->bandwidth->where('id', $id)->set($update)->update();
		    if($update_bandwidth){
		      	session()->setFlashdata('success', 'Update manajemen bandwidth berhasil');
		       	return redirect()->to(base_url('pegawai/bandwidth'));
		    } else {
		       	session()->setFlashdata('errors', 'Update manajemen bandwidth gagal');
		       	return redirect()->to(base_url('pegawai/bandwidth'));
		    }
        }
	}

	public function bandwidth_approval($id){
		$data = [
			'title' => 'Layanan Internet',
			'data' => $this->bandwidth->where('id', dekrip($id))->first(),
			'lib' => $this->service_lib,
			'id' => $id
		];
		return view('pegawai/bandwidth/bandwidth_approve', $data);
	}

	public function bandwidth_approve(){
		$dekrip = $this->request->getPost('id');
		$bandwidth_upload = $this->request->getPost('bandwidth_upload');
		$bandwidth_download = $this->request->getPost('bandwidth_download');
		$status =  1;
		$id = dekrip($dekrip);

		$validasi = [
			'bandwidth_upload' => $bandwidth_upload,
            'bandwidth_download' => $bandwidth_download
        ];

        if($this->validation->run($validasi, 'approveBandwidth') == FALSE){
        	$data = [
				'title' => 'Layanan Internet',
				'validation' => $this->validation->getErrors(),
				'data' => $this->bandwidth->find($id),
				'id' => $dekrip
			];
			return view('pegawai/bandwidth/bandwidth_approve', $data);
        } else {
        	$update = [
	       		'bandwidth_upload' => $bandwidth_upload,
		        'bandwidth_download' => $bandwidth_download,
	            'status' => $status
		    ];
		    $update_bandwidth = $this->bandwidth->where('id', $id)->set($update)->update();
		    if($update_bandwidth){
		       	session()->setFlashdata('success', 'approve pelayanan internet berhasil');
		       	return redirect()->to(base_url('pegawai/pengajuan'));
		    } else {
		       	session()->setFlashdata('errors', 'approve pelayanan internet gagal');
		       	return redirect()->to(base_url('pegawai/pengajuan'));
		    }
        }
	}

	public function bandwidth_reject(){
		$id_en = $this->request->getPost('id');
		$id = dekrip($id_en);
		$this->bandwidth->where('id', $id)->set(['status' => '2'])->update();
		$delete = $this->bandwidth->where('id', $id)->delete();
		if($delete){
			$response = [
				'status' => 201,
				'message' => 'Reject pengajuan layanan internet berhasil'
			];
		} else {
			$response = [
				'status' => 500,
				'message' => 'Reject pengajuan layanan internet gagal'
			];
		}
		return $this->response->setJSON($response);
	}

	public function bandwidth_delete(){
		$input = $this->request->getRawInput();
		$id_en = $input['id'];
		$id = dekrip($id_en);
		
		$delete = $this->bandwidth->delete($id);
		if($delete){
			$response = [
				'status' => 201,
				'message' => 'Data pengajuan layanan internet berhasil dihapus'
			];
		} else {
			$response = [
				'status' => 500,
				'message' => 'Data pengajuan layanan internet gagal dihapus'
			];
		}	
		return $this->response->setJSON($response);
	}

	public function layanan(){
		$type = 'pegawai';
		$id_user = session()->get('uid');
		$data = [
			'title' => 'Layanan Internet',
			'data' => $this->bandwidth->where('type', $type)->where('id_user', $id_user)->first(),
			'lib' => $this->service_lib,
			'id' => $id_user
		];
		return view('pegawai/layanan', $data);
	}

	public function layanan_submit(){
		$input = $this->request->getRawInput();
		$id_en = $input['id'];
		$id = dekrip($id_en);

		$insert = [
			'id_user' => $id,
			'type' => 'pegawai',
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
		$this->bandwidth->where('id_user', $id)->where('type', 'pegawai')->set(['status' => 2])->update();
		$update = $this->bandwidth->where('id_user', $id)->where('type', 'pegawai')->delete();
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

	public function laporan_list(){
	  $data_status = ["0" => "need approval", "1" => "approved", "2" => "rejected"];
	  $request = Services::request();
	  $m_bandwidth = new Bandwidth_Model($request);
	  if($request->getMethod(true)=='POST'){
	    $lists = $m_bandwidth->get_datatables2();
	        $data = [];
	        $no = $request->getPost("start");
	        foreach ($lists as $list) {
	                $no++;
	                $row = [];
	                $row[] = $no;
	                $row[] = (($list->type == 'pegawai') ? $this->service_lib->getUsernamePegawai($list->id_user) : (($list->type == 'dosen') ? $this->service_lib->getUsernameDosen($list->id_user) : $this->service_lib->getUsernameMahasiswa($list->id_user)));
	                $row[] = $list->type;
	                $row[] = $list->bandwidth_upload;
	                $row[] = $list->bandwidth_download;
	                $row[] = $list->last_login != NULL ? date('d-m-Y H:i:s', strtotime($list->last_login)) : '-';
	                $row[] = $list->last_logout != NULL ? date('d-m-Y H:i:s', strtotime($list->last_logout)) : '-';
	                $row[] = $data_status[$list->status];
	                $data[] = $row;
	    }
	    $output = ["draw" => $request->getPost('draw'),
	                        "recordsTotal" => $m_bandwidth->count_all2(),
	                        "recordsFiltered" => $m_bandwidth->count_filtered2(),
	                        "data" => $data];
	    echo json_encode($output);
	  }
	}

	public function laporan(){
		$data = [
			'title' => 'Layanan Internet'
		];
		return view('pegawai/laporan', $data);
	}

	public function logout()
	{
		session()->destroy();
		return redirect()->to(base_url('login'));
	}

}
