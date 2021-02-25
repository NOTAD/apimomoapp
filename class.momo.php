<?php
/**
 * @package NOTAD API MOMO
 * @author  LÊ HỒNG SƠN : 0387654818
 * @copyright   Copyright (c) 2021, notad.net
 * @link    https://notad.net
 * @since   Version 1.0
 * @method Sell Api Lịch sử giao dịch VCB, TCB 0387654818 
 */
require_once ('Database.php');
$momo = new Momo();
$db = new Database();
//error_reporting(1);

$result = $momo->history(5);// Thay 1 bằng số ngày muốn. vid dụ 10 là 10 ngày.
  //print_r($result);
//----------------------------------------------------
    $jsonDecode = json_decode($result);
    $json = ($jsonDecode->momoMsg->tranList);
 //   print_r($json) ;
foreach ($json as $data) {
          $sql = "SELECT * FROM `api_momo` Where `tranId` = '".$data->tranId . "' ";
          if ($db->query($sql) == 0) {
              
            $data_insert = array(
                'io' => $data->io,
                'tranId' => $data->tranId,
                'partnerId' => $data->partnerId,    
                'partnerName' => $data->partnerName,
                'amount' => $data->amount,
                'comment' => $data->comment,
                'time' => $data->finishTime / 1000 ,
                'congtien' => -1 ,
                'sapco' => -1 ,
                'sapco1' => -1
               );

          if ($data_insert['io'] == 1 or $data_insert['io'] == -1) {
            $db->insert('api_momo',$data_insert);

           require('tele.php');
        }
        }
        }