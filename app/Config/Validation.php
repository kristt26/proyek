<?php

namespace Config;

use CodeIgniter\Validation\CreditCardRules;
use CodeIgniter\Validation\FileRules;
use CodeIgniter\Validation\FormatRules;
use CodeIgniter\Validation\Rules;

class Validation
{
	//--------------------------------------------------------------------
	// Setup
	//--------------------------------------------------------------------

	/**
	 * Stores the classes that contain the
	 * rules that are available.
	 *
	 * @var string[]
	 */
	public $ruleSets = [
		Rules::class,
		FormatRules::class,
		FileRules::class,
		CreditCardRules::class,
	];

	/**
	 * Specifies the views that are used to display the
	 * errors.
	 *
	 * @var array<string, string>
	 */
	public $templates = [
		'list'   => 'CodeIgniter\Validation\Views\list',
		'single' => 'CodeIgniter\Validation\Views\single',
	];

	//--------------------------------------------------------------------
	// Rules
	//--------------------------------------------------------------------

	public $loginUser = [
        'username' => [
            'label'  => 'Username',
            'rules'  => 'required'
        ],
        'password' => [
            'label'  => 'Password',
            'rules'  => 'required'
        ]
    ];

    public $loginUser_errors = [
        'username' => [
            'required' => '{field} wajib diisi'
        ],
        'password' => [
            'required' => '{field} wajib diisi'
        ]
    ];

    public $resendPassword = [
        'email' => [
            'label'  => 'Email',
            'rules'  => 'required|valid_email'
        ]
    ];

    public $resendPassword_errors = [
        'email' => [
            'required' => '{field} wajib diisi',
            'valid_email' => 'format {field} salah'
        ]
    ];

    public $regisPegawai = [
        'nama'      => [
        	'label'  => 'Nama',
            'rules'  => 'required'
        ],
        'alamat'      => [
            'label'  => 'Alamat',
            'rules'  => 'required'
        ],
        'tempat_lahir'      => [
            'label'  => 'Tempat Lahir',
            'rules'  => 'required'
        ],
        'tgl_lahir'      => [
            'label'  => 'Tanggal Lahir',
            'rules'  => 'required'
        ],
        'email'      => [
            'label'  => 'Email',
            'rules'  => 'required|valid_email'
        ],
        'no_telp'      => [
            'label'  => 'No. Telp',
            'rules'  => 'required'
        ]
    ];

    public $regisPegawai_errors = [
    	'nama'      => [
            'required' => '{field} wajib diisi'
        ],
        'alamat'      => [
            'required' => '{field} wajib diisi'
        ],
        'tempat_lahir'      => [
            'required' => '{field} wajib diisi'
        ],
        'tgl_lahir'      => [
            'required' => '{field} wajib diisi'
        ],
        'email'      => [
            'required' => '{field} wajib diisi',
            'valid_email' => '{field} tidak valid'
        ],
        'no_telp'      => [
            'required' => '{field} wajib diisi'
        ]
    ];

    public $insertCabang = [
        'nama_cabang'       => [
            'label'  => 'Nama Cabang',
            'rules'  => 'required'
        ],
        'alamat_cabang'      => [
            'label'  => 'Alamat Cabang',
            'rules'  => 'required'
        ],
        'email_cabang'      => [
            'label'  => 'Email Cabang',
            'rules'  => 'required|valid_email'
        ],
        'telp_cabang'      => [
            'label'  => 'Telp Cabang',
            'rules'  => 'required'
        ]
    ];

    public $insertCabang_errors = [
        'nama_cabang'    => [
            'required' => '{field} wajib diisi'
        ],
        'alamat_cabang'    => [
            'required' => '{field} wajib diisi'
        ],
        'email_cabang'    => [
            'required' => '{field} wajib diisi',
            'valid_email' => '{field} tidak valid',
        ],
         'telp_cabang'    => [
            'required' => '{field} wajib diisi'
        ]
    ];

    public $insertKendaraan = [
        'jenis_kendaraan'       => [
            'label'  => 'Jenis Kendaraan',
            'rules'  => 'required'
        ],
        'nomor_polisi'      => [
            'label'  => 'Nomor Polisi',
            'rules'  => 'required'
        ],
        'nomor_mesin'      => [
            'label'  => 'Nomor Mesin',
            'rules'  => 'required'
        ]
    ];

