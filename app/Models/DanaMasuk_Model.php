<?php
namespace App\Models;

use CodeIgniter\Model;

class DanaMasuk_Model extends Model
{

    protected $table = 'dana_masuk';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['id_proyek', 'tgl_transaksi', 'keterangan', 'jumlah'];
    protected $useSoftDeletes = true;
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    protected $column_order = array('dana_masuk.id', 'proyek.nama_proyek');
    protected $column_search = array('proyek.nama_proyek');
    protected $order = array('dana_masuk.id' => 'asc');
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

    private function _get_datatables_query($id_proyek = null)
    {
        $i = 0;
        $this->dt->select('dana_masuk.*,proyek.nama_proyek')->join('proyek', 'proyek.id=dana_masuk.id_proyek')->where('dana_masuk.deleted_at', null);
        if(!is_null($id_proyek)){
            $this->dt->where("id_proyek", $id_proyek);
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

    public function get_datatables($id_proyek = null)
    {
        $this->_get_datatables_query($id_proyek);
        if ($this->request->getPost('length') != -1) {
            $this->dt->limit($this->request->getPost('length'), $this->request->getPost('start'));
        }
        $query = $this->dt->get();
        return $query->getResult();
    }

    public function count_filtered()
    {
        $this->_get_datatables_query();
        return $this->dt->countAllResults();
    }

    public function count_all()
    {
        $tbl_storage = $this->db->table($this->table)->where('deleted_at', null);
        return $tbl_storage->countAllResults();
    }
}