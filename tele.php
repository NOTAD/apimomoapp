<?php
/**
 * @package NOTAD API MOMO
 * @author  LÊ HỒNG SƠN : 0387654818
 * @copyright   Copyright (c) 2021, notad.net
 * @link    https://notad.net
 * @since   Version 1.0
 * @method Sell Api Lịch sử giao dịch VCB, TCB 0387654818 
 */
$sdtkh =$data->partnerId;
$tenkh = $data->partnerName;
$idd = $data->tranId;
$mountt = number_format($data->amount);
$time = time();
$msg = date('d/m/Y H:i:s', $data->finishTime / 1000);
$noidung = $data->comment;
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.telegram.org/bot<token>?chat_id=123123&text=Nhật Kí Giao Dịch:%0D%0AMã giao dịch: $idd%0D%0AKhách hàng: $sdtkh%0D%0ASố tiền: $mountt%0D%0ALời nhắn: $noidung%0D%0ATime: $msg%0D%0AMomo: $somomo",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
));
$response = curl_exec($curl);
curl_close($curl);
?>