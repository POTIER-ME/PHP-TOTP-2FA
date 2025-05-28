<?php
/*
 Copyright (c) 2025 - Nicolas POTIER
    *** https://soft.potier.me

 Simple use of 2FA (TOTP) on PHP website

 This project is licensed under the MIT (Massachusetts Institute of Technology) License
*/



/*
function to permit to generate OTP key
 $length : number of chars to use (use 1 of theses : 16/20/24/28/32/36/40)
*/
function p2fa_generate($length) {
$g_return=""; //will contain the answer (BASE32 format)
$g_length=explode(",","16,20,24,28,32,36,40"); //only this number can be accepted to be fully compliant
$g_chars="ABCDEFGHIJKLMNOPQRSTUVWXYZ234567"; //for OTP, only thess chars can be used

 if (in_array($length, $g_length)) {
  //we add char 1 by 1
  for ($i=0; $i<$length; $i++) {$g_return.=$car_ok[rand(0, strlen($g_chars)-1)];}
 }

return $g_return;
}

/*
function to permit to decode BASE32 data (OTP key is a BASE32 data)
 $data : the base32 data
*/
function p2fa_b32_decode($data) {
$d_return=""; //will contain the answer (byte format)
$d_chars="ABCDEFGHIJKLMNOPQRSTUVWXYZ234567"; //for BASE32/OTP, only thess chars can be used
$d_binary=""; //needed to manage the binary

 //we convert $data into an array, then convert into binary (5 bits for each binary)
 foreach (str_split($data) as $d_char) {
 $pos=strpos($d_chars, $d_char);
 $d_binary.=str_pad(decbin($pos), 5, "0", STR_PAD_LEFT);
 }

 //we convert $d_binary into an array of 8 bits, then convert it into integer
 foreach (str_split($d_binary, 8) as $d_byte) {
  if (strlen($d_byte)<8) continue;
 $d_return.=chr(bindec($d_byte));
 }

return $d_return;
}


/*
function to permit to retrieve 2FA code, here we will send back previous, actual and next 2FA code
 $secret : the TOTP key
*/
function p2fa_getTOTP($secret) {
$g_return=array(); //will contain the answer (array format)
$g_times=explode(",","-30,0,30"); //contain all theses 2FA codes (previous, actual and next)

$g_secret=p2fa_b32_decode($secret); //convert $secret
 foreach ($g_times as $g_time) {
 $g_time_actual=floor((time()+($g_time))/30);
 $g_time_bin=pack('N*', 0).pack('N*', $g_time_actual); // 64-bit timestamp into binary
 $g_hash=hash_hmac('sha1', $g_time_bin, $g_secret, true); //generate hash key
 //need to have some octet from $g_hash
 $g_offset=ord($g_hash[19]) & 0xf;
 $g_code=(
  ((ord($g_hash[$g_offset])&0x7f)<<24) |
  ((ord($g_hash[$g_offset+1])&0xff)<<16) |
  ((ord($g_hash[$g_offset+2])&0xff)<<8) |
  (ord($g_hash[$g_offset+3])&0xff))%1000000;
 $g_return[$g_time]=str_pad($g_code, 6, '0', STR_PAD_LEFT); //we fill data into $g_return
 }

return $g_return;
}




/*
//demo mode :
$my_secret=p2fa_generate(32); //generate 32 chars key
$my_valid=p2fa_getTOTP($my_secret); //retrieve previous, actual and next correct 2FA code
$my_test="123456"; //2FA code I would like to test

 //check if $my_test is in $my_valid
 if (in_array($my_test, $my_valid)) {
 echo "GOOD !";
 }
 else {
 echo "BAD !";
 }
*/
?>
