<?php
namespace App\Models;

use CodeIgniter\Model;


class PemakaianMaterial_Model extends Model {
	
    protected $table = 'pemakaian_material';
    protected $primaryKey = 'id';
    protected $returnType     = 'array';
    protected $allowedFields = ['id_proyek', 'id_kegiatan', 'id_material', 'tgl_penggunaan', 'jumlah_pemakaian'];
    protected $useSoftDeletes = true;
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $column_order = array('id','nama_material', 'tgl_penggunaan');
    protected $column_search = array('nama_material');
    protected $order = array('pemakaian_material.id' => 'asc');
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
        $this->dt->select('`pemakaian_material`.*, `proyek`.`nama_proyek`, `kegiatan`.`jenis_kegiatan`, material.nama_material')->join('kegiatan', '`pemakaian_material`.`id_kegiatan` = `kegiatan`.`id`', 'left')->join('proyek', '`kegiatan`.`id_proyek` = `proyek`.`id`', 'left')->join("material", "material.id=pemakaian_material.id_material", "left")->where('pemakaian_material.deleted_at', NULL);
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
}