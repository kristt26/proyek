<?php
namespace App\Models;

use CodeIgniter\Model;

class Proyek_Model extends Model
{

    protected $table = 'proyek';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['nama_proyek', 'lokasi', 'tgl_mulai', 'tgl_selesai', 'konsultan_pengawas', 'id_pegawai', 'nilai_kontrak'];
    protected $useSoftDeletes = true;
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    protected $column_order = array('id', 'nama_proyek', 'nilai_kontrak');
    protected $column_search = array('nama_proyek', 'lokasi');
    protected $order = array('id' => 'asc');
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

    private function _get_datatables_query()
    {
        $i = 0;
        $this->dt->select("proyek.*, pegawai.nama, (SELECT sum(jumlah) FROM operasional where operasional.id_proyek=proyek.id AND isnull(operasional.deleted_at)) AS terapan_anggaran ")->join('pegawai', 'proyek.id_pegawai=pegawai.id');
        $this->dt->where('proyek.deleted_at', null);
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
            $a = key($order);
            $this->dt->orderBy(key($order), $order[key($order)]);
        }
    }

    public function get_datatables()
    {
        $this->_get_datatables_query();
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