    public $insertKendaraan_errors = [
        'jenis_kendaraan'    => [
            'required' => '{field} wajib diisi'
        ],
        'nomor_polisi'    => [
            'required' => '{field} wajib diisi'
        ],
        'nomor_mesin'    => [
            'required' => '{field} wajib diisi'
        ]
    ];

    public $insertProyek = [
        'nama_proyek'       => [
            'label'  => 'Nama Proyek',
            'rules'  => 'required'
        ],
        'lokasi'      => [
            'label'  => 'Nomor Polisi',
            'rules'  => 'required'
        ],
        'tgl_mulai'      => [
            'label'  => 'Tanggal Mulai',
            'rules'  => 'required'
        ],
        'tgl_selesai'      => [
            'label'  => 'Tanggal Selesai',
            'rules'  => 'required'
        ],
        'konsultan_pengawas'      => [
            'label'  => 'Konsultan Pengawas',
            'rules'  => 'required'
        ],
        'id_pegawai'      => [
            'label'  => 'Penanggung Jawab Lapangan',
            'rules'  => 'required'
        ],
        'nilai_kontrak'      => [
            'label'  => 'Nilai Kontrak',
            'rules'  => 'required'
        ]
    ];

    public $insertProyek_errors = [
        'nama_proyek'    => [
            'required' => '{field} wajib diisi'
        ],
        'lokasi'    => [
            'required' => '{field} wajib diisi'
        ],
        'tgl_mulai'    => [
            'required' => '{field} wajib diisi'
        ],
        'tgl_selesai'    => [
            'required' => '{field} wajib diisi'
        ],
        'konsultan_pengawas'    => [
            'required' => '{field} wajib diisi'
        ],
        'id_pegawai'    => [
            'required' => '{field} wajib diisi'
        ],
        'nilai_kontrak'    => [
            'required' => '{field} wajib diisi'
        ]
    ];

    public $insertKegiatan = [
        'id_proyek'       => [
            'label'  => 'Proyek',
            'rules'  => 'required'
        ],
        'tgl_mulai'      => [
            'label'  => 'Tanggal Mulai',
            'rules'  => 'required'
        ],
        'tgl_selesai'      => [
            'label'  => 'Tanggal Selesai',
            'rules'  => 'required'
        ]
    ];

    public $insertKegiatan_errors = [
        'id_proyek'    => [
            'required' => '{field} wajib diisi'
        ],
        'tgl_mulai'    => [
            'required' => '{field} wajib diisi'
        ],
        'tgl_selesai'    => [
            'required' => '{field} wajib diisi'
        ]
    ];
    public $insertKelolaKegiatan = [
        'id_proyek'       => [
            'label'  => 'Proyek',
            'rules'  => 'required'
        ],
        'tgl_mulai'      => [
            'label'  => 'Tanggal Mulai',
            'rules'  => 'required'
        ],
        'tgl_selesai'      => [
            'label'  => 'Tanggal Selesai',
            'rules'  => 'required'
        ]
    ];

    public $insertKelolaKegiatan_errors = [
        'id_proyek'    => [
            'required' => '{field} wajib diisi'
        ],
        'tgl_mulai'    => [
            'required' => '{field} wajib diisi'
        ],
        'tgl_selesai'    => [
            'required' => '{field} wajib diisi'
        ]
    ];

    public $insertUsers = [
        'id_pegawai'       => [
            'label'  => 'Pegawai',
            'rules'  => 'required'
        ],
        'hak_akses'      => [
            'label'  => 'Hak Akses',
            'rules'  => 'required'
        ],
        'username'      => [
            'label'  => 'Username',
            'rules'  => 'required|is_unique[users.username]'
        ],
        'password'      => [
            'label'  => 'Password',
            'rules'  => 'required|min_length[5]'
        ],
        'confirm_password'      => [
            'label'  => 'Konfirmasi Password',
            'rules'  => 'required|matches[password]'
        ]
    ];

