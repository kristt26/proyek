<?php
namespace App\Libraries;

use CodeIgniter\Model;

class Service_Lib extends Model {

    protected $db;

	public function __construct() {
      $this->db = db_connect();
      $this->email = \Config\Services::email($this->config());
    }

    public function config(){
        $data =  [
                'protocol' => getenv('custome.email.protocol'),
                'SMTPHost' => getenv('custome.email.smtp.host'),
                'SMTPPort' => getenv('custome.email.smtp.port'),
                'SMTPTimeout' => getenv('custome.email.smtp.timeout'),
                'mailType' => getenv('custome.email.mail.type'),
                'userAgent' => getenv('custome.email.user.agent'),
                'SMTPUser' => getenv('custome.email.smtp.user'),
                'SMTPPass' => getenv('custome.email.smtp.password'),
                'newline' => '\r\n',
            ];
        return $data;
    }

    public function sendMail($data=null){
        $to = $data['to'];
        $subject = $data['subject'];
        $message = $data['message'];

        $this->email->setTo($to);
        $this->email->setFrom(getenv('custome.email.from'), 'Layanan Internet');
            
        $this->email->setSubject($subject);
        $this->email->setMessage($message);

        if ($this->email->send()){
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function getNamaProyek($id){
        $nama = '';
        $qry = $this->db->table('proyek')->where('id', $id)->get()->getResultArray();
        if(count($qry) > 0){
            foreach ($qry as $key) {
                $nama = $key['nama_proyek'];  
            }   
        }
        
        return $nama; 
    }
}