<?php
namespace App\Models;

use CodeIgniter\Model;


class PemakaianBBM_Model extends Model {
	
    protected $table = 'pemakaian_bbm';
    protected $primaryKey = 'id';
    protected $returnType     = 'array';
    protected $allowedFields = ['id_proyek', 'id_pemakaian_kendaraan', 'id_kendaraan', 'jumlah_pemakaian', 'id_bahan_bakar', 'jumlah_rpm', 'tanggal_pakai'];
    protected $useSoftDeletes = true;
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $column_order = array('pemakaian_bbm.id','kendaraan.jenis_kendaraan', 'bahan_bakar.nama_bahan_bakar');
    protected $column_search = array('kendaraan.jenis_kendaraan', 'bahan_bakar.nama_bahan_bakar');
    protected $order = array('pemakaian_bbm.id' => 'asc');
    protected $request;
    protected $db;
    protected $dt;


    public function __construct()
    {
       parent::__construct();
       $this->db = db_connect();
       $this->request = \Config\Services::request();
       $this->dt = $this->db->table($this->table);
    }

    private function _get_datatables_query(){
        $i = 0;
        $this->dt->select('pemakaian_bbm.*,kendaraan.jenis_kendaraan,kendaraan.nomor_polisi,bahan_bakar.nama_bahan_bakar, proyek.nama_proyek, kegiatan.jenis_kegiatan, kelola_kegiatan.nama_kegiatan')->join('penggunaan_kendaraan', '`pemakaian_bbm`.`id_pemakaian_kendaraan` =`penggunaan_kendaraan`.`id`', 'left')->join('kegiatan', '`penggunaan_kendaraan`.`id_kegiatan` = `kegiatan`.`id`', 'left')->join('`kelola_kegiatan`', '`kegiatan`.`id_kegiatan` = `kelola_kegiatan`.`id`', 'left')->join('proyek', '`proyek`.`kelola_kegiatan`.`id_proyek` = `proyek`.`id`','left')->join('bahan_bakar', '`proyek`.`pemakaian_bbm`.`id_bahan_bakar` = `bahan_bakar`.`id`', 'left')->join('kendaraan', '`proyek`.`penggunaan_kendaraan`.`id_kendaraan` = `kendaraan`.`id`', 'left')->where('pemakaian_bbm.deleted_at', NULL);
        foreach ($this->column_search as $item){
            if($this->request->getPost('search')['value']){ 
                if($i===0){
                    $this->dt->groupStart();
                    $this->dt->like($item, $this->request->getPost('search')['value']);
                }
                else{
                    $this->dt->orLike($item, $this->request->getPost('search')['value']);
                }
                if(count($this->column_search) - 1 == $i)
                    $this->dt->groupEnd();
            }
            $i++;
        }
         
        if($this->request->getPost('order')){
                $this->dt->orderBy($this->column_order[$this->request->getPost('order')['0']['column']], $this->request->getPost('order')['0']['dir']);
            } 
        else if(isset($this->order)){
            $order = $this->order;
            $this->dt->orderBy(key($order), $order[key($order)]);
        }
    }

    function get_datatables(){
        $this->_get_datatables_query();
        if($this->request->getPost('length') != -1)
        $this->dt->limit($this->request->getPost('length'), $this->request->getPost('start'));
        $query = $this->dt->get();
        return $query->getResult();
    }

    function count_filtered(){
        $this->_get_datatables_query();
        return $this->dt->countAllResults();
    }

    public function count_all(){
        $tbl_storage = $this->db->table($this->table)->where('deleted_at', NULL);
        return $tbl_storage->countAllResults();
    }
    public function getData($id){
        $result = $this->db->query("SELECT
            `penggunaan_kendaraan`.*,
            `kendaraan`.`jenis_kendaraan`,
            `kegiatan`.`jenis_kegiatan`
        FROM
            `penggunaan_kendaraan`
            LEFT JOIN `kendaraan` ON `penggunaan_kendaraan`.`id_kendaraan` =
        `kendaraan`.`id`
            LEFT JOIN `kegiatan` ON `penggunaan_kendaraan`.`id_kegiatan` = `kegiatan`.`id` WHERE penggunaan_kendaraan.id_proyek = '$id' AND penggunaan_kendaraan.deleted_at IS NULL")->getResult();
        return $result;
    }

    
}