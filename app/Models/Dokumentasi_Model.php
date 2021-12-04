<?php
namespace App\Models;

use CodeIgniter\Model;

class Dokumentasi_Model extends Model
{
    protected $table = 'kegiatan';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['id_proyek',  'id_kegiatan', 'jenis_kegiatan', 'tgl_mulai', 'tgl_selesai', 'dokumentasi', 'progress_proyek', 'status_proyek'];
    protected $useSoftDeletes = true;
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    protected $column_order = array('kegiatan.id', 'proyek.nama_proyek');
    protected $column_search = array('proyek.nama_proyek');
    protected $order = array('kegiatan.id' => 'asc');
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

    private function _get_datatables_query($id=null)
    {
        $i = 0;
        $this->dt->select('kegiatan.*,proyek.nama_proyek')->join('proyek', 'proyek.id=kegiatan.id_proyek', 'left')->where('kegiatan.deleted_at', null);
        if(!is_null($id)){
            $this->dt->where('kegiatan.id_proyek', $id);
        }
        foreach ($this->column_search as $item) {
            if ($this->request->getPost('search')['value']) {
                if ($i === 0) {
                    $this->dt->groupStart();
                    $this->dt->like($item, $this->request->getPost('search')['value']);
                } else {
                    $this->dt->orLike($item, $this->request->getPost('search')['value']);
                }
                if (count($this->column_search) - 1 == $i) {
                    $this->dt->groupEnd();
                }

            }
            $i++;
        }

        if ($this->request->getPost('order')) {
            $this->dt->orderBy($this->column_order[$this->request->getPost('order')['0']['column']], $this->request->getPost('order')['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->dt->orderBy(key($order), $order[key($order)]);
        }
    }

    public function get_datatables($id=null)
    {
        $this->_get_datatables_query($id);
        if ($this->request->getPost('length') != -1) {
            $this->dt->limit($this->request->getPost('length'), $this->request->getPost('start'));
        }

        $query = $this->dt->get();
        return $query->getResult();
    }

    public function count_filtered($id=null)
    {
        $this->_get_datatables_query($id);
        return $this->dt->countAllResults();
    }

    public function count_all($id=null)
    {
        if(is_null($id)){
            $tbl_storage = $this->db->table($this->table)->where('deleted_at', null);
        }else{
            $tbl_storage = $this->db->table($this->table)->where(['deleted_at'=> null, 'id_proyek'=>$id]);
        }
        return $tbl_storage->countAllResults();
    }
    public function selectData()
    {
        return $this->db->query("SELECT
            `kegiatan`.*,
            `proyek`.`nama_proyek`
        FROM
            `kegiatan`
            LEFT JOIN `proyek` ON `proyek`.`kegiatan`.`id_proyek` = `proyek`.`id`")->getResult();
    }
    
    public function getProgress($id)
    {
        $progress = 0;
        $kegiatans = $this->db->table('kelola_kegiatan')->where(['id_proyek'=>$id,'deleted_at'=> null])->get()->getResult();
        foreach ($kegiatans as $key => $kegiatan) {
            $dok=  $this->dt->where(['id_kegiatan'=>$kegiatan->id, 'deleted_at'=> null])->get()->getRowObject();
            if(!is_null($dok)){
                $progress += $dok->progress_proyek*($kegiatan->progress/100);
            }
        }
        return $progress;
    }
}