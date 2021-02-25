
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
error_reporting(0);
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=Edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nhìn Gì Mà Nhìn</title>
    <meta http-equiv="refresh" content="60">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.1/css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.bootstrap4.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.bootstrap4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.colVis.min.js"></script>
    <style type="text/css">
    body{font-family:Baomoi!important}h1,h2,h3,h4,h5,p{font-family:Baomoi!important}@font-face{font-family:Baomoi;src:url(https://baomoi-static.zadn.vn/mobile/styles/fonts/baomoi/2.0.2/baomoi-light-2.0.2.woff2) format("woff2"),url(https://baomoi-static.zadn.vn/mobile/styles/fonts/baomoi/2.0.2/baomoi-light-2.0.2.woff) format("woff")}@font-face{font-family:Baomoi;src:url(https://baomoi-static.zadn.vn/mobile/styles/fonts/baomoi/2.0.2/baomoi-light-italic-2.0.2.woff2) format("woff2"),url(https://baomoi-static.zadn.vn/mobile/styles/fonts/baomoi/2.0.2/baomoi-light-italic-2.0.2.woff) format("woff");font-style:italic}@font-face{font-family:Baomoi;src:url(https://baomoi-static.zadn.vn/mobile/styles/fonts/baomoi/2.0.2/baomoi-regular-2.0.2.woff2) format("woff2"),url(https://baomoi-static.zadn.vn/mobile/styles/fonts/baomoi/2.0.2/baomoi-regular-2.0.2.woff) format("woff");font-weight:700}@font-face{font-family:Baomoi;src:url(https://baomoi-static.zadn.vn/mobile/styles/fonts/baomoi/2.0.2/baomoi-regular-2.0.2.woff2) format("woff2"),url(https://baomoi-static.zadn.vn/mobile/styles/fonts/baomoi/2.0.2/baomoi-regular-2.0.2.woff) format("woff")}:after,:before{box-sizing:border-box}a{color:#337ab7;text-decoration:none}i{margin-bottom:4px}.btn{display:inline-block;font-size:14px;font-weight:400;line-height:1.42857143;text-align:center;white-space:nowrap;vertical-align:middle;cursor:pointer;user-select:none;background-image:none;border:1px solid transparent;border-radius:4px}.btn-app{color:#fff;box-shadow:none;border-radius:3px;position:relative;padding:10px 15px;margin:0;min-width:60px;max-width:80px;text-align:center;border:1px solid #ddd;background-color:#f4f4f4;font-size:12px;transition:all .2s;background-color:#4682b4!important}.btn-app>.fa,.btn-app>.glyphicon,.btn-app>.ion{font-size:30px;display:block}.btn-app:hover{border-color:#aaa;transform:scale(1.1)}.pdf{background-color:#dc2f2f!important}.excel{background-color:#3ca23c!important}.csv{background-color:#e86c3a!important}.imprimir{background-color:#8766b1!important}.selectTable{height:40px;float:right}div.dataTables_wrapper div.dataTables_filter{text-align:left;margin-top:15px}.btn-secondary{color:#fff;background-color:#4682b4;border-color:#4682b4}.btn-secondary:hover{color:#fff;background-color:#315f86;border-color:#545b62}.titulo-tabla{color:#606263;text-align:center;margin-top:15px;margin-bottom:15px;font-weight:700}.inline{display:inline-block;padding:0}
    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h3 class="titulo-tabla">MOMO Transaction Result - Working!<br/>Bạn có thể chuyển tiền về <?php echo '088.999.3605'?> và F5 trang để thử</h3>
                <table id="example" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Thời gian</th>
                            <th>Loại giao dịch</th>
                            <th>Mã giao dịch</th>
                            <th>Số điện thoại</th>
                            <th>Tên</th>
                            <th>Tiền</th>
                            <th>Lời nhắn</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $res = $db->getObject('api_momo');
                        ?>
                        <tr>
                        <?php
                        foreach ($res as $row) {
                        ?>
                            <td><?php echo date('d/m/Y H:i:s', $row->time); ?></td>
                            <td><?php if ($row->io == '-1') { echo 'Chuyển Tiền'; } else { echo 'Nhận tiền'; } ?></td>
                            <?php $subTranId = substr($row->tranId,-3); ?>
                            <!--<td><?php echo $row->tranId; ?></td>-->
                            <td><?php echo "*******" . $subTranId; ?></td>
                            <td><?php echo $row->partnerId; ?></td>
                            <td><?php echo $row->partnerName; ?></td>
                            <td><?php echo $row->amount; ?></td>
                            <td><?php echo $row->comment; ?></td>
                        </tr> 
                           <?php } ?> 
                                              
                     </tbody>
                        </table>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {
                 var table = $('#example').DataTable( {
                 "order": [[ 0, "desc" ]],
                 "lengthMenu": [[10,50,100, -1],[10,50,100,"Tất cả"]],
                 "language": {
                   "search": "Tìm gì cũng được",
                   "paginate": {
                       "first": "Về Đầu",
                       "last": "Về Cuối",
                       "next": "Tiến",
                       "previous": "Lùi"
                   },
                   "info": "Hiển thị _START_ đến _END_ của _TOTAL_ mục",
                   "infoEmpty": "Hiển thị 0 đến 0 của 0 mục",
                   "lengthMenu": "Hiển thị _MENU_ mục",
                   "loadingRecords": "Đang tải...",
                   "emptyTable": "Không có gì để hiển thị",
                 },
                 dom: 'Bfrt<"col-md-6 inline"i><"col-md-6 inline"p>',   
                 buttons: {
                   dom: {
                     container:{
                       tag:'div',
                       className:'flexcontent'
                     },
                     buttonLiner: {
                       tag: null
                     }
                   },
                   buttons: [
                            {
                                 extend:    'copyHtml5',
                                 text:      '<i class="fa fa-clipboard"></i>Copy',
                                 title:'Admin result data copy',
                                 titleAttr: 'Copiar',
                                 className: 'btn btn-app export barras',
                                 exportOptions: {
                                     columns: [ 0, 1 ]
                                 }
                             },
                             {
                                 extend:    'excelHtml5',
                                 text:      '<i class="fa fa-file-excel-o"></i>Excel',
                                 title:'Admin result data excel',
                                 titleAttr: 'Excel',
                                 className: 'btn btn-app export excel',
                                 exportOptions: {
                                     columns: [ 0, 1 ]
                                 },
                             },
                             {
                                 extend:    'pageLength',
                                 titleAttr: 'Hiển thị',
                                 className: 'selectTable'
                             }
                         ]
                 }
                 }); 
                 } );
    </script>
</body>

</html>