    public $insertUsers_errors = [
        'id_pegawai'    => [
            'required' => '{field} wajib diisi'
        ],
        'hak_akses'    => [
            'required' => '{field} wajib diisi'
        ],
        'username'    => [
            'required' => '{field} wajib diisi',
            'is_unique' => '{field} sudah terdaftar di database'
        ],
        'password'    => [
            'required' => '{field} wajib diisi',
            'min_length' => '{field} minimal 5 karakter'
        ],
        'confirm_password'    => [
            'required' => '{field} wajib diisi',
            'matches' => 'Password tidak cocok'
        ]
    ];

    public $updateUsers = [
        'hak_akses'      => [
            'label'  => 'Hak Akses',
            'rules'  => 'required'
        ],
        'username'      => [
            'label'  => 'Username',
            'rules'  => 'required'
        ]
    ];

    public $updateUsers_errors = [
        'hak_akses'    => [
            'required' => '{field} wajib diisi'
        ],
        'username'    => [
            'required' => '{field} wajib diisi'
        ]
    ];

    public $insertOperasional = [
        'id_proyek'       => [
            'label'  => 'Proyek',
            'rules'  => 'required'
        ],
        'tgl_kegiatan'      => [
            'label'  => 'Tanggal Kegiatan',
            'rules'  => 'required'
        ],
        'keterangan'      => [
            'label'  => 'Keterangan',
            'rules'  => 'required'
        ],
        // 'jenis_transaksi'      => [
        //     'label'  => 'Jenis Transaksi',
        //     'rules'  => 'required'
        // ],
        'jumlah'      => [
            'label'  => 'Jumlah',
            'rules'  => 'required'
        ]
    ];

    public $insertOperasional_errors = [
        'id_proyek'    => [
            'required' => '{field} wajib diisi'
        ],
        'tgl_kegiatan'    => [
            'required' => '{field} wajib diisi'
        ],
        'keterangan'    => [
            'required' => '{field} wajib diisi'
        ],
        // 'jenis_transaksi'    => [
        //     'required' => '{field} wajib diisi'
        // ],
        'jumlah'    => [
            'required' => '{field} wajib diisi'
        ]
    ];

    public $insertTransaksi = [
        'tgl_transaksi'      => [
            'label'  => 'Tanggal Transaksi',
            'rules'  => 'required'
        ],
        'keterangan'      => [
            'label'  => 'Keterangan',
            'rules'  => 'required'
        ],
        'jumlah'      => [
            'label'  => 'Jumlah',
            'rules'  => 'required'
        ]
    ];

    public $insertTransaksi_errors = [
        'tgl_transaksi'    => [
            'required' => '{field} wajib diisi'
        ],
        'keterangan'    => [
            'required' => '{field} wajib diisi'
        ],
        'jumlah'    => [
            'required' => '{field} wajib diisi'
        ]
    ];

    public $insertDana = [
        'id_proyek'      => [
            'label'  => 'Proyek',
            'rules'  => 'required'
        ],
        'tgl_transaksi'      => [
            'label'  => 'Tanggal Transaksi',
            'rules'  => 'required'
        ],
        'keterangan'      => [
            'label'  => 'Keterangan',
            'rules'  => 'required'
        ],
        'jumlah'      => [
            'label'  => 'Jumlah',
            'rules'  => 'required'
        ]
    ];

    public $insertDana_errors = [
        'id_proyek'    => [
            'required' => '{field} wajib diisi'
        ],
        'tgl_transaksi'    => [
            'required' => '{field} wajib diisi'
        ],
        'keterangan'    => [
            'required' => '{field} wajib diisi'
        ],
        'jumlah'    => [
            'required' => '{field} wajib diisi'
        ]
    ];

    public $insertDokumentasi = [
        'nama_dokumen'      => [
            'label'  => 'Nama Dokumen',
            'rules'  => 'required'
        ],
        'tgl'      => [
            'label'  => 'Tanggal Kegiatan',
            'rules'  => 'required'
        ],
        'keterangan' => [
            'label'  => 'Keterangan',
            'rules'  => 'required'
        ],
        'file'      => [
            'label'  => 'File',
            'rules'  => 'uploaded[file]|mime_in[file,image/jpg,image/jpeg,image/gif,image/png]|max_size[file,5000]'
        ]
    ];

