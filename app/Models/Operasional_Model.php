<?php
namespace App\Models;

use CodeIgniter\Model;

class Operasional_Model extends Model
{

    protected $table = 'operasional';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['id_proyek', 'tgl_kegiatan', 'keterangan', 'jumlah'];
    protected $useSoftDeletes = true;
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    protected $column_order = array('operasional.id', 'proyek.nama_proyek', 'operasional.tgl_kegiatan');
    protected $column_search = array('proyek.nama_proyek', 'operasional.keterangan');
    protected $order = array('operasional.id' => 'asc');
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

    private function _get_datatables_query($id = null)
    {
        $i = 0;
        $this->dt->select('operasional.*,proyek.nama_proyek')->join('proyek', 'proyek.id=operasional.id_proyek')->where('operasional.deleted_at', null);
        if (!is_null($id)) {
            $this->dt->where('operasional.id_proyek', $id);
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

    public function get_datatables($id = null)
    {
        $this->_get_datatables_query($id);
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

    public function num($id = null)
    {
        $serult = $this->db->query("SELECT SUM(jumlah) AS jumlah FROM operasional WHERE operasional.id_proyek = '$id'")->getRowArray();
        return $serult;
    }
}