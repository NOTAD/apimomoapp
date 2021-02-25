<?php
/**
 * @package NOTAD API MOMO
 * @author  LÊ HỒNG SƠN : 0387654818
 * @copyright   Copyright (c) 2021, notad.net
 * @link    https://notad.net
 * @since   Version 1.0
 * @method Sell Api Lịch sử giao dịch VCB, TCB 0387654818 
 */
include ('class.momo.php');

if (isset($_GET['tranid'])) {
    $tranid = $_GET['tranid'];

    $sql = "SELECT * FROM `api_momo` WHERE `tranId` = '".$tranid."' ";
    if ($db->query($sql) == TRUE) {
        $sql = "SELECT * FROM `api_momo` WHERE `tranId` = '".$tranid."' ";
        $data = $db->query($sql);
        foreach ($data as $value) {   
        $array = [
            'tranid' => $value['tranId'],
            'sdt' => $value['partnerId'],
            'name' => $value['partnerName'],
            'amount' => $value['amount'],
            'comment' => $value['comment'],
            'time' => $value['time']
            ];
            echo json_encode($array);
            }

    } else {
        $array = [
            'msg' => 'key hoặc tranid không tồn tại',
            ];
        echo json_encode($array);
    }
} else {
    $array = [
        'msg' => 'lỗi',
        ];
    echo json_encode($array);
    }
?>