    public $insertDokumentasi_errors = [
        'nama_dokumen'    => [
            'required' => '{field} wajib diisi'
        ],
        'tgl'    => [
            'required' => '{field} wajib diisi'
        ],
        'keterangan'    => [
            'required' => '{field} wajib diisi'
        ],  
        'file' => [
            'uploaded' => '{field} gagal diupload',
            'mime_in' => '{field} bukan format gambar',
            'max_size' => 'Ukuran file {field} tidak boleh lebih dari 5 MB'
        ]
    ];

    public $insertPenggunaan = [
        'id_proyek'      => [
            'label'  => 'Proyek',
            'rules'  => 'required'
        ],
        'id_kendaraan'      => [
            'label'  => 'Jenis Kendaraan',
            'rules'  => 'required'
        ],
        'tgl_kegiatan' => [
            'label'  => 'Tanggal Kegiatan',
            'rules'  => 'required'
        ],
        'pemakaian_bbm'      => [
            'label'  => 'Pemakaian Bahan Bakar',
            'rules'  => 'required'
        ],
        'id_bahan_bakar'      => [
            'label'  => 'Jenis Bahan Bakar',
            'rules'  => 'required'
        ],
        'jumlah_rpm'      => [
            'label'  => 'Jumlah RPM',
            'rules'  => 'required'
        ]
    ];

    public $insertPenggunaan_errors = [
        'id_proyek'    => [
            'required' => '{field} wajib diisi'
        ],
        'id_kendaraan'    => [
            'required' => '{field} wajib diisi'
        ],
        'tgl_kegiatan'    => [
            'required' => '{field} wajib diisi'
        ],
        'pemakaian_bbm'    => [
            'required' => '{field} wajib diisi'
        ],
        'id_bahan_bakar'    => [
            'required' => '{field} wajib diisi'
        ],
        'jumlah_rpm'    => [
            'required' => '{field} wajib diisi'
        ]
    ];

    public $insertPemakaianBBM = [
        'id_kendaraan'      => [
            'label'  => 'Jenis Kendaraan',
            'rules'  => 'required'
        ],
        'jumlah_pemakaian'      => [
            'label'  => 'Jumlah Pemakaian',
            'rules'  => 'required'
        ],
        'id_bahan_bakar' => [
            'label'  => 'Jenis Bahan Bakar',
            'rules'  => 'required'
        ]
    ];

    public $insertPemakaianBBM_errors = [
        'id_kendaraan'    => [
            'required' => '{field} wajib diisi'
        ],
        'jumlah_pemakaian'    => [
            'required' => '{field} wajib diisi'
        ],
        'id_bahan_bakar'    => [
            'required' => '{field} wajib diisi'
        ]
    ];

    public $insertMaterial = [
        'nama_bahan'      => [
            'label'  => 'Nama Bahan',
            'rules'  => 'required'
        ],
        'tgl_penggunaan'      => [
            'label'  => 'Tanggal Pengunaan',
            'rules'  => 'required'
        ],
        'jumlah_pemakaian' => [
            'label'  => 'Jumlah Pemakaian',
            'rules'  => 'required'
        ]
    ];

    public $insertMaterial_errors = [
        'nama_bahan'    => [
            'required' => '{field} wajib diisi'
        ],
        'tgl_penggunaan'    => [
            'required' => '{field} wajib diisi'
        ],
        'jumlah_pemakaian'    => [
            'required' => '{field} wajib diisi'
        ]
    ];

    public $insertBBM = [
        'nama_bahan_bakar'      => [
            'label'  => 'Nama Bahan Bakar',
            'rules'  => 'required'
        ]
    ];

    public $insertBBM_errors = [
        'nama_bahan_bakar'    => [
            'required' => '{field} wajib diisi'
        ]
    ];
    
    public $insertJenisMaterial = [
        'nama_material'      => [
            'label'  => 'Nama Material',
            'rules'  => 'required'
        ],
        'satuan'      => [
            'label'  => 'Satuan Material',
            'rules'  => 'required'
        ]
    ];

    public $insertJenisMaterial_errors = [
        'nama_material'    => [
            'required' => '{field} wajib diisi'
        ],
        'satuan'    => [
            'required' => '{field} wajib diisi'
        ]
    ];

}