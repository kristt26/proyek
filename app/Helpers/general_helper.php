<?php

 function generate_token(){
 	$token = openssl_random_pseudo_bytes(16);
	//Convert the binary data into hexadecimal representation.
	$convert = bin2hex($token);
	return $convert;
 }

 function array_push_assoc($array, $key, $value){
	$array[$key] = $value;
	return $array;
}

function random_string($length = 7) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@*#';
    $sandi = '';
    $characterListLength = mb_strlen($characters, '8bit') - 1;
    foreach(range(1, $length) as $i){
        $sandi .= $characters[random_int(0, $characterListLength)];
    }
    $n = count(array_unique(str_split($sandi)));
    if($n != $length){
        while($n < $length){
            random_string();
        }
    } 
    return $sandi;
 }

 function enkrip($data){
      return base64_encode($data.'-project');
 }

 function dekrip($data){
      $dekrip = base64_decode($data);
      $pecah = explode('-', $dekrip); 
      return $pecah[0];
 }

 function rupiah($angka){
    $hasil_rupiah = number_format($angka,0,',','.');
    return $hasil_rupiah;
}
?>