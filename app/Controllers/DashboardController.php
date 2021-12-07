<?php
namespace App\Controllers;

use App\Libraries\PDF;
use App\Libraries\Service_Lib;
use App\Models\BahanBakar_Model;
use App\Models\Cabang_Model;
use App\Models\DanaKeluar_Model;
use App\Models\DanaMasuk_Model;
use App\Models\Dokumentasi_Model;
use App\Models\Kegiatan_Model;
use App\Models\Kelola_Kegiatan_Model;
use App\Models\Kendaraan_Model;
use App\Models\Operasional_Model;
use App\Models\Pegawai_Model;
use App\Models\PemakaianBBM_Model;
use App\Models\PemakaianMaterial_Model;
use App\Models\JenisMaterialModel;
use App\Models\PenggunaanKendaraan_Model;
use App\Models\Proyek_Model;
use App\Models\Transaksi_Model;
use App\Models\Users_Model;
use Config\Services;
use App\Libraries\Decode;
use CodeIgniter\API\ResponseTrait;

class DashboardController extends BaseController
{
    use ResponseTrait;
    public function __construct()
    {
        helper(['url', 'form', 'general']);
        $this->validation = \Config\Services::validation();
        $this->pegawai = new Pegawai_Model();
        $this->users = new Users_Model();
        $this->cabang = new Cabang_Model();
        $this->kendaraan = new Kendaraan_Model();
        $this->proyek = new Proyek_Model();
        $this->kegiatan = new Kegiatan_Model();
        $this->kelola_kegiatan = new Kelola_Kegiatan_Model();
        $this->operasional = new Operasional_Model();
        $this->transaksi = new Transaksi_Model();
        $this->dana = new DanaMasuk_Model();
        $this->danaKeluar = new DanaKeluar_Model();
        $this->dokumentasi = new Dokumentasi_Model();
        $this->penggunaan = new PenggunaanKendaraan_Model();
        $this->pemakaian_bbm = new PemakaianBBM_Model();
        $this->material = new PemakaianMaterial_Model();
        $this->jenis_material = new JenisMaterialModel();
        $this->bbm = new BahanBakar_Model();
        $this->decode = new Decode();
        $this->service_lib = new Service_Lib();
    }

    public function cetak_pdf()
    {
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
            'title' => 'Manajemen Proyek',
            'bio' => $self,
            'lib' => $this->service_lib,
        ];
        return view('dashboard', $data);
    }

    public function profile()
    {
        $id = session()->get('uid');
        $data = [
            'title' => 'Manajemen Proyek',
            'data' => $this->users->find($id),
            'lib' => $this->service_lib,
        ];
        return view('profile', $data);
    }

    public function password_edit()
    {
        $id = session()->get('uid');
        $data = [
            'title' => 'Manajemen Proyek',
            'id' => enkrip($id),
        ];
        return view('password_edit', $data);
    }

    public function password_update()
    {
        $dekrip = $this->request->getPost('id');
        $password_lama = $this->request->getPost('password_lama');
        $password_baru = $this->request->getPost('password_baru');
        $confirm_password_baru = $this->request->getPost('confirm_password_baru');
        $id = dekrip($dekrip);
        $data = $this->users->find($id);

        $validasi = [
            'password_lama' => $password_lama,
            'password_baru' => $password_baru,
            'confirm_password_baru' => $confirm_password_baru,
        ];

        if ($this->validation->run($validasi, 'updatePassword') == false) {
            $data = [
                'title' => 'Manajemen Proyek',
                'validation' => $this->validation->getErrors(),
                'id' => $dekrip,
            ];
            return view('password_edit', $data);
        } else {
            if (password_verify($password_lama, $data['password']) == false) {
                $data = [
                    'title' => 'Manajemen Proyek',
                    'validation' => ['password_lama' => 'Password lama salah'],
                    'id' => $dekrip,
                ];
                return view('password_edit', $data);
            } else {
                $update = [
                    'password' => password_hash($password_baru, PASSWORD_DEFAULT),
                ];
                $save_pegawai = $this->users->where('id', $id)->set($update)->update();
                if ($save_pegawai) {
                    session()->setFlashdata('success', 'Update password berhasil');
                    return redirect()->to(base_url('dashboard/password_edit'));
                } else {
                    session()->setFlashdata('errors', 'Update password gagal');
                    return redirect()->to(base_url('dashboard/password_edit'));
                }
            }
        }
    }

    public function pegawai_list()
    {
        $request = Services::request();
        $m_pegawai = new Pegawai_Model($request);
        if ($request->getMethod(true) == 'POST') {
            $lists = $m_pegawai->get_datatables();
            $data = [];
            $no = $request->getPost("start");
            foreach ($lists as $list) {
                $no++;
                $row = [];
                $row[] = $no;
                $row[] = $list->nama;
                $row[] = $list->alamat;
                $row[] = $list->tempat_lahir;
                $row[] = date('d-m-Y', strtotime($list->tgl_lahir));
                $row[] = $list->email;
                $row[] = $list->no_telp;
                $row[] = '<a href="' . base_url('dashboard/pegawai_edit/' . enkrip($list->id)) . '" class="text-secondary"><i class="fa fa-pencil-alt"></i></a> &nbsp; <a href="#" onClick="return deletePegawai(' . $list->id . ')" class="text-secondary"><i class="fa fa-trash"></i></a>';
                $data[] = $row;
            }
            $output = ["draw" => $request->getPost('draw'),
                "recordsTotal" => $m_pegawai->count_all(),
                "recordsFiltered" => $m_pegawai->count_filtered(),
                "data" => $data];
            echo json_encode($output);
        }
    }

    public function pegawai()
    {
        $data = [
            'title' => 'Manajemen Proyek',
        ];
        return view('admin/pegawai', $data);
    }

    public function pegawai_lap_list()
    {
        $request = Services::request();
        $m_pegawai = new Pegawai_Model($request);
        if ($request->getMethod(true) == 'POST') {
            $lists = $m_pegawai->get_datatables();
            $data = [];
            $no = $request->getPost("start");
            foreach ($lists as $list) {
                $no++;
                $row = [];
                $row[] = $no;
                $row[] = $list->nama;
                $row[] = $list->alamat;
                $row[] = $list->tempat_lahir;
                $row[] = date('d-m-Y', strtotime($list->tgl_lahir));
                $row[] = $list->email;
                $row[] = $list->no_telp;
                $data[] = $row;
            }
            $output = ["draw" => $request->getPost('draw'),
                "recordsTotal" => $m_pegawai->count_all(),
                "recordsFiltered" => $m_pegawai->count_filtered(),
                "data" => $data];
            echo json_encode($output);
        }
    }

    public function pegawai_lap()
    {
        $data = [
            'title' => 'Manajemen Proyek',
        ];
        return view('direktur/pegawai', $data);
    }

    public function pegawai_add()
    {
        $data = [
            'title' => 'Manajemen Proyek',
        ];
        return view('admin/pegawai/pegawai_add', $data);
    }

    public function pegawai_store()
    {
        $nama = $this->request->getPost('nama');
        $alamat = $this->request->getPost('alamat');
        $tempat_lahir = $this->request->getPost('tempat_lahir');
        $tgl_lahir = $this->request->getPost('tgl_lahir');
        $email = $this->request->getPost('email');
        $no_telp = $this->request->getPost('no_telp');

        $validasi = [
            'nama' => $nama,
            'alamat' => $alamat,
            'tempat_lahir' => $tempat_lahir,
            'tgl_lahir' => $tgl_lahir,
            'email' => $email,
            'no_telp' => $no_telp,
        ];

        $password = random_string();

        if ($this->validation->run($validasi, 'regisPegawai') == false) {
            $data = [
                'title' => 'Manajemen Proyek',
                'validation' => $this->validation->getErrors(),
            ];
            return view('admin/pegawai/pegawai_add', $data);
        } else {
            $insert = [
                'nama' => $nama,
                'alamat' => $alamat,
                'tempat_lahir' => $tempat_lahir,
                'tgl_lahir' => $tgl_lahir,
                'email' => $email,
                'no_telp' => $no_telp,
            ];
            $save_pegawai = $this->pegawai->save($insert);
            if ($save_pegawai) {
                session()->setFlashdata('success', 'Tambah data pegawai berhasil');
                return redirect()->to(base_url('dashboard/pegawai'));
            } else {
                session()->setFlashdata('errors', 'Tambah data pegawai gagal');
                return redirect()->to(base_url('dashboard/pegawai'));
            }
        }
    }

    public function pegawai_edit($id)
    {
        $data = [
            'title' => 'Manajemen Proyek',
            'data' => $this->pegawai->find(dekrip($id)),
            'id' => $id,
        ];
        return view('admin/pegawai/pegawai_edit', $data);

    }

    public function pegawai_update()
    {
        $dekrip = $this->request->getPost('id');
        $nama = $this->request->getPost('nama');
        $alamat = $this->request->getPost('alamat');
        $tempat_lahir = $this->request->getPost('tempat_lahir');
        $tgl_lahir = $this->request->getPost('tgl_lahir');
        $email = $this->request->getPost('email');
        $no_telp = $this->request->getPost('no_telp');
        $id = dekrip($dekrip);

        $validasi = [
            'nama' => $nama,
            'alamat' => $alamat,
            'tempat_lahir' => $tempat_lahir,
            'tgl_lahir' => $tgl_lahir,
            'email' => $email,
            'no_telp' => $no_telp,
        ];

        if ($this->validation->run($validasi, 'regisPegawai') == false) {
            $data = [
                'title' => 'Manajemen Proyek',
                'validation' => $this->validation->getErrors(),
                'data' => $this->pegawai->find($id),
                'id' => $dekrip,
            ];
            return view('admin/pegawai/pegawai_edit', $data);
        } else {
            $update = [
                'nama' => $nama,
                'alamat' => $alamat,
                'tempat_lahir' => $tempat_lahir,
                'tgl_lahir' => $tgl_lahir,
                'email' => $email,
                'no_telp' => $no_telp,
            ];
            $update_pegawai = $this->pegawai->where('id', $id)->set($update)->update();
            if ($update_pegawai) {
                session()->setFlashdata('success', 'Update data pegawai berhasil');
                return redirect()->to(base_url('dashboard/pegawai'));
            } else {
                session()->setFlashdata('errors', 'Update data pegawai gagal');
                return redirect()->to(base_url('dashboard/pegawai'));
            }
        }
    }

    public function pegawai_delete()
    {
        $input = $this->request->getRawInput();
        $id_en = $input['id'];
        $id = dekrip($id_en);
        $delete = $this->pegawai->delete($id);
        if ($delete) {
            $response = [
                'status' => 201,
                'message' => 'Data pegawai berhasil dihapus',
            ];
        } else {
            $response = [
                'status' => 500,
                'message' => 'Data pegawai gagal dihapus',
            ];
        }

        return $this->response->setJSON($response);
    }

    public function cabang_list()
    {
        $request = Services::request();
        $m_cabang = new Cabang_Model($request);
        if ($request->getMethod(true) == 'POST') {
            $lists = $m_cabang->get_datatables();
            $data = [];
            $no = $request->getPost("start");
            foreach ($lists as $list) {
                $no++;
                $row = [];
                $row[] = $no;
                $row[] = $list->nama_cabang;
                $row[] = $list->alamat_cabang;
                $row[] = $list->email_cabang;
                $row[] = $list->telp_cabang;
                $row[] = '<a href="' . base_url('dashboard/cabang_edit/' . enkrip($list->id)) . '" class="text-secondary"><i class="fa fa-pencil-alt"></i></a> &nbsp; <a href="#" onClick="return deleteCabang(' . $list->id . ')" class="text-secondary"><i class="fa fa-trash"></i></a>';
                $data[] = $row;
            }
            $output = ["draw" => $request->getPost('draw'),
                "recordsTotal" => $m_cabang->count_all(),
                "recordsFiltered" => $m_cabang->count_filtered(),
                "data" => $data];
            echo json_encode($output);
        }
    }

    public function cabang()
    {
        $data = [
            'title' => 'Manajemen Proyek',
        ];
        return view('admin/cabang', $data);
    }

    public function cabang_lap_list()
    {
        $request = Services::request();
        $m_cabang = new Cabang_Model($request);
        if ($request->getMethod(true) == 'POST') {
            $lists = $m_cabang->get_datatables();
            $data = [];
            $no = $request->getPost("start");
            foreach ($lists as $list) {
                $no++;
                $row = [];
                $row[] = $no;
                $row[] = $list->nama_cabang;
                $row[] = $list->alamat_cabang;
                $row[] = $list->email_cabang;
                $row[] = $list->telp_cabang;
                $data[] = $row;
            }
            $output = ["draw" => $request->getPost('draw'),
                "recordsTotal" => $m_cabang->count_all(),
                "recordsFiltered" => $m_cabang->count_filtered(),
                "data" => $data];
            echo json_encode($output);
        }
    }

    public function cabang_lap()
    {
        $data = [
            'title' => 'Manajemen Proyek',
        ];
        return view('direktur/cabang', $data);
    }

    public function cabang_add()
    {
        $data = [
            'title' => 'Manajemen Proyek',
        ];
        return view('admin/cabang/cabang_add', $data);
    }

    public function cabang_store()
    {
        $nama_cabang = $this->request->getPost('nama_cabang');
        $alamat_cabang = $this->request->getPost('alamat_cabang');
        $email_cabang = $this->request->getPost('email_cabang');
        $telp_cabang = $this->request->getPost('telp_cabang');

        $validasi = [
            'nama_cabang' => $nama_cabang,
            'alamat_cabang' => $alamat_cabang,
            'email_cabang' => $email_cabang,
            'telp_cabang' => $telp_cabang,
        ];

        if ($this->validation->run($validasi, 'insertCabang') == false) {
            $data = [
                'title' => 'Manajemen Proyek',
                'validation' => $this->validation->getErrors(),
            ];
            return view('admin/cabang/cabang_add', $data);
        } else {
            $insert = [
                'nama_cabang' => $nama_cabang,
                'alamat_cabang' => $alamat_cabang,
                'email_cabang' => $email_cabang,
                'telp_cabang' => $telp_cabang,
            ];
            $save = $this->cabang->save($insert);
            if ($save) {
                session()->setFlashdata('success', 'Tambah data cabang berhasil');
                return redirect()->to(base_url('dashboard/cabang'));
            } else {
                session()->setFlashdata('errors', 'Tambah data cabang gagal');
                return redirect()->to(base_url('dashboard/cabang'));
            }
        }
    }

    public function cabang_edit($id)
    {
        $data = [
            'title' => 'Manajemen Proyek',
            'data' => $this->cabang->find(dekrip($id)),
            'id' => $id,
        ];
        return view('admin/cabang/cabang_edit', $data);

    }

    public function cabang_update()
    {
        $dekrip = $this->request->getPost('id');
        $nama_cabang = $this->request->getPost('nama_cabang');
        $alamat_cabang = $this->request->getPost('alamat_cabang');
        $email_cabang = $this->request->getPost('email_cabang');
        $telp_cabang = $this->request->getPost('telp_cabang');
        $id = dekrip($dekrip);

        $validasi = [
            'nama_cabang' => $nama_cabang,
            'alamat_cabang' => $alamat_cabang,
            'email_cabang' => $email_cabang,
            'telp_cabang' => $telp_cabang,
        ];

        if ($this->validation->run($validasi, 'insertCabang') == false) {
            $data = [
                'title' => 'Manajemen Proyek',
                'validation' => $this->validation->getErrors(),
                'data' => $this->cabang->find($id),
                'id' => $dekrip,
            ];
            return view('admin/cabang/cabang_edit', $data);
        } else {
            $update = [
                'nama_cabang' => $nama_cabang,
                'alamat_cabang' => $alamat_cabang,
                'email_cabang' => $email_cabang,
                'telp_cabang' => $telp_cabang,
            ];
            $update_cabang = $this->cabang->where('id', $id)->set($update)->update();
            if ($update_cabang) {
                session()->setFlashdata('success', 'Update data cabang berhasil');
                return redirect()->to(base_url('dashboard/cabang'));
            } else {
                session()->setFlashdata('errors', 'Update data cabang gagal');
                return redirect()->to(base_url('dashboard/cabang'));
            }
        }
    }

    public function cabang_delete()
    {
        $input = $this->request->getRawInput();
        $id_en = $input['id'];
        $id = dekrip($id_en);
        $delete = $this->cabang->delete($id);
        if ($delete) {
            $response = [
                'status' => 201,
                'message' => 'Data cabang berhasil dihapus',
            ];
        } else {
            $response = [
                'status' => 500,
                'message' => 'Data cabang gagal dihapus',
            ];
        }

        return $this->response->setJSON($response);
    }

    public function kendaraan_list()
    {
        $request = Services::request();
        $m_kendaraan = new Kendaraan_Model($request);
        if ($request->getMethod(true) == 'POST') {
            $lists = $m_kendaraan->get_datatables();
            $data = [];
            $no = $request->getPost("start");
            foreach ($lists as $list) {
                $no++;
                $row = [];
                $row[] = $no;
                $row[] = $list->jenis_kendaraan;
                $row[] = $list->nomor_polisi;
                $row[] = $list->nomor_mesin;
                $row[] = '<a href="' . base_url('dashboard/kendaraan_edit/' . enkrip($list->id)) . '" class="text-secondary"><i class="fa fa-pencil-alt"></i></a> &nbsp; <a href="#" onClick="return deleteKendaraan(' . $list->id . ')" class="text-secondary"><i class="fa fa-trash"></i></a>';
                $data[] = $row;
            }
            $output = ["draw" => $request->getPost('draw'),
                "recordsTotal" => $m_kendaraan->count_all(),
                "recordsFiltered" => $m_kendaraan->count_filtered(),
                "data" => $data];
            echo json_encode($output);
        }
    }

    public function kendaraan()
    {
        $data = [
            'title' => 'Manajemen Proyek',
        ];
        return view('admin/kendaraan', $data);
    }

    public function kendaraan_lap_list()
    {
        $request = Services::request();
        $m_kendaraan = new Kendaraan_Model($request);
        if ($request->getMethod(true) == 'POST') {
            $lists = $m_kendaraan->get_datatables();
            $data = [];
            $no = $request->getPost("start");
            foreach ($lists as $list) {
                $no++;
                $row = [];
                $row[] = $no;
                $row[] = $list->jenis_kendaraan;
                $row[] = $list->nomor_polisi;
                $row[] = $list->nomor_mesin;
                $data[] = $row;
            }
            $output = ["draw" => $request->getPost('draw'),
                "recordsTotal" => $m_kendaraan->count_all(),
                "recordsFiltered" => $m_kendaraan->count_filtered(),
                "data" => $data];
            echo json_encode($output);
        }
    }

    public function kendaraan_lap()
    {
        $data = [
            'title' => 'Manajemen Proyek',
        ];
        return view('direktur/kendaraan', $data);
    }

    public function kendaraan_add()
    {
        $data = [
            'title' => 'Manajemen Proyek',
        ];
        return view('admin/kendaraan/kendaraan_add', $data);
    }

    public function kendaraan_store()
    {
        $jenis_kendaraan = $this->request->getPost('jenis_kendaraan');
        $nomor_polisi = $this->request->getPost('nomor_polisi');
        $nomor_mesin = $this->request->getPost('nomor_mesin');

        $validasi = [
            'jenis_kendaraan' => $jenis_kendaraan,
            'nomor_polisi' => $nomor_polisi,
            'nomor_mesin' => $nomor_mesin,
        ];

        if ($this->validation->run($validasi, 'insertKendaraan') == false) {
            $data = [
                'title' => 'Manajemen Proyek',
                'validation' => $this->validation->getErrors(),
            ];
            return view('admin/kendaraan/kendaraan_add', $data);
        } else {
            try {
                $insert = [
                    'jenis_kendaraan' => $jenis_kendaraan,
                    'nomor_polisi' => $nomor_polisi,
                    'nomor_mesin' => $nomor_mesin,
                ];
                $save = $this->kendaraan->save($insert);
                if ($save) {
                    session()->setFlashdata('success', 'Tambah data kendaraan berhasil');
                    return redirect()->to(base_url('dashboard/kendaraan'));
                }
            } catch (\Throwable $th) {
                session()->setFlashdata('errors', 'Tambah data kendaraan gagal, nomor polisi telah ada');
                return redirect()->to(base_url('dashboard/kendaraan'));
                //throw $th;
            }
        }
    }

    public function kendaraan_edit($id)
    {
        $data = [
            'title' => 'Manajemen Proyek',
            'data' => $this->kendaraan->find(dekrip($id)),
            'id' => $id,
        ];
        return view('admin/kendaraan/kendaraan_edit', $data);

    }

    public function kendaraan_update()
    {
        $dekrip = $this->request->getPost('id');
        $jenis_kendaraan = $this->request->getPost('jenis_kendaraan');
        $nomor_polisi = $this->request->getPost('nomor_polisi');
        $nomor_mesin = $this->request->getPost('nomor_mesin');
        $id = dekrip($dekrip);

        $validasi = [
            'jenis_kendaraan' => $jenis_kendaraan,
            'nomor_polisi' => $nomor_polisi,
            'nomor_mesin' => $nomor_mesin,
        ];

        if ($this->validation->run($validasi, 'insertKendaraan') == false) {
            $data = [
                'title' => 'Manajemen Proyek',
                'validation' => $this->validation->getErrors(),
                'data' => $this->kendaraan->find($id),
                'id' => $dekrip,
            ];
            return view('admin/kendaraan/kendaraan_edit', $data);
        } else {
            $update = [
                'jenis_kendaraan' => $jenis_kendaraan,
                'nomor_polisi' => $nomor_polisi,
                'nomor_mesin' => $nomor_mesin,
            ];
            $update_kendaraan = $this->kendaraan->where('id', $id)->set($update)->update();
            if ($update_kendaraan) {
                session()->setFlashdata('success', 'Update data kendaraan berhasil');
                return redirect()->to(base_url('dashboard/kendaraan'));
            } else {
                session()->setFlashdata('errors', 'Update data kendaraan gagal');
                return redirect()->to(base_url('dashboard/kendaraan'));
            }
        }
    }

    public function kendaraan_delete()
    {
        $input = $this->request->getRawInput();
        $id_en = $input['id'];
        $id = dekrip($id_en);
        $delete = $this->kendaraan->delete($id);
        if ($delete) {
            $response = [
                'status' => 201,
                'message' => 'Data kendaraan berhasil dihapus',
            ];
        } else {
            $response = [
                'status' => 500,
                'message' => 'Data kendaraan gagal dihapus',
            ];
        }

        return $this->response->setJSON($response);
    }

    public function proyek_list()
    {
        $request = Services::request();
        $m_proyek = new Proyek_Model($request);
        if ($request->getMethod(true) == 'POST') {
            $lists = $m_proyek->get_datatables();
            $data = [];
            $no = $request->getPost("start");
            foreach ($lists as $list) {
                $no++;
                $row = [];
                $row[] = $no;
                $row[] = $list->nama_proyek;
                $row[] = $list->lokasi;
                $row[] = $this->decode->selisih_tanggal($list->tgl_mulai, $list->tgl_selesai);
                $row[] = date('d-m-Y', strtotime($list->tgl_mulai));
                $row[] = date('d-m-Y', strtotime($list->tgl_selesai));
                $row[] = $list->konsultan_pengawas;
                $row[] = $list->nama;
                // $row[] = $list->progress_proyek;
                $row[] = rupiah($list->terapan_anggaran);
                $row[] = rupiah($list->nilai_kontrak);
                $row[] = '<a href="' . base_url('dashboard/kelola_kegiatan/' . enkrip($list->id)) . '" class="btn btn-primary btn-sm"><i class="fa fa-file"></i></a> &nbsp; <a href="' . base_url('dashboard/kelola_kegiatan_add/' . enkrip($list->id)) . '" class="btn btn-success btn-sm"><i class="fa fa-plus"></i></a>';
                $row[] = '<a href="' . base_url('dashboard/proyek_edit/' . enkrip($list->id)) . '" class="text-secondary"><i class="fa fa-pencil-alt"></i></a> &nbsp; <a href="#" onClick="return deleteProyek(' . $list->id . ')" class="text-secondary"><i class="fa fa-trash"></i></a>';
                $data[] = $row;
            }
            $output = ["draw" => $request->getPost('draw'),
                "recordsTotal" => $m_proyek->count_all(),
                "recordsFiltered" => $m_proyek->count_filtered(),
                "data" => $data];
            echo json_encode($output);
        }
    }

    public function proyek()
    {
        $data = [
            'title' => 'Manajemen Proyek',
        ];
        return view('admin/proyek', $data);
    }

    public function proyek_lap_list()
    {
        $request = Services::request();
        $m_proyek = new Proyek_Model($request);
        $m_dokumentasi = new Dokumentasi_model();
        if ($request->getMethod(true) == 'POST') {
            $lists = $m_proyek->get_datatables();
            $data = [];
            $no = $request->getPost("start");
            foreach ($lists as $list) {
                $progress = 
                $no++;
                $row = [];
                $row[] = $no;
                $row[] = $list->nama_proyek;
                $row[] = $list->lokasi;
                $row[] = $this->decode->selisih_tanggal($list->tgl_mulai, $list->tgl_selesai);
                $row[] = date('d-m-Y', strtotime($list->tgl_mulai));
                $row[] = date('d-m-Y', strtotime($list->tgl_selesai));
                $row[] = $list->konsultan_pengawas;
                $row[] = $list->nama;
                $row[] = $m_dokumentasi->getProgress($list->id)."%";
                $row[] = rupiah($list->nilai_kontrak);
                $data[] = $row;
            }
            $output = ["draw" => $request->getPost('draw'),
                "recordsTotal" => $m_proyek->count_all(),
                "recordsFiltered" => $m_proyek->count_filtered(),
                "data" => $data];
            echo json_encode($output);
        }
    }

    public function proyek_lap()
    {
        $data = [
            'title' => 'Manajemen Proyek',
        ];
        return view('direktur/proyek', $data);
    }

    public function proyek_add()
    {
        $data = [
            'title' => 'Manajemen Proyek',
            'decode' => $this->decode,
            'pegawai' => $this->pegawai->findAll()
        ];
        return view('admin/proyek/proyek_add', $data);
    }

    public function proyek_store()
    {
        $nama_proyek = $this->request->getPost('nama_proyek');
        $lokasi = $this->request->getPost('lokasi');
        $tgl_mulai = $this->request->getPost('tgl_mulai');
        $tgl_selesai = $this->request->getPost('tgl_selesai');
        $konsultan_pengawas = $this->request->getPost('konsultan_pengawas');
        $id_pegawai = $this->request->getPost('id_pegawai');
        $nilai_kontrak = $this->request->getPost('nilai_kontrak');

        $validasi = [
            'nama_proyek' => $nama_proyek,
            'lokasi' => $lokasi,
            'tgl_mulai' => $tgl_mulai,
            'tgl_selesai' => $tgl_selesai,
            'konsultan_pengawas' => $konsultan_pengawas,
            'id_pegawai' => $id_pegawai,
            'nilai_kontrak' => $nilai_kontrak,
        ];

        if ($this->validation->run($validasi, 'insertProyek') == false) {
            $data = [
                'title' => 'Manajemen Proyek',
                'validation' => $this->validation->getErrors(),
            ];
            return view('admin/proyek/proyek_add', $data);
        } else {
            $insert = [
                'nama_proyek' => $nama_proyek,
                'lokasi' => $lokasi,
                'tgl_mulai' => $tgl_mulai,
                'tgl_selesai' => $tgl_selesai,
                'konsultan_pengawas' => $konsultan_pengawas,
                'id_pegawai' => $id_pegawai,
                'nilai_kontrak' => $nilai_kontrak,
            ];
            $save = $this->proyek->save($insert);
            if ($save) {
                session()->setFlashdata('success', 'Tambah data proyek berhasil');
                return redirect()->to(base_url('dashboard/proyek'));
            } else {
                session()->setFlashdata('errors', 'Tambah data proyek gagal');
                return redirect()->to(base_url('dashboard/proyek'));
            }
        }
    }

    public function proyek_edit($id)
    {
        $data = [
            'title' => 'Manajemen Proyek',
            'data' => $this->proyek->find(dekrip($id)),
            'id' => $id,
            'pegawai' => $this->pegawai->findAll()
        ];
        $data['item']= $this->pegawai->find($data['data']['id_pegawai']);
        return view('admin/proyek/proyek_edit', $data);

    }

    public function proyek_update()
    {
        $dekrip = $this->request->getPost('id');
        $nama_proyek = $this->request->getPost('nama_proyek');
        $lokasi = $this->request->getPost('lokasi');
        $tgl_mulai = $this->request->getPost('tgl_mulai');
        $tgl_selesai = $this->request->getPost('tgl_selesai');
        $konsultan_pengawas = $this->request->getPost('konsultan_pengawas');
        $id_pegawai = $this->request->getPost('id_pegawai');
        $nilai_kontrak = $this->request->getPost('nilai_kontrak');
        $id = dekrip($dekrip);

        $validasi = [
            'nama_proyek' => $nama_proyek,
            'lokasi' => $lokasi,
            'tgl_mulai' => $tgl_mulai,
            'tgl_selesai' => $tgl_selesai,
            'konsultan_pengawas' => $konsultan_pengawas,
            'id_pegawai' => $id_pegawai,
            'nilai_kontrak' => $nilai_kontrak,
        ];

        if ($this->validation->run($validasi, 'insertProyek') == false) {
            $data = [
                'title' => 'Manajemen Proyek',
                'validation' => $this->validation->getErrors(),
                'data' => $this->proyek->find($id),
                'id' => $dekrip,
            ];
            return view('admin/proyek/proyek_edit', $data);
        } else {
            $update = [
                'nama_proyek' => $nama_proyek,
                'lokasi' => $lokasi,
                'tgl_mulai' => $tgl_mulai,
                'tgl_selesai' => $tgl_selesai,
                'konsultan_pengawas' => $konsultan_pengawas,
                'id_pegawai' => $id_pegawai,
                'nilai_kontrak' => $nilai_kontrak,
            ];
            $update_proyek = $this->proyek->where('id', $id)->set($update)->update();
            if ($update_proyek) {
                session()->setFlashdata('success', 'Update data proyek berhasil');
                return redirect()->to(base_url('dashboard/proyek'));
            } else {
                session()->setFlashdata('errors', 'Update data proyek gagal');
                return redirect()->to(base_url('dashboard/proyek'));
            }
        }
    }

    public function proyek_delete()
    {
        $input = $this->request->getRawInput();
        $id_en = $input['id'];
        $id = dekrip($id_en);
        $delete = $this->proyek->delete($id);
        if ($delete) {
            $response = [
                'status' => 201,
                'message' => 'Data proyek berhasil dihapus',
            ];
        } else {
            $response = [
                'status' => 500,
                'message' => 'Data proyek gagal dihapus',
            ];
        }

        return $this->response->setJSON($response);
    }
    public function kelola_kegiatan_list($id)
    {
        $request = Services::request();
        $m_kelolah_kegiatan = new Kelola_Kegiatan_Model($request);
        if ($request->getMethod(true) == 'POST') {
            $lists = $m_kelolah_kegiatan->get_datatables($id);
            $data = [];
            $no = $request->getPost("start");
            foreach ($lists as $list) {
                $no++;
                $row = [];
                $row[] = $no;
                $row[] = $list->nama_kegiatan;
                $row[] = date('d-m-Y', strtotime($list->tgl_mulai));
                $row[] = date('d-m-Y', strtotime($list->tgl_selesai));
                $row[] = $list->progress;
                $row[] = '<a href="' . base_url('dashboard/kelola_kegiatan_edit/' . enkrip($list->id)) . '" class="text-secondary"><i class="fa fa-pencil-alt"></i></a> &nbsp; <a href="#" onClick="return deleteKelolaKegiatan(' . $list->id . ')" class="text-secondary"><i class="fa fa-trash"></i></a>';
                $data[] = $row;
            }
            $output = ["draw" => $request->getPost('draw'),
                "recordsTotal" => $m_kelolah_kegiatan->count_all($id),
                "recordsFiltered" => $m_kelolah_kegiatan->count_filtered($id),
                "data" => $data];
            echo json_encode($output);
        }
    }

    public function kelola_kegiatan($id)
    {
        $data = [
            'title' => 'Kelolah Kegiatan Proyek',
            'id_proyek' => dekrip($id),
        ];
        return view('admin/kelola_kegiatan', $data);
    }

    public function kelola_kegiatan_lap_list()
    {
        $request = Services::request();
        $m_kelolah_kegiatan = new Proyek_Model($request);
        if ($request->getMethod(true) == 'POST') {
            $lists = $m_kelolah_kegiatan->get_datatables();
            $data = [];
            $no = $request->getPost("start");
            foreach ($lists as $list) {
                $no++;
                $row = [];
                $row[] = $no;
                $row[] = $list->nama_proyek;
                $row[] = $list->lokasi;
                $row[] = $list->jangka_waktu;
                $row[] = date('d-m-Y', strtotime($list->tgl_mulai));
                $row[] = date('d-m-Y', strtotime($list->tgl_selesai));
                $row[] = $list->konsultan_pengawas;
                $row[] = $list->kontraktor_pelaksana;
                $row[] = rupiah($list->nilai_kontrak);
                $data[] = $row;
            }
            $output = ["draw" => $request->getPost('draw'),
                "recordsTotal" => $m_kelolah_kegiatan->count_all(),
                "recordsFiltered" => $m_kelolah_kegiatan->count_filtered(),
                "data" => $data];
            echo json_encode($output);
        }
    }

    public function kelola_kegiatan_lap()
    {
        $data = [
            'title' => 'Manajemen Proyek',
        ];
        return view('direktur/proyek', $data);
    }

    public function kelola_kegiatan_add($id)
    {
        $data = [
            'title' => 'Manajemen Proyek',
            'id_proyek' => dekrip($id),
        ];
        return view('admin/kelola_kegiatan/kegiatan_add', $data);
    }

    public function kelola_kegiatan_store()
    {
        $id_proyek = $this->request->getPost('id_proyek');
        $nama_kegiatan = $this->request->getPost('nama_kegiatan');
        $tgl_mulai = $this->request->getPost('tgl_mulai');
        $tgl_selesai = $this->request->getPost('tgl_selesai');
        $progress = $this->request->getPost('progress');

        $validasi = [
            'id_proyek' => $id_proyek,
            'tgl_mulai' => $tgl_mulai,
            'tgl_selesai' => $tgl_selesai,
        ];

        if ($this->validation->run($validasi, 'insertKelolaKegiatan') == false) {
            $data = [
                'title' => 'Manajemen Proyek',
                'validation' => $this->validation->getErrors(),
                'id_proyek' => $id_proyek,
            ];
            return view('admin/kelola_kegiatan/kegiatan_add', $data);
        } else {
            $insert = [
                'id_proyek' => $id_proyek,
                'nama_kegiatan' => $nama_kegiatan,
                'tgl_mulai' => $tgl_mulai,
                'tgl_selesai' => $tgl_selesai,
                'progress' => $progress,
            ];
            $save = $this->kelola_kegiatan->save($insert);
            if ($save) {
                session()->setFlashdata('success', 'Tambah data kelolah kegiatan proyek berhasil');
                return redirect()->to(base_url('dashboard/kelola_kegiatan/' . enkrip($id_proyek)));
            } else {
                session()->setFlashdata('errors', 'Tambah data kelola Proyek gagal');
                return redirect()->to(base_url('dashboard/kelola_kegiatan/' . enkrip($id_proyek)));
            }
        }
    }

    public function kelola_kegiatan_edit($id)
    {
        $data = [
            'title' => 'Manajemen Proyek',
            'data' => $this->kelola_kegiatan->find(dekrip($id)),
            'id' => $id,
        ];
        return view('admin/kelola_kegiatan/kegiatan_edit', $data);

    }

    public function kelola_kegiatan_update()
    {
        $dekrip = $this->request->getPost('id');
        $nama_kegiatan = $this->request->getPost('nama_kegiatan');
        $tgl_mulai = $this->request->getPost('tgl_mulai');
        $tgl_selesai = $this->request->getPost('tgl_selesai');
        $progress = $this->request->getPost('progress');
        $id_proyek = $this->request->getPost('id_proyek');
        $id = dekrip($dekrip);

        $update = [
            'nama_kegiatan' => $nama_kegiatan,
            'tgl_mulai' => $tgl_mulai,
            'tgl_selesai' => $tgl_selesai,
            'progress' => $progress,
        ];
        $update_proyek = $this->kelola_kegiatan->where('id', $id)->set($update)->update();
        if ($update_proyek) {
            session()->setFlashdata('success', 'Update data kelola Kegiatan berhasil');
            return redirect()->to(base_url('dashboard/kelola_kegiatan/' . enkrip($id_proyek)));
        } else {
            session()->setFlashdata('errors', 'Update data kelolah kegiatan gagal');
            return redirect()->to(base_url('dashboard/kelola_kegiatan/' . enkrip($id_proyek)));
        }
    }

    public function kelola_kegiatan_delete()
    {
        $input = $this->request->getRawInput();
        $id_en = $input['id'];
        $id = dekrip($id_en);
        $delete = $this->kelola_kegiatan->delete($id);
        if ($delete) {
            $response = [
                'status' => 201,
                'message' => 'Data kegiatan proyek berhasil dihapus',
            ];
        } else {
            $response = [
                'status' => 500,
                'message' => 'Data kegiatan proyek gagal dihapus',
            ];
        }

        return $this->response->setJSON($response);
    }

    public function kegiatan_list()
    {
        $request = Services::request();
        $m_kegiatan = new Kegiatan_Model($request);
        if ($request->getMethod(true) == 'POST') {
            $lists = $m_kegiatan->get_datatables();
            $data = [];
            $no = $request->getPost("start");
            foreach ($lists as $list) {
                $no++;
                $row = [];
                $row[] = $no;
                $row[] = $list->nama_proyek;
                $row[] = date('d-m-Y', strtotime($list->tgl_mulai));
                $row[] = date('d-m-Y', strtotime($list->tgl_selesai));
                $row[] = '<a href="' . base_url('dashboard/kegiatan_edit/' . enkrip($list->id)) . '" class="text-secondary"><i class="fa fa-pencil-alt"></i></a> &nbsp; <a href="#" onClick="return deleteKegiatan(' . $list->id . ')" class="text-secondary"><i class="fa fa-trash"></i></a>';
                $data[] = $row;
            }
            $output = ["draw" => $request->getPost('draw'),
                "recordsTotal" => $m_kegiatan->count_all(),
                "recordsFiltered" => $m_kegiatan->count_filtered(),
                "data" => $data];
            echo json_encode($output);
        }
    }

    public function kegiatan()
    {
        $data = [
            'title' => 'Manajemen Proyek',
        ];
        return view('admin/kegiatan', $data);
    }

    public function kegiatan_add()
    {
        $data = [
            'title' => 'Manajemen Proyek',
            'proyek' => $this->proyek->findAll(),
        ];
        return view('admin/kegiatan/kegiatan_add', $data);
    }

    public function kegiatan_store()
    {
        $id_proyek = $this->request->getPost('id_proyek');
        $tgl_mulai = $this->request->getPost('tgl_mulai');
        $tgl_selesai = $this->request->getPost('tgl_selesai');

        $validasi = [
            'id_proyek' => $id_proyek,
            'tgl_mulai' => $tgl_mulai,
            'tgl_selesai' => $tgl_selesai,
        ];

        if ($this->validation->run($validasi, 'insertKelolaKegiatan') == false) {
            $data = [
                'title' => 'Manajemen Proyek',
                'validation' => $this->validation->getErrors(),
                'proyek' => $this->proyek->findAll(),
            ];
            return view('admin/kegiatan/kegiatan_add', $data);
        } else {
            $insert = [
                'id_proyek' => $id_proyek,
                'tgl_mulai' => $tgl_mulai,
                'tgl_selesai' => $tgl_selesai,
            ];
            $save = $this->kegiatan->save($insert);
            if ($save) {
                session()->setFlashdata('success', 'Tambah data kegiatan berhasil');
                return redirect()->to(base_url('dashboard/kegiatan'));
            } else {
                session()->setFlashdata('errors', 'Tambah data kegiatan gagal');
                return redirect()->to(base_url('dashboard/kegiatan'));
            }
        }
    }

    public function kegiatan_edit($id)
    {
        $data = [
            'title' => 'Manajemen Proyek',
            'data' => $this->kegiatan->find(dekrip($id)),
            'proyek' => $this->proyek->findAll(),
            'id' => $id,
        ];
        return view('admin/kegiatan/kegiatan_edit', $data);

    }

    public function kegiatan_update()
    {
        $dekrip = $this->request->getPost('id');
        $id_proyek = $this->request->getPost('id_proyek');
        $tgl_mulai = $this->request->getPost('tgl_mulai');
        $tgl_selesai = $this->request->getPost('tgl_selesai');
        $id = dekrip($dekrip);

        $validasi = [
            'id_proyek' => $id_proyek,
            'tgl_mulai' => $tgl_mulai,
            'tgl_selesai' => $tgl_selesai,
        ];

        if ($this->validation->run($validasi, 'insertKegiatan') == false) {
            $data = [
                'title' => 'Manajemen Proyek',
                'validation' => $this->validation->getErrors(),
                'data' => $this->kegiatan->find($id),
                'proyek' => $this->proyek->findAll(),
                'id' => $dekrip,
            ];
            return view('admin/kegiatan/kegiatan_edit', $data);
        } else {
            $update = [
                'id_proyek' => $id_proyek,
                'tgl_mulai' => $tgl_mulai,
                'tgl_selesai' => $tgl_selesai,
            ];
            $update_kegiatan = $this->kegiatan->where('id', $id)->set($update)->update();
            if ($update_kegiatan) {
                session()->setFlashdata('success', 'Update data kegiatan berhasil');
                return redirect()->to(base_url('dashboard/kegiatan'));
            } else {
                session()->setFlashdata('errors', 'Update data kegiatan gagal');
                return redirect()->to(base_url('dashboard/kegiatan'));
            }
        }
    }

    public function kegiatan_delete()
    {
        $input = $this->request->getRawInput();
        $id_en = $input['id'];
        $id = dekrip($id_en);
        $delete = $this->kegiatan->delete($id);
        if ($delete) {
            $response = [
                'status' => 201,
                'message' => 'Data kegiatan berhasil dihapus',
            ];
        } else {
            $response = [
                'status' => 500,
                'message' => 'Data kegiatan gagal dihapus',
            ];
        }

        return $this->response->setJSON($response);
    }

    public function users_list()
    {
        $request = Services::request();
        $m_users = new Users_Model($request);
        if ($request->getMethod(true) == 'POST') {
            $lists = $m_users->get_datatables();
            $data = [];
            $no = $request->getPost("start");
            foreach ($lists as $list) {
                $no++;
                $row = [];
                $row[] = $no;
                $row[] = $list->nama;
                $row[] = ucwords($list->hak_akses);
                $row[] = $list->username;
                $row[] = '<a href="' . base_url('dashboard/users_edit/' . enkrip($list->id)) . '" class="text-secondary"><i class="fa fa-pencil-alt"></i></a> &nbsp; <a href="#" onClick="return deleteUsers(' . $list->id . ')" class="text-secondary"><i class="fa fa-trash"></i></a>';
                $data[] = $row;
            }
            $output = ["draw" => $request->getPost('draw'),
                "recordsTotal" => $m_users->count_all(),
                "recordsFiltered" => $m_users->count_filtered(),
                "data" => $data];
            echo json_encode($output);
        }
    }

    public function users()
    {
        $data = [
            'title' => 'Manajemen Proyek',
        ];
        return view('admin/users', $data);
    }

    public function users_add()
    {
        $data = [
            'title' => 'Manajemen Proyek',
            'proyek' => $this->proyek->findAll(),
            'pegawai' => $this->pegawai->findAll()
        ];
        return view('admin/users/users_add', $data);
    }

    public function users_store()
    {
        $id_pegawai = $this->request->getPost('id_pegawai');
        $hak_akses = $this->request->getPost('hak_akses');
        $id_proyek = $this->request->getPost('id_proyek');
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        $confirm_password = $this->request->getPost('confirm_password');

        $validasi = [
            'id_pegawai' => $id_pegawai,
            'hak_akses' => $hak_akses,
            'username' => $username,
            'password' => $password,
            'confirm_password' => $confirm_password,
        ];

        if ($this->validation->run($validasi, 'insertUsers') == false) {
            $data = [
                'title' => 'Manajemen Proyek',
                'validation' => $this->validation->getErrors(),
                'proyek' => $this->proyek->findAll(),
                'pegawai' => $this->pegawai->findAll()
            ];
            return view('admin/users/users_add', $data);
        } else {
            $insert = [
                'id_pegawai' => $id_pegawai,
                'hak_akses' => $hak_akses,
                'id_proyek' => $id_proyek,
                'username' => $username,
                'password' => password_hash($password, PASSWORD_DEFAULT),
            ];
            $save = $this->users->save($insert);
            if ($save) {
                session()->setFlashdata('success', 'Tambah data pengguna berhasil');
                return redirect()->to(base_url('dashboard/users'));
            } else {
                session()->setFlashdata('errors', 'Tambah data pengguna gagal');
                return redirect()->to(base_url('dashboard/users'));
            }
        }
    }

    public function users_edit($id)
    {
        $data = [
            'title' => 'Manajemen Proyek',
            'data' => $this->users->getUser(dekrip($id)),
            'proyek' => $this->proyek->findAll(),
            'id' => $id,
            'pegawai' => $this->pegawai->findAll(),
        ];
        return view('admin/users/users_edit', $data);

    }

    public function users_update()
    {
        $dekrip = $this->request->getPost('id');
        $id_pegawai = $this->request->getPost('id_pegawai');
        $hak_akses = $this->request->getPost('hak_akses');
        $id_proyek = $this->request->getPost('id_proyek');
        $username = $this->request->getPost('username');
        $username_old = $this->request->getPost('username_old');
        $id = dekrip($dekrip);

        $validasi = [
            'id_pegawai' => $id_pegawai,
            'hak_akses' => $hak_akses,
            'username' => $username,
        ];

        if ($this->validation->run($validasi, 'updateUsers') == false) {
            $data = [
                'title' => 'Manajemen Proyek',
                'validation' => $this->validation->getErrors(),
                'data' => $this->users->find($id),
                'proyek' => $this->proyek->findAll(),
                'id' => $dekrip,
            ];
            return view('admin/users/users_edit', $data);
        } else {
            if ($username != $username_old && $this->users->where('username', $username)->countAllResults() > 0) {
                $data = [
                    'title' => 'Manajemen Proyek',
                    'validation' => ['username' => 'Username sudah terdaftar di database'],
                    'data' => $this->users->find($id),
                    'proyek' => $this->proyek->findAll(),
                    'id' => $dekrip,
                ];
                return view('admin/users/users_edit', $data);
            } else {
                $update = [
                    'id_pegawai' => $id_pegawai,
                    'hak_akses' => $hak_akses,
                    'id_proyek' => $id_proyek,
                    'username' => $username,
                ];
                $update_users = $this->users->where('id', $id)->set($update)->update();
                if ($update_users) {
                    session()->setFlashdata('success', 'Update data pengguna berhasil');
                    return redirect()->to(base_url('dashboard/users'));
                } else {
                    session()->setFlashdata('errors', 'Update data pengguna gagal');
                    return redirect()->to(base_url('dashboard/users'));
                }
            }
        }
    }

    public function users_delete()
    {
        $input = $this->request->getRawInput();
        $id_en = $input['id'];
        $id = dekrip($id_en);
        if ($id == session()->get('uid')) {
            $response = [
                'status' => 500,
                'message' => 'Anda tidak bisa menghapus data sendiri',
            ];
        } else {
            $delete = $this->users->delete($id);
            if ($delete) {
                $response = [
                    'status' => 201,
                    'message' => 'Data pengguna berhasil dihapus',
                ];
            } else {
                $response = [
                    'status' => 500,
                    'message' => 'Data pengguna gagal dihapus',
                ];
            }
        }

        return $this->response->setJSON($response);
    }

    public function operasional_list()
    {
        $request = Services::request();
        $m_operasional = new Operasional_Model($request);
        if ($request->getMethod(true) == 'POST') {
            $lists = $m_operasional->get_datatables();
            $data = [];
            $no = $request->getPost("start");
            foreach ($lists as $list) {
                $no++;
                $row = [];
                $row[] = $no;
                $row[] = $list->nama_proyek;
                $row[] = date('d-m-Y', strtotime($list->tgl_kegiatan));
                $row[] = $list->keterangan;
                $row[] = rupiah($list->jumlah);
                $row[] = '<a href="' . base_url('dashboard/operasional_edit/' . enkrip($list->id)) . '" class="text-secondary"><i class="fa fa-pencil-alt"></i></a> &nbsp; <a href="#" onClick="return deleteOperasional(' . $list->id . ')" class="text-secondary"><i class="fa fa-trash"></i></a>';
                $data[] = $row;
            }
            $output = ["draw" => $request->getPost('draw'),
                "recordsTotal" => $m_operasional->count_all(),
                "recordsFiltered" => $m_operasional->count_filtered(),
                "data" => $data];
            echo json_encode($output);
        }
    }

    public function operasional()
    {
        $data = [
            'title' => 'Manajemen Proyek',
        ];
        return view('bendahara/operasional', $data);
    }

    public function operasional_lap_list()
    {
        $request = Services::request();
        $m_operasional = new Operasional_Model($request);
        if ($request->getMethod(true) == 'POST') {
            $lists = $m_operasional->get_datatables();
            $data = [];
            $no = $request->getPost("start");
            foreach ($lists as $list) {
                $no++;
                $row = [];
                $row[] = $no;
                $row[] = $list->nama_proyek;
                $row[] = date('d-m-Y', strtotime($list->tgl_kegiatan));
                $row[] = $list->keterangan;
                $row[] = $list->jenis_transaksi == 'kredit' ? rupiah($list->jumlah) : '';
                $row[] = $list->jenis_transaksi == 'debit' ? rupiah($list->jumlah) : '';
                $data[] = $row;
            }
            $output = ["draw" => $request->getPost('draw'),
                "recordsTotal" => $m_operasional->count_all(),
                "recordsFiltered" => $m_operasional->count_filtered(),
                "data" => $data];
            echo json_encode($output);
        }
    }

    public function operasional_lap()
    {
        $data = [
            'title' => 'Manajemen Proyek',
        ];
        return view('direktur/operasional', $data);
    }

    public function operasional_add()
    {
        $data = [
            'title' => 'Manajemen Proyek',
            'proyek' => $this->proyek->findAll(),
        ];
        return view('bendahara/operasional/operasional_add', $data);
    }

    public function operasional_store()
    {
        $id_proyek = $this->request->getPost('id_proyek');
        $tgl_kegiatan = $this->request->getPost('tgl_kegiatan');
        $keterangan = $this->request->getPost('keterangan');
        // $jenis_transaksi = $this->request->getPost('jenis_transaksi');
        $jumlah = $this->request->getPost('jumlah');

        $validasi = [
            'id_proyek' => $id_proyek,
            'tgl_kegiatan' => $tgl_kegiatan,
            'keterangan' => $keterangan,
            // 'jenis_transaksi' => $jenis_transaksi,
            'jumlah' => $jumlah,
        ];

        if ($this->validation->run($validasi, 'insertOperasional') == false) {
            $data = [
                'title' => 'Manajemen Proyek',
                'validation' => $this->validation->getErrors(),
                'proyek' => $this->proyek->findAll(),
            ];
            return view('bendahara/operasional/operasional_add', $data);
        } else {
            $insert = [
                'id_proyek' => $id_proyek,
                'tgl_kegiatan' => $tgl_kegiatan,
                'keterangan' => $keterangan,
                // 'jenis_transaksi' => $jenis_transaksi,
                'jumlah' => $jumlah,
            ];
            $save = $this->operasional->save($insert);
            if ($save) {
                session()->setFlashdata('success', 'Tambah dana operasional berhasil');
                return redirect()->to(base_url('dashboard/operasional'));
            } else {
                session()->setFlashdata('errors', 'Tambah dana operasional gagal');
                return redirect()->to(base_url('dashboard/operasional'));
            }
        }
    }

    public function operasional_edit($id)
    {
        $data = [
            'title' => 'Manajemen Proyek',
            'data' => $this->operasional->find(dekrip($id)),
            'proyek' => $this->proyek->findAll(),
            'id' => $id,
        ];
        return view('bendahara/operasional/operasional_edit', $data);

    }

    public function operasional_update()
    {
        $dekrip = $this->request->getPost('id');
        $id_proyek = $this->request->getPost('id_proyek');
        $tgl_kegiatan = $this->request->getPost('tgl_kegiatan');
        $keterangan = $this->request->getPost('keterangan');
        // $jenis_transaksi = $this->request->getPost('jenis_transaksi');
        $jumlah = $this->request->getPost('jumlah');
        $id = dekrip($dekrip);

        $validasi = [
            'id_proyek' => $id_proyek,
            'tgl_kegiatan' => $tgl_kegiatan,
            'keterangan' => $keterangan,
            // 'jenis_transaksi' => $jenis_transaksi,
            'jumlah' => $jumlah,
        ];

        if ($this->validation->run($validasi, 'insertOperasional') == false) {
            $data = [
                'title' => 'Manajemen Proyek',
                'validation' => $this->validation->getErrors(),
                'data' => $this->operasional->find($id),
                'proyek' => $this->proyek->findAll(),
                'id' => $dekrip,
            ];
            return view('bendahara/operasional/operasional_edit', $data);
        } else {
            $update = [
                'id_proyek' => $id_proyek,
                'tgl_kegiatan' => $tgl_kegiatan,
                'keterangan' => $keterangan,
                // 'jenis_transaksi' => $jenis_transaksi,
                'jumlah' => $jumlah,
            ];
            $update_operasional = $this->operasional->where('id', $id)->set($update)->update();
            if ($update_operasional) {
                session()->setFlashdata('success', 'Update dana operasional berhasil');
                return redirect()->to(base_url('dashboard/operasional'));
            } else {
                session()->setFlashdata('errors', 'Update dana operasional gagal');
                return redirect()->to(base_url('dashboard/operasional'));
            }
        }
    }

    public function operasional_delete()
    {
        $input = $this->request->getRawInput();
        $id_en = $input['id'];
        $id = dekrip($id_en);
        $delete = $this->operasional->delete($id);
        if ($delete) {
            $response = [
                'status' => 201,
                'message' => 'Data dana operasional berhasil dihapus',
            ];
        } else {
            $response = [
                'status' => 500,
                'message' => 'Data dana operasional gagal dihapus',
            ];
        }

        return $this->response->setJSON($response);
    }

    public function transaksi_list()
    {
        $request = Services::request();
        $m_transaksi = new Transaksi_Model($request);
        if ($request->getMethod(true) == 'POST') {
            $lists = $m_transaksi->get_datatables();
            $data = [];
            $no = $request->getPost("start");
            foreach ($lists as $list) {
                $no++;
                $row = [];
                $row[] = $no;
                $row[] = date('d-m-Y', strtotime($list->tgl_transaksi));
                $row[] = $list->keterangan;
                $row[] = rupiah($list->jumlah);
                $row[] = '<a href="' . base_url('dashboard/transaksi_edit/' . enkrip($list->id)) . '" class="text-secondary"><i class="fa fa-pencil-alt"></i></a> &nbsp; <a href="#" onClick="return deleteTransaksi(' . $list->id . ')" class="text-secondary"><i class="fa fa-trash"></i></a>';
                $data[] = $row;
            }
            $output = ["draw" => $request->getPost('draw'),
                "recordsTotal" => $m_transaksi->count_all(),
                "recordsFiltered" => $m_transaksi->count_filtered(),
                "data" => $data];
            echo json_encode($output);
        }
    }

    public function transaksi()
    {
        $data = [
            'title' => 'Manajemen Proyek',
        ];
        return view('bendahara/transaksi', $data);
    }

    public function transaksi_add()
    {
        $data = [
            'title' => 'Manajemen Proyek',
        ];
        return view('bendahara/transaksi/transaksi_add', $data);
    }

    public function transaksi_store()
    {
        $tgl_transaksi = $this->request->getPost('tgl_transaksi');
        $keterangan = $this->request->getPost('keterangan');
        $jumlah = $this->request->getPost('jumlah');

        $validasi = [
            'tgl_transaksi' => $tgl_transaksi,
            'keterangan' => $keterangan,
            'jumlah' => $jumlah,
        ];

        if ($this->validation->run($validasi, 'insertTransaksi') == false) {
            $data = [
                'title' => 'Manajemen Proyek',
                'validation' => $this->validation->getErrors(),
            ];
            return view('bendahara/transaksi/transaksi_add', $data);
        } else {
            $insert = [
                'tgl_transaksi' => $tgl_transaksi,
                'keterangan' => $keterangan,
                'jumlah' => $jumlah,
            ];
            $save = $this->transaksi->save($insert);
            if ($save) {
                session()->setFlashdata('success', 'Tambah transaksi berhasil');
                return redirect()->to(base_url('dashboard/transaksi'));
            } else {
                session()->setFlashdata('errors', 'Tambah transaksi gagal');
                return redirect()->to(base_url('dashboard/transaksi'));
            }
        }
    }

    public function transaksi_edit($id)
    {
        $data = [
            'title' => 'Manajemen Proyek',
            'data' => $this->transaksi->find(dekrip($id)),
            'id' => $id,
        ];
        return view('bendahara/transaksi/transaksi_edit', $data);

    }

    public function transaksi_update()
    {
        $dekrip = $this->request->getPost('id');
        $tgl_transaksi = $this->request->getPost('tgl_transaksi');
        $keterangan = $this->request->getPost('keterangan');
        $jumlah = $this->request->getPost('jumlah');
        $id = dekrip($dekrip);

        $validasi = [
            'tgl_transaksi' => $tgl_transaksi,
            'keterangan' => $keterangan,
            'jumlah' => $jumlah,
        ];

        if ($this->validation->run($validasi, 'insertTransaksi') == false) {
            $data = [
                'title' => 'Manajemen Proyek',
                'validation' => $this->validation->getErrors(),
                'data' => $this->transaksi->find($id),
                'id' => $dekrip,
            ];
            return view('bendahara/transaksi/transaksi_edit', $data);
        } else {
            $update = [
                'tgl_transaksi' => $tgl_transaksi,
                'keterangan' => $keterangan,
                'jumlah' => $jumlah,
            ];
            $update_transaksi = $this->transaksi->where('id', $id)->set($update)->update();
            if ($update_transaksi) {
                session()->setFlashdata('success', 'Update transaksi berhasil');
                return redirect()->to(base_url('dashboard/transaksi'));
            } else {
                session()->setFlashdata('errors', 'Update transaksi gagal');
                return redirect()->to(base_url('dashboard/transaksi'));
            }
        }
    }

    public function transaksi_delete()
    {
        $input = $this->request->getRawInput();
        $id_en = $input['id'];
        $id = dekrip($id_en);
        $delete = $this->transaksi->delete($id);
        if ($delete) {
            $response = [
                'status' => 201,
                'message' => 'Data transaksi berhasil dihapus',
            ];
        } else {
            $response = [
                'status' => 500,
                'message' => 'Data transaksi gagal dihapus',
            ];
        }

        return $this->response->setJSON($response);
    }

    public function dana_list()
    {
        $request = Services::request();
        $m_dana = new DanaMasuk_Model($request);
        if ($request->getMethod(true) == 'POST') {
            $lists = $m_dana->get_datatables(null);
            $data = [];
            $no = $request->getPost("start");
            foreach ($lists as $list) {
                $no++;
                $row = [];
                $row[] = $no;
                $row[] = $list->nama_proyek;
                $row[] = date('d-m-Y', strtotime($list->tgl_transaksi));
                $row[] = $list->keterangan;
                $row[] = rupiah($list->jumlah);
                $row[] = '<a href="' . base_url('dashboard/dana_edit/' . enkrip($list->id)) . '" class="text-secondary"><i class="fa fa-pencil-alt"></i></a> &nbsp; <a href="#" onClick="return deleteDana(' . $list->id . ')" class="text-secondary"><i class="fa fa-trash"></i></a>';
                $data[] = $row;
            }
            $output = ["draw" => $request->getPost('draw'),
                "recordsTotal" => $m_dana->count_all(),
                "recordsFiltered" => $m_dana->count_filtered(),
                "data" => $data];
            echo json_encode($output);
        }
    }

    public function dana()
    {
        $data = [
            'title' => 'Manajemen Proyek',
        ];
        return view('bendahara/dana', $data);
    }

    public function dana_lap_list($id_proyek)
    {
        $request = Services::request();
        $m_dana = new DanaMasuk_Model($request);
        if ($request->getMethod(true) == 'POST') {
            $lists = $m_dana->get_datatables($id_proyek);
            $data = [];
            $no = $request->getPost("start");
            foreach ($lists as $list) {
                $no++;
                $row = [];
                $row[] = $no;
                $row[] = $list->nama_proyek;
                $row[] = date('d-m-Y', strtotime($list->tgl_transaksi));
                $row[] = $list->keterangan;
                $row[] = rupiah($list->jumlah);
                $data[] = $row;
            }
            $output = ["draw" => $request->getPost('draw'),
                "recordsTotal" => $m_dana->count_all(),
                "recordsFiltered" => $m_dana->count_filtered(),
                "data" => $data];
            echo json_encode($output);
        }
    }

    public function dana_lap()
    {
        $data = [
            'title' => 'Manajemen Proyek',
            'proyek' => $this->proyek->findAll()
        ];
        return view('direktur/dana', $data);
    }

    public function dana_add()
    {
        $data = [
            'title' => 'Manajemen Proyek',
            'proyek' => $this->proyek->findAll(),
        ];
        return view('bendahara/dana/dana_add', $data);
    }

    public function dana_store()
    {
        $id_proyek = $this->request->getPost('id_proyek');
        $tgl_transaksi = $this->request->getPost('tgl_transaksi');
        $keterangan = $this->request->getPost('keterangan');
        $jumlah = $this->request->getPost('jumlah');

        $validasi = [
            'id_proyek' => $id_proyek,
            'tgl_transaksi' => $tgl_transaksi,
            'keterangan' => $keterangan,
            'jumlah' => $jumlah,
        ];

        if ($this->validation->run($validasi, 'insertDana') == false) {
            $data = [
                'title' => 'Manajemen Proyek',
                'validation' => $this->validation->getErrors(),
                'proyek' => $this->proyek->findAll(),
            ];
            return view('bendahara/dana/dana_add', $data);
        } else {
            $insert = [
                'id_proyek' => $id_proyek,
                'tgl_transaksi' => $tgl_transaksi,
                'keterangan' => $keterangan,
                'jumlah' => $jumlah,
            ];
            $save = $this->dana->save($insert);
            if ($save) {
                session()->setFlashdata('success', 'Tambah dana masuk berhasil');
                return redirect()->to(base_url('dashboard/dana'));
            } else {
                session()->setFlashdata('errors', 'Tambah dana masuk gagal');
                return redirect()->to(base_url('dashboard/dana'));
            }
        }
    }

    public function dana_edit($id)
    {
        $data = [
            'title' => 'Manajemen Proyek',
            'data' => $this->dana->find(dekrip($id)),
            'proyek' => $this->proyek->findAll(),
            'id' => $id,
        ];
        return view('bendahara/dana/dana_edit', $data);

    }

    public function dana_update()
    {
        $dekrip = $this->request->getPost('id');
        $id_proyek = $this->request->getPost('id_proyek');
        $tgl_transaksi = $this->request->getPost('tgl_transaksi');
        $keterangan = $this->request->getPost('keterangan');
        $jumlah = $this->request->getPost('jumlah');
        $id = dekrip($dekrip);

        $validasi = [
            'id_proyek' => $id_proyek,
            'tgl_transaksi' => $tgl_transaksi,
            'keterangan' => $keterangan,
            'jumlah' => $jumlah,
        ];

        if ($this->validation->run($validasi, 'insertDana') == false) {
            $data = [
                'title' => 'Manajemen Proyek',
                'validation' => $this->validation->getErrors(),
                'data' => $this->dana->find($id),
                'proyek' => $this->proyek->findAll(),
                'id' => $dekrip,
            ];
            return view('bendahara/dana/dana_edit', $data);
        } else {
            $update = [
                'id_proyek' => $id_proyek,
                'tgl_transaksi' => $tgl_transaksi,
                'keterangan' => $keterangan,
                'jumlah' => $jumlah,
            ];
            $update_dana = $this->dana->where('id', $id)->set($update)->update();
            if ($update_dana) {
                session()->setFlashdata('success', 'Update dana masuk berhasil');
                return redirect()->to(base_url('dashboard/dana'));
            } else {
                session()->setFlashdata('errors', 'Update dana masuk gagal');
                return redirect()->to(base_url('dashboard/dana'));
            }
        }
    }

    public function dana_delete()
    {
        $input = $this->request->getRawInput();
        $id_en = $input['id'];
        $id = dekrip($id_en);
        $delete = $this->dana->delete($id);
        if ($delete) {
            $response = [
                'status' => 201,
                'message' => 'Data dana masuk berhasil dihapus',
            ];
        } else {
            $response = [
                'status' => 500,
                'message' => 'Data dana masuk gagal dihapus',
            ];
        }

        return $this->response->setJSON($response);
    }

    public function dana_keluar_list()
    {
        $request = Services::request();
        $m_dana_keluar = new DanaKeluar_Model($request);
        if ($request->getMethod(true) == 'POST') {
            $lists = $m_dana_keluar->get_datatables(null);
            $data = [];
            $no = $request->getPost("start");
            foreach ($lists as $list) {
                $no++;
                $row = [];
                $row[] = $no;
                $row[] = $list->nama_proyek;
                $row[] = date('d-m-Y', strtotime($list->tgl_transaksi));
                $row[] = $list->keterangan;
                $row[] = rupiah($list->jumlah);
                $row[] = '<a href="' . base_url('dashboard/dana_keluar_edit/' . enkrip($list->id)) . '" class="text-secondary"><i class="fa fa-pencil-alt"></i></a> &nbsp; <a href="#" onClick="return deleteDanaKeluar(' . $list->id . ')" class="text-secondary"><i class="fa fa-trash"></i></a>';
                $data[] = $row;
            }
            $output = ["draw" => $request->getPost('draw'),
                "recordsTotal" => $m_dana_keluar->count_all(),
                "recordsFiltered" => $m_dana_keluar->count_filtered(),
                "data" => $data];
            echo json_encode($output);
        }
    }

    public function dana_keluar()
    {
        $data = [
            'title' => 'Manajemen Proyek',
        ];
        return view('bendahara/dana_keluar', $data);
    }

    public function dana_keluar_lap_list($id_proyek)
    {
        $request = Services::request();
        $m_dana_keluar = new DanaKeluar_Model($request);
        if ($request->getMethod(true) == 'POST') {
            $lists = $m_dana_keluar->get_datatables($id_proyek);
            $data = [];
            $no = $request->getPost("start");
            foreach ($lists as $list) {
                $no++;
                $row = [];
                $row[] = $no;
                $row[] = $list->nama_proyek;
                $row[] = date('d-m-Y', strtotime($list->tgl_transaksi));
                $row[] = $list->keterangan;
                $row[] = rupiah($list->jumlah);
                $data[] = $row;
            }
            $output = ["draw" => $request->getPost('draw'),
                "recordsTotal" => $m_dana_keluar->count_all(),
                "recordsFiltered" => $m_dana_keluar->count_filtered(),
                "data" => $data];
            echo json_encode($output);
        }
    }

    public function dana_keluar_lap()
    {
        $data = [
            'title' => 'Manajemen Proyek',
            'proyek'=> $this->proyek->findAll()
        ];
        return view('direktur/dana_keluar', $data);
    }

    public function dana_keluar_add()
    {
        $data = [
            'title' => 'Manajemen Proyek',
            'proyek' => $this->proyek->findAll(),
        ];
        return view('bendahara/dana_keluar/dana_add', $data);
    }

    public function dana_keluar_store()
    {
        $id_proyek = $this->request->getPost('id_proyek');
        $tgl_transaksi = $this->request->getPost('tgl_transaksi');
        $keterangan = $this->request->getPost('keterangan');
        $jumlah = $this->request->getPost('jumlah');

        $validasi = [
            'id_proyek' => $id_proyek,
            'tgl_transaksi' => $tgl_transaksi,
            'keterangan' => $keterangan,
            'jumlah' => $jumlah,
        ];

        if ($this->validation->run($validasi, 'insertDana') == false) {
            $data = [
                'title' => 'Manajemen Proyek',
                'validation' => $this->validation->getErrors(),
                'proyek' => $this->proyek->findAll(),
            ];
            return view('bendahara/dana_keluar/dana_add', $data);
        } else {
            $insert = [
                'id_proyek' => $id_proyek,
                'tgl_transaksi' => $tgl_transaksi,
                'keterangan' => $keterangan,
                'jumlah' => $jumlah,
            ];
            $save = $this->danaKeluar->save($insert);
            if ($save) {
                session()->setFlashdata('success', 'Tambah dana_keluar masuk berhasil');
                return redirect()->to(base_url('dashboard/dana_keluar'));
            } else {
                session()->setFlashdata('errors', 'Tambah dana_keluar masuk gagal');
                return redirect()->to(base_url('dashboard/dana_keluar'));
            }
        }
    }

    public function dana_keluar_edit($id)
    {
        $data = [
            'title' => 'Manajemen Proyek',
            'data' => $this->dana_keluar->find(dekrip($id)),
            'proyek' => $this->proyek->findAll(),
            'id' => $id,
        ];
        return view('bendahara/dana_keluar/dana_keluar_edit', $data);

    }

    public function dana_keluar_update()
    {
        $dekrip = $this->request->getPost('id');
        $id_proyek = $this->request->getPost('id_proyek');
        $tgl_transaksi = $this->request->getPost('tgl_transaksi');
        $keterangan = $this->request->getPost('keterangan');
        $jumlah = $this->request->getPost('jumlah');
        $id = dekrip($dekrip);

        $validasi = [
            'id_proyek' => $id_proyek,
            'tgl_transaksi' => $tgl_transaksi,
            'keterangan' => $keterangan,
            'jumlah' => $jumlah,
        ];

        if ($this->validation->run($validasi, 'insertdana_keluar') == false) {
            $data = [
                'title' => 'Manajemen Proyek',
                'validation' => $this->validation->getErrors(),
                'data' => $this->dana_keluar->find($id),
                'proyek' => $this->proyek->findAll(),
                'id' => $dekrip,
            ];
            return view('bendahara/dana_keluar/dana_keluar_edit', $data);
        } else {
            $update = [
                'id_proyek' => $id_proyek,
                'tgl_transaksi' => $tgl_transaksi,
                'keterangan' => $keterangan,
                'jumlah' => $jumlah,
            ];
            $update_dana_keluar = $this->dana_keluar->where('id', $id)->set($update)->update();
            if ($update_dana_keluar) {
                session()->setFlashdata('success', 'Update dana_keluar masuk berhasil');
                return redirect()->to(base_url('dashboard/dana_keluar'));
            } else {
                session()->setFlashdata('errors', 'Update dana_keluar masuk gagal');
                return redirect()->to(base_url('dashboard/dana_keluar'));
            }
        }
    }

    public function dana_keluar_delete()
    {
        $input = $this->request->getRawInput();
        $id_en = $input['id'];
        $id = dekrip($id_en);
        $delete = $this->dana_keluar->delete($id);
        if ($delete) {
            $response = [
                'status' => 201,
                'message' => 'Data dana_keluar masuk berhasil dihapus',
            ];
        } else {
            $response = [
                'status' => 500,
                'message' => 'Data dana_keluar masuk gagal dihapus',
            ];
        }

        return $this->response->setJSON($response);
    }


    public function dokumentasi_list($id=null)
    {
        $request = Services::request();
        $m_dokumentasi = new Dokumentasi_Model($request);
        if ($request->getMethod(true) == 'POST') {
            $lists = $m_dokumentasi->get_datatables($id);
            $data = [];
            $no = $request->getPost("start");
            foreach ($lists as $list) {
                $no++;
                $row = [];
                $row[] = $no;
                $row[] = $list->nama_proyek;
                $row[] = $list->jenis_kegiatan;
                $row[] = date('d-m-Y', strtotime($list->tgl_mulai));
                $row[] = date('d-m-Y', strtotime($list->tgl_selesai));
                $row[] = $list->progress_proyek;
                $row[] = $list->status_proyek;
                $row[] = $list->dokumentasi != null ? '<img src="' . base_url() . '/uploads/dokumentasi/' . $list->dokumentasi . '" style="height:75px;">' : '';
                $row[] = '<a href="' . base_url('dashboard/dokumentasi_edit/' . enkrip($list->id)) . '" class="text-secondary"><i class="fa fa-pencil-alt"></i></a> &nbsp; <a href="#" onClick="return deleteDokumentasi(' . $list->id . ')" class="text-secondary"><i class="fa fa-trash"></i></a>';
                $data[] = $row;
            }
            $output = ["draw" => $request->getPost('draw'),
                "recordsTotal" => $m_dokumentasi->count_all($id),
                "recordsFiltered" => $m_dokumentasi->count_filtered($id),
                "data" => $data];
            echo json_encode($output);
        }
    }

    public function dokumentasi()
    {
        $data = [
            'title' => 'Manajemen Proyek',
        ];
        return view('lapangan/dokumentasi', $data);
    }

    public function dokumentasi_lap_list($id)
    {
        $request = Services::request();
        $m_dokumentasi = new Dokumentasi_Model($request);
        if ($request->getMethod(true) == 'POST') {
            $lists = $m_dokumentasi->get_datatables($id);
            $data = [];
            $no = $request->getPost("start");
            foreach ($lists as $list) {
                $no++;
                $row = [];
                $row[] = $no;
                $row[] = $list->nama_proyek;
                $row[] = $list->jenis_kegiatan;
                $row[] = date('d-m-Y', strtotime($list->tgl_mulai));
                $row[] = date('d-m-Y', strtotime($list->tgl_selesai));
                $row[] = $list->progress_proyek;
                $row[] = $list->status_proyek;
                $row[] = $list->dokumentasi != null ? '<img src="' . base_url() . '/uploads/dokumentasi/' . $list->dokumentasi . '" style="height:75px;">' : '';
                $data[] = $row;
            }
            $output = ["draw" => $request->getPost('draw'),
                "recordsTotal" => $m_dokumentasi->count_all(),
                "recordsFiltered" => $m_dokumentasi->count_filtered(),
                "data" => $data];
            echo json_encode($output);
        }
    }

    public function dokumentasi_read()
    {
        $data = $this->proyek->join('pegawai', 'pegawai.id=proyek.id_pegawai', 'left')->join('users', 'users.id_pegawai=pegawai.id')->where(['proyek.deleted_at'=> NULL, 'users.id'=> session()->get('uid')])->get()->getResultObject();
        foreach ($data as $key => $value) {
            $value->kegiatan = $this->kelola_kegiatan->where('id_proyek', $value->id)->get()->getResult();
        }
        return $this->respond($data);
    }
    public function dokumentasi_lap()
    {
        $data = [
            'title' => 'Manajemen Proyek',
            'proyek'=> $this->proyek->findAll()
        ];
        return view('direktur/dokumentasi', $data);
    }

    public function dokumentasi_add()
    {
        $data = [
            'title' => 'Manajemen Proyek',
        ];
        return view('lapangan/dokumentasi/dokumentasi_add', $data);
    }

    public function dokumentasi_store()
    {
        $data = $this->request->getJSON();
        $data->dokumentasi = isset($data->dokumentasi->base64) ? $this->decode->decodebase64($data->dokumentasi->base64, 'foto') : $data->dokumentasi;
        $this->dokumentasi->save($data);
        session()->setFlashdata('success', 'Update Dokumentasi berhasil');
        return $this->respond(true);
    }

    public function dokumentasi_edit($id)
    {
        $data = [
            'title' => 'Manajemen Proyek',
            'data' => $this->dokumentasi->find(dekrip($id)),
            'proyek' => $this->proyek->findAll(),
            'id' => $id,
        ];
        $data['dataproyek'] = $this->proyek->find($data['data']['id_proyek']);
        return view('lapangan/dokumentasi/dokumentasi_edit', $data);
    }
    
    public function dokumentasi_getedit($id)
    {
        $a = dekrip($id);
        $data['proyek'] = $this->proyek->where('deleted_at', NULL)->get()->getResultObject();
        foreach ($data['proyek'] as $key => $value) {
            $value->kegiatan = $this->kelola_kegiatan->where('id_proyek', $value->id)->get()->getResult();
        }
        $data['kegiatan'] = $this->dokumentasi->find(dekrip($id));
        return $this->respond($data);
    }

    public function dokumentasi_update()
    {
        $data = $this->request->getJSON();
        if(isset($data->dokumentasi->base64)){
            $data->dokumentasi = isset($data->dokumentasi->base64) ? $this->decode->decodebase64($data->dokumentasi->base64, 'foto') : $data->dokumentasi;
        }
        $this->dokumentasi->update($data->id, $data);
        session()->setFlashdata('success', 'Update Dokumentasi berhasil');
        return $this->respond(true);
    }

    public function dokumentasi_delete()
    {
        $input = $this->request->getRawInput();
        $id_en = $input['id'];
        $id = dekrip($id_en);
        $data = $this->dokumentasi->where('id', $id)->first();
        if ($data['dokumentasi'] != null && $data['dokumentasi'] != '') {
            // unlink(ROOTPATH . 'uploads/dokumentasi/' . $data['dokumentasi']);
            unlink('uploads/dokumentasi/' . $data['dokumentasi']);
        }
        $this->dokumentasi->where('id', $id)->set(['dokumentasi' => null])->update();
        $delete = $this->dokumentasi->delete($id);
        if ($delete) {
            $response = [
                'status' => 201,
                'message' => 'Data dokumentasi berhasil dihapus',
            ];
        } else {
            $response = [
                'status' => 500,
                'message' => 'Data dokumentasi gagal dihapus',
            ];
        }

        return $this->response->setJSON($response);
    }

    public function pemakaian_read()
    {
        $data['proyek'] = $this->proyek->where(['deleted_at'=> NULL, 'id_pegawai'=>session()->get('uid')])->get()->getResultObject();
        $data['kendaraan'] = $this->kendaraan->where('deleted_at', NULL)->get()->getResultObject();
        $data['bahanBakar'] = $this->bbm->where('deleted_at', NULL)->get()->getResultObject();
        foreach ($data['proyek'] as $key => $value) {
            $value->kegiatan = $this->kegiatan->getData($value->id);
        }
        return $this->respond($data);
        
    }
    
    public function pemakaian_getedit($id)
    {
        $data['proyek'] = $this->proyek->where('deleted_at', NULL)->get()->getResultObject();
        foreach ($data['proyek'] as $key => $value) {
            $value->kegiatan = $this->kegiatan->getData($value->id);
        }
        $data['kendaraan'] = $this->kendaraan->where('deleted_at', NULL)->findAll();
        $data['bbm'] = $this->bbm->where('deleted_at', NULL)->findAll();
        $data['pemakaian'] = $this->penggunaan->find(dekrip($id));
        return $this->respond($data);
    }

    public function penggunaan_list()
    {
        $request = Services::request();
        $m_penggunaan = new PenggunaanKendaraan_Model($request);
        if ($request->getMethod(true) == 'POST') {
            $lists = $m_penggunaan->get_datatables();
            $data = [];
            $no = $request->getPost("start");
            foreach ($lists as $list) {
                $no++;
                $row = [];
                $row[] = $no;
                $row[] = $list->nama_proyek;
                $row[] = $list->jenis_kendaraan;
                $row[] = date('d-m-Y', strtotime($list->tgl_kegiatan));
                $row[] = rupiah($list->pemakaian_bbm);
                $row[] = rupiah($list->jumlah_rpm);
                $row[] = $list->nama_bahan_bakar;
                $row[] = '<a href="' . base_url('dashboard/penggunaan_edit/' . enkrip($list->id)) . '" class="text-secondary"><i class="fa fa-pencil-alt"></i></a> &nbsp; <a href="#" onClick="return deletePenggunaan(' . $list->id . ')" class="text-secondary"><i class="fa fa-trash"></i></a>';
                $data[] = $row;
            }
            $output = ["draw" => $request->getPost('draw'),
                "recordsTotal" => $m_penggunaan->count_all(),
                "recordsFiltered" => $m_penggunaan->count_filtered(),
                "data" => $data];
            echo json_encode($output);
        }
    }

    public function penggunaan()
    {
        $data = [
            'title' => 'Manajemen Proyek',
        ];
        return view('lapangan/penggunaan', $data);
    }

    public function penggunaan_lap_list()
    {
        $request = Services::request();
        $m_penggunaan = new PenggunaanKendaraan_Model($request);
        if ($request->getMethod(true) == 'POST') {
            $lists = $m_penggunaan->get_datatables();
            $data = [];
            $no = $request->getPost("start");
            foreach ($lists as $list) {
                $no++;
                $row = [];
                $row[] = $no;
                $row[] = $list->nama_proyek;
                $row[] = $list->jenis_kendaraan;
                $row[] = date('d-m-Y', strtotime($list->tgl_kegiatan));
                $row[] = $list->pemakaian_bbm;
                $row[] = rupiah($list->jumlah_rpm);
                $row[] = $list->nama_bahan_bakar;
                $data[] = $row;
            }
            $output = ["draw" => $request->getPost('draw'),
                "recordsTotal" => $m_penggunaan->count_all(),
                "recordsFiltered" => $m_penggunaan->count_filtered(),
                "data" => $data];
            echo json_encode($output);
        }
    }

    public function penggunaan_lap()
    {
        $data = [
            'title' => 'Manajemen Proyek',
        ];
        return view('direktur/penggunaan', $data);
    }

    public function penggunaan_add()
    {
        $data = [
            'title' => 'Manajemen Proyek',
            'proyek' => $this->proyek->findAll(),
            'kendaraan' => $this->kendaraan->findAll(),
            'bbm' => $this->bbm->findAll(),
        ];
        return view('lapangan/penggunaan/penggunaan_add', $data);
    }

    public function penggunaan_store()
    {
        $insert = $this->request->getJSON();
        $save = $this->penggunaan->save($insert);
        if ($save) {
            session()->setFlashdata('success', 'Tambah penggunaan kendaraan berhasil');
            return redirect()->to(base_url('dashboard/penggunaan'));
        } else {
            session()->setFlashdata('errors', 'Tambah penggunaan kendaraan gagal');
            return redirect()->to(base_url('dashboard/penggunaan'));
        }
    }

    public function penggunaan_edit($id)
    {
        $data = [
            'title' => 'Manajemen Proyek',
            'data' => $this->penggunaan->find(dekrip($id)),
            'proyek' => $this->proyek->findAll(),
            'kendaraan' => $this->kendaraan->findAll(),
            'bbm' => $this->bbm->findAll(),
            'id' => $id,
        ];
        return view('lapangan/penggunaan/penggunaan_edit', $data);

    }

    public function penggunaan_update()
    {
        $update = $this->request->getJSON();
        $update_penggunaan = $this->penggunaan->update($update->id, $update);
        if ($update_penggunaan) {
            session()->setFlashdata('success', 'Update penggunaan kendaraan berhasil');
            return redirect()->to(base_url('dashboard/penggunaan'));
        } else {
            session()->setFlashdata('errors', 'Update penggunaan kendaraan gagal');
            return redirect()->to(base_url('dashboard/penggunaan'));
        }
    }

    public function penggunaan_delete()
    {
        $input = $this->request->getRawInput();
        $id_en = $input['id'];
        $id = dekrip($id_en);
        $delete = $this->penggunaan->delete($id);
        if ($delete) {
            $response = [
                'status' => 201,
                'message' => 'Data penggunaan kendaraan berhasil dihapus',
            ];
        } else {
            $response = [
                'status' => 500,
                'message' => 'Data penggunaan kendaraan gagal dihapus',
            ];
        }

        return $this->response->setJSON($response);
    }

    public function pemakaian_bbm_read()
    {
        $data['proyek'] = $this->proyek->where('deleted_at', NULL)->get()->getResultObject();
        $data['kendaraan'] = $this->kendaraan->where('deleted_at', NULL)->get()->getResultObject();
        $data['bahanBakar'] = $this->bbm->where('deleted_at', NULL)->get()->getResultObject();
        foreach ($data['proyek'] as $key => $value) {
            $value->kegiatan = $this->pemakaian_bbm->getData($value->id);
            foreach ($value->kegiatan as $key => $kegiatan) {
                $kegiatan->pemakaian_bbm = (int)$kegiatan->pemakaian_bbm;
                $kegiatan->jumlah_rpm = (int)$kegiatan->jumlah_rpm;
            }
        }
        return $this->respond($data);
        
    }
    
    public function pemakaian_bbm_getedit($id)
    {
        $data['proyek'] = $this->proyek->where('deleted_at', NULL)->get()->getResultObject();
        $data['kendaraan'] = $this->kendaraan->where('deleted_at', NULL)->get()->getResultObject();
        $data['bahanBakar'] = $this->bbm->where('deleted_at', NULL)->get()->getResultObject();
        foreach ($data['proyek'] as $key => $value) {
            $value->kegiatan = $this->pemakaian_bbm->getData($value->id);
            foreach ($value->kegiatan as $key => $kegiatan) {
                $kegiatan->pemakaian_bbm = (int)$kegiatan->pemakaian_bbm;
                $kegiatan->jumlah_rpm = (int)$kegiatan->jumlah_rpm;
            }
        }
        $data['pemakaian'] = $this->pemakaian_bbm->find(dekrip($id));
        return $this->respond($data);
    }

    public function pemakaian_bbm_list()
    {
        $request = Services::request();
        $m_pemakaian_bbm = new PemakaianBBM_Model($request);
        if ($request->getMethod(true) == 'POST') {
            $lists = $m_pemakaian_bbm->get_datatables(null);
            $data = [];
            $no = $request->getPost("start");
            foreach ($lists as $list) {
                $no++;
                $row = [];
                $row[] = $no;
                $row[] = $list->nama_proyek;
                $row[] = $list->jenis_kegiatan;
                $row[] = $list->jenis_kendaraan;
                $row[] = $list->nomor_polisi;
                $row[] = $list->jumlah_pemakaian;
                $row[] = $list->nama_bahan_bakar;
                $row[] = '<a href="' . base_url('dashboard/pemakaian_bbm_edit/' . enkrip($list->id)) . '" class="text-secondary"><i class="fa fa-pencil-alt"></i></a> &nbsp; <a href="#" onClick="return deletePemakaianBBM(' . $list->id . ')" class="text-secondary"><i class="fa fa-trash"></i></a>';
                $data[] = $row;
            }
            $output = ["draw" => $request->getPost('draw'),
                "recordsTotal" => $m_pemakaian_bbm->count_all(null),
                "recordsFiltered" => $m_pemakaian_bbm->count_filtered(null),
                "data" => $data];
            echo json_encode($output);
        }
    }

    public function pemakaian_bbm()
    {
        $data = [
            'title' => 'Manajemen Proyek',
        ];
        return view('lapangan/pemakaian_bbm', $data);
    }
    
    public function pemakaian_bbm_lap()
    {
        $data = [
            'title' => 'Manajemen Proyek',
            'proyek'=> $this->proyek->findAll()
        ];
        return view('direktur/pemakaian_bbm', $data);
    }
    public function pemakaian_bbm_lap_list($id=null)
    {
        $request = Services::request();
        $m_pemakaian_bbm = new PemakaianBBM_Model($request);
        if ($request->getMethod(true) == 'POST') {
            $lists = $m_pemakaian_bbm->get_datatables($id);
            $data = [];
            $no = $request->getPost("start");
            foreach ($lists as $list) {
                $no++;
                $row = [];
                $row[] = $no;
                $row[] = $list->nama_proyek;
                $row[] = $list->jenis_kegiatan;
                $row[] = $list->jenis_kendaraan;
                $row[] = $list->nomor_polisi;
                $row[] = $list->jumlah_pemakaian;
                $row[] = $list->nama_bahan_bakar;
                $data[] = $row;
            }
            $output = ["draw" => $request->getPost('draw'),
                "recordsTotal" => $m_pemakaian_bbm->count_all($id),
                "recordsFiltered" => $m_pemakaian_bbm->count_filtered($id),
                "data" => $data];
            echo json_encode($output);
        }
    }

    public function pemakaian_bbm_add()
    {
        $data = [
            'title' => 'Manajemen Proyek',
            'kendaraan' => $this->kendaraan->findAll(),
            'bbm' => $this->bbm->findAll(),
        ];
        
        return view('lapangan/pemakaian_bbm/pemakaian_bbm_add', $data);
    }

    public function pemakaian_bbm_store()
    {
        $dataPost = $this->request->getJSON();
        $save = $this->pemakaian_bbm->save($dataPost);
        if ($save) {
            session()->setFlashdata('success', 'Tambah pemakaian bahan bakar berhasil');
            return $this->respond(true);
        } else {
            session()->setFlashdata('errors', 'Tambah pemakaian bahan bakar gagal');
            return $this->respond(false);
        }
    }

    public function pemakaian_bbm_edit($id)
    {
        $data = [
            'title' => 'Manajemen Proyek',
            'data' => $this->pemakaian_bbm->find(dekrip($id)),
            'kendaraan' => $this->kendaraan->findAll(),
            'bbm' => $this->bbm->findAll(),
            'id' => $id,
        ];
        return view('lapangan/pemakaian_bbm/pemakaian_bbm_edit', $data);

    }

    public function pemakaian_bbm_update()
    {
        $update = $this->request->getJSON();
        $update_pemakaian_bbm = $this->pemakaian_bbm->update($update->id, $update);
        if ($update_pemakaian_bbm) {
            session()->setFlashdata('success', 'Update pemakaian bahan bakar berhasil');
            return $this->respond(true);
        } else {
            session()->setFlashdata('errors', 'Update pemakaian bahan bakar gagal');
            return $this->fail(false);
        }
    }

    public function pemakaian_bbm_delete()
    {
        $input = $this->request->getRawInput();
        $id_en = $input['id'];
        $id = dekrip($id_en);
        $delete = $this->pemakaian_bbm->delete($id);
        if ($delete) {
            $response = [
                'status' => 201,
                'message' => 'Data pemakaian bahan bakar berhasil dihapus',
            ];
        } else {
            $response = [
                'status' => 500,
                'message' => 'Data pemakaian bahan bakar gagal dihapus',
            ];
        }

        return $this->response->setJSON($response);
    }

    public function material_read()
    {
        $data['proyek'] = $this->proyek->where(['deleted_at'=> NULL, 'id_pegawai'=>session()->get('uid')])->get()->getResultObject();
        $data['jenis_material'] = $this->jenis_material->where('deleted_at', NULL)->get()->getResultObject();
        foreach ($data['proyek'] as $key => $value) {
            $value->kegiatan = $this->kegiatan->findAll($value->id);
        }
        return $this->respond($data);
    }
    
    public function material_getedit($id)
    {
        $data['proyek'] = $this->proyek->where('deleted_at', NULL)->get()->getResultObject();
        $data['jenis_material'] = $this->jenis_material->where('deleted_at', NULL)->get()->getResultObject();
        foreach ($data['proyek'] as $key => $value) {
            $value->kegiatan = $this->pemakaian_bbm->findAll($value->id);
        }
        $data['material'] = $this->material->find(dekrip($id));
        return $this->respond($data);
    }

    public function material_list()
    {
        $request = Services::request();
        $m_material = new PemakaianMaterial_Model($request);
        if ($request->getMethod(true) == 'POST') {
            $lists = $m_material->get_datatables();
            $data = [];
            $no = $request->getPost("start");
            foreach ($lists as $list) {
                $no++;
                $row = [];
                $row[] = $no;
                $row[] = $list->nama_proyek;
                $row[] = $list->jenis_kegiatan;
                $row[] = $list->nama_material;
                $row[] = date('d-m-Y', strtotime($list->tgl_penggunaan));
                $row[] = rupiah($list->jumlah_pemakaian);
                $row[] = '<a href="' . base_url('dashboard/material_edit/' . enkrip($list->id)) . '" class="text-secondary"><i class="fa fa-pencil-alt"></i></a> &nbsp; <a href="#" onClick="return deleteMaterial(' . $list->id . ')" class="text-secondary"><i class="fa fa-trash"></i></a>';
                $data[] = $row;
            }
            $output = ["draw" => $request->getPost('draw'),
                "recordsTotal" => $m_material->count_all(),
                "recordsFiltered" => $m_material->count_filtered(),
                "data" => $data];
            echo json_encode($output);
        }
    }

    public function material()
    {
        $data = [
            'title' => 'Manajemen Proyek',
        ];
        return view('lapangan/material', $data);
    }

    public function material_lap_list()
    {
        $request = Services::request();
        $m_material = new PemakaianMaterial_Model($request);
        if ($request->getMethod(true) == 'POST') {
            $lists = $m_material->get_datatables();
            $data = [];
            $no = $request->getPost("start");
            foreach ($lists as $list) {
                $no++;
                $row = [];
                $row[] = $no;
                $row[] = $list->nama_proyek;
                $row[] = $list->jenis_kegiatan;
                $row[] = $list->nama_material;
                $row[] = date('d-m-Y', strtotime($list->tgl_penggunaan));
                $row[] = rupiah($list->jumlah_pemakaian);
                $data[] = $row;
            }
            $output = ["draw" => $request->getPost('draw'),
                "recordsTotal" => $m_material->count_all(),
                "recordsFiltered" => $m_material->count_filtered(),
                "data" => $data];
            echo json_encode($output);
        }
    }

    public function material_lap()
    {
        $data = [
            'title' => 'Manajemen Proyek',
        ];
        return view('direktur/material', $data);
    }

    public function material_add()
    {
        $data = [
            'title' => 'Manajemen Proyek',
        ];
        return view('lapangan/material/material_add', $data);
    }

    public function material_store()
    {
        $insert = $this->request->getJSON();
        $save = $this->material->save($insert);
        if ($save) {
            session()->setFlashdata('success', 'Tambah penggunaan material berhasil');
            return $this->respond(true);
        } else {
            session()->setFlashdata('errors', 'Tambah penggunaan material gagal');
            return $this->respond(true);
        }
    }

    public function material_edit($id)
    {
        $data = [
            'title' => 'Manajemen Proyek',
            'data' => $this->material->find(dekrip($id)),
            'id' => $id,
        ];
        return view('lapangan/material/material_edit', $data);

    }

    public function material_update()
    {
        $update = $this->request->getJSON();
        $save = $this->material->update($update->id, $update);
        if ($save) {
            session()->setFlashdata('success', 'Tambah penggunaan material berhasil');
            return $this->respond(true);
        } else {
            session()->setFlashdata('errors', 'Tambah penggunaan material gagal');
            return $this->respond(true);
        }
    }

    public function material_delete()
    {
        $input = $this->request->getRawInput();
        $id_en = $input['id'];
        $id = dekrip($id_en);
        $delete = $this->material->delete($id);
        if ($delete) {
            $response = [
                'status' => 201,
                'message' => 'Data penggunaan material berhasil dihapus',
            ];
        } else {
            $response = [
                'status' => 500,
                'message' => 'Data penggunaan material gagal dihapus',
            ];
        }

        return $this->response->setJSON($response);
    }
    

    
    public function jenis_material_read()
    {
        $data['material'] = $this->jenis_material->where('deleted_at', NULL)->get()->getResultObject();
        foreach ($data['proyek'] as $key => $value) {
            $value->kegiatan = $this->kegiatan->findAll($value->id);
        }
        return $this->respond($data);
        
    }
    
    public function jenis_material_getedit($id)
    {
        $data['material'] = $this->jenis_material->find(dekrip($id));
        return $this->respond($data);
    }

    public function jenis_material_list()
    {
        $request = Services::request();
        $m_material = new JenisMaterialModel($request);
        if ($request->getMethod(true) == 'POST') {
            $lists = $m_material->get_datatables();
            $data = [];
            $no = $request->getPost("start");
            foreach ($lists as $list) {
                $no++;
                $row = [];
                $row[] = $no;
                $row[] = $list->nama_material;
                $row[] = '<a href="' . base_url('dashboard/jenis_material_edit/' . enkrip($list->id)) . '" class="text-secondary"><i class="fa fa-pencil-alt"></i></a> &nbsp; <a href="#" onClick="return deleteMaterial(' . $list->id . ')" class="text-secondary"><i class="fa fa-trash"></i></a>';
                $data[] = $row;
            }
            $output = ["draw" => $request->getPost('draw'),
                "recordsTotal" => $m_material->count_all(),
                "recordsFiltered" => $m_material->count_filtered(),
                "data" => $data];
            echo json_encode($output);
        }
    }

    public function jenis_material()
    {
        $data = [
            'title' => 'Manajemen Proyek',
        ];
        return view('lapangan/jenis_material', $data);
    }

    public function jenis_material_lap_list()
    {
        $request = Services::request();
        $m_material = new JenisMaterialModel($request);
        if ($request->getMethod(true) == 'POST') {
            $lists = $m_material->get_datatables();
            $data = [];
            $no = $request->getPost("start");
            foreach ($lists as $list) {
                $no++;
                $row = [];
                $row[] = $no;
                $row[] = $list->nama_material;
                $data[] = $row;
            }
            $output = ["draw" => $request->getPost('draw'),
                "recordsTotal" => $m_material->count_all(),
                "recordsFiltered" => $m_material->count_filtered(),
                "data" => $data];
            echo json_encode($output);
        }
    }

    public function jenis_material_lap()
    {
        $data = [
            'title' => 'Manajemen Proyek',
        ];
        return view('direktur/jenis_material', $data);
    }

    public function jenis_material_add()
    {
        $data = [
            'title' => 'Manajemen Proyek',
        ];
        return view('lapangan/jenis_material/jenis_material_add', $data);
    }

    public function jenis_material_store()
    {
        $nama_material = $this->request->getPost('nama_material');

        $validasi = [
            'nama_material' => $nama_material,
        ];

        if ($this->validation->run($validasi, 'insertJenisMaterial') == false) {
            $data = [
                'title' => 'Manajemen Proyek',
                'validation' => $this->validation->getErrors(),
            ];
            return view('lapangan/jenis_material/jenis_material_add', $data);
        } else {
            $insert = [
                'nama_material' => $nama_material,
            ];
            $save = $this->jenis_material->save($insert);
            if ($save) {
                session()->setFlashdata('success', 'Tambah jenis material berhasil');
                return redirect()->to(base_url('dashboard/jenis_material'));
            } else {
                session()->setFlashdata('errors', 'Tambah jenis material gagal');
                return redirect()->to(base_url('dashboard/jenis_material'));
            }
        }
    }

    public function jenis_material_edit($id)
    {
        $data = [
            'title' => 'Manajemen Proyek',
            'data' => $this->jenis_material->find(dekrip($id)),
            'id' => $id,
        ];
        return view('lapangan/jenis_material/jenis_material_edit', $data);

    }

    public function jenis_material_update()
    {
        $dekrip = $this->request->getPost('id');
        $nama_material = $this->request->getPost('nama_material');
        $id = dekrip($dekrip);

        $validasi = [
            'nama_material' => $nama_material,
        ];

        if ($this->validation->run($validasi, 'insertJenisMaterial') == false) {
            $data = [
                'title' => 'Manajemen Proyek',
                'validation' => $this->validation->getErrors(),
                'data' => $this->jenis_material->find($id),
                'id' => $dekrip,
            ];
            return view('lapangan/jenis_material/jenis_material_edit', $data);
        } else {
            $update = [
                'nama_material' => $nama_material,
            ];
            $update_jenis_material = $this->jenis_material->where('id', $id)->set($update)->update();
            if ($update_jenis_material) {
                session()->setFlashdata('success', 'Update jenis material berhasil');
                return redirect()->to(base_url('dashboard/jenis_material'));
            } else {
                session()->setFlashdata('errors', 'Update jenis material gagal');
                return redirect()->to(base_url('dashboard/jenis_material'));
            }
        }
    }

    public function jenis_material_delete()
    {
        $input = $this->request->getRawInput();
        $id_en = $input['id'];
        $id = dekrip($id_en);
        $delete = $this->jenis_material->delete($id);
        if ($delete) {
            $response = [
                'status' => 201,
                'message' => 'Data jenis material berhasil dihapus',
            ];
        } else {
            $response = [
                'status' => 500,
                'message' => 'Data jenis material gagal dihapus',
            ];
        }

        return $this->response->setJSON($response);
    }




    

    public function bbm_list()
    {
        $request = Services::request();
        $m_bbm = new BahanBakar_Model($request);
        if ($request->getMethod(true) == 'POST') {
            $lists = $m_bbm->get_datatables();
            $data = [];
            $no = $request->getPost("start");
            foreach ($lists as $list) {
                $no++;
                $row = [];
                $row[] = $no;
                $row[] = $list->nama_bahan_bakar;
                $row[] = '<a href="' . base_url('dashboard/bbm_edit/' . enkrip($list->id)) . '" class="text-secondary"><i class="fa fa-pencil-alt"></i></a> &nbsp; <a href="#" onClick="return deleteBBM(' . $list->id . ')" class="text-secondary"><i class="fa fa-trash"></i></a>';
                $data[] = $row;
            }
            $output = ["draw" => $request->getPost('draw'),
                "recordsTotal" => $m_bbm->count_all(),
                "recordsFiltered" => $m_bbm->count_filtered(),
                "data" => $data];
            echo json_encode($output);
        }
    }

    public function bbm()
    {
        $data = [
            'title' => 'Manajemen Proyek',
        ];
        return view('lapangan/bbm', $data);
    }

    public function bbm_add()
    {
        $data = [
            'title' => 'Manajemen Proyek',
        ];
        return view('lapangan/bbm/bbm_add', $data);
    }

    public function bbm_store()
    {
        $nama_bahan_bakar = $this->request->getPost('nama_bahan_bakar');

        $validasi = [
            'nama_bahan_bakar' => $nama_bahan_bakar,
        ];

        if ($this->validation->run($validasi, 'insertBBM') == false) {
            $data = [
                'title' => 'Manajemen Proyek',
                'validation' => $this->validation->getErrors(),
            ];
            return view('lapangan/bbm/bbm_add', $data);
        } else {
            $insert = [
                'nama_bahan_bakar' => $nama_bahan_bakar,
            ];
            $save = $this->bbm->save($insert);
            if ($save) {
                session()->setFlashdata('success', 'Tambah bahan bakar berhasil');
                return redirect()->to(base_url('dashboard/bbm'));
            } else {
                session()->setFlashdata('errors', 'Tambah bahan bakar gagal');
                return redirect()->to(base_url('dashboard/bbm'));
            }
        }
    }

    public function bbm_edit($id)
    {
        $data = [
            'title' => 'Manajemen Proyek',
            'data' => $this->bbm->find(dekrip($id)),
            'id' => $id,
        ];
        return view('lapangan/bbm/bbm_edit', $data);

    }

    public function bbm_update()
    {
        $dekrip = $this->request->getPost('id');
        $nama_material = $this->request->getPost('nama_material');
        $id = dekrip($dekrip);

        $validasi = [
            'nama_material' => $nama_material,
        ];

        if ($this->validation->run($validasi, 'insertBBM') == false) {
            $data = [
                'title' => 'Manajemen Proyek',
                'validation' => $this->validation->getErrors(),
                'data' => $this->bbm->find($id),
                'id' => $dekrip,
            ];
            return view('lapangan/bbm/bbm_edit', $data);
        } else {
            $update = [
                'nama_material' => $nama_material,
            ];
            $update_bbm = $this->bbm->where('id', $id)->set($update)->update();
            if ($update_bbm) {
                session()->setFlashdata('success', 'Update bahan bakar berhasil');
                return redirect()->to(base_url('dashboard/bbm'));
            } else {
                session()->setFlashdata('errors', 'Update bahan bakar gagal');
                return redirect()->to(base_url('dashboard/bbm'));
            }
        }
    }

    public function bbm_delete()
    {
        $input = $this->request->getRawInput();
        $id_en = $input['id'];
        $id = dekrip($id_en);
        $delete = $this->bbm->delete($id);
        if ($delete) {
            $response = [
                'status' => 201,
                'message' => 'Data bahan bakar berhasil dihapus',
            ];
        } else {
            $response = [
                'status' => 500,
                'message' => 'Data bahan bakar gagal dihapus',
            ];
        }

        return $this->response->setJSON($response);
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url());
    }

}