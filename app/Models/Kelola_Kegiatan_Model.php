<?php
namespace App\Models;

use CodeIgniter\Model;

class Kelola_Kegiatan_Model extends Model
{

    protected $table = 'kelola_kegiatan';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['id_proyek', 'nama_kegiatan', 'tgl_mulai', 'tgl_selesai', 'progress'];
    protected $useSoftDeletes = true;
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    protected $column_order = array('id_proyek');
    protected $column_search = array('nama_kegiatan');
    protected $order = array('kelola_kegiatan.id' => 'asc');
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

    private function _get_datatables_query($id)
    {
        $i = 0;
        $this->dt->where(['kelola_kegiatan.deleted_at' => null, 'kelola_kegiatan.id_proyek' => $id]);
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

    public function get_datatables($id)
    {
        $this->_get_datatables_query($id);
        if ($this->request->getPost('length') != -1) {
            $this->dt->limit($this->request->getPost('length'), $this->request->getPost('start'));
        }

        $query = $this->dt->get();
        return $query->getResult();
    }

    public function count_filtered($id)
    {
        $this->_get_datatables_query($id);
        return $this->dt->countAllResults();
    }

    public function count_all($id)
    {
        $tbl_storage = $this->db->table($this->table)->where(['deleted_at' => null, 'id_proyek' => $id]);
        return $tbl_storage->countAllResults();
    }
}