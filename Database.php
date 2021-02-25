<?php
/**
 * @package NOTAD API MOMO
 * @author  LÊ HỒNG SƠN : 0387654818
 * @copyright   Copyright (c) 2021, notad.net
 * @link    https://notad.net
 * @since   Version 1.0
 * @method Sell Api Lịch sử giao dịch VCB, TCB 0387654818 
 */
date_default_timezone_set('Asia/Ho_Chi_Minh');
class Momo{
    private $config;
    private $phone              = ''; //Số điện thoại momo
    private $otp                = ''; //Mã otp lúc đăng nhập vào app
    private $password           = ''; //Pass momo
    private $rkey               = ''; // 20 characters trong public/login
    private $setupKeyEncrypted  = ""; // (*): Xem trong public
    private $imei               = ""; // (*): Xem trong public
    private $token              = ''; // (*): Xem trong public
    private $onesignalToken     = ''; // (*): Xem trong public

    public function __construct(){
        $ohash = hash('sha256', $this->phone . $this->rkey . $this->otp);
        $this->config = [
            'phone'                 => $this->phone, //sdt (*)
            'otp'                   => $this->otp, //otp (*)
            'password'              => $this->password, //pass (*)
            'rkey'                  => $this->rkey, // 20 characters (*)
            'setupKeyEncrypted'     => $this->setupKeyEncrypted, // (*): 
            'imei'                  => $this->imei, // (*)
            'token'                 => $this->token, // (*)
            'onesignalToken'        => $this->onesignalToken, // (*)
            'aaid'                  => '', //null
            'idfa'                  => '', //nul
            'csp'                   => 'Viettel', //Xem trong Charles
            'icc'                   => '', 
            'mcc'                   => '0',
            'mnc'                   => '0',
            'cname'                 => 'Vietnam', //Xem trong Charles
            'ccode'                 => '084', //Xem trong Charles
            'channel'               => 'APP',
            'lang'                  => 'vi',
            'device'                => 'iPhone', //Xem trong Charles
            'firmware'              => '12.4.8', //Xem trong Charles
            'manufacture'           => 'Apple', //Xem trong Charles
            'hardware'              => 'iPhone', //Xem trong Charles
            'simulator'             => false,
            'appVer'                => '21540', //Xem trong Charles
            'appCode'               => "2.1.54", //Xem trong Charles
            'deviceOS'              => "IOS", //Xem trong Charles
            'setupKeyDecrypted'     => $this->encryptDecrypt($this->setupKeyEncrypted, $ohash, 'DECRYPT')

        ];
    }

    private function encryptDecrypt($data, $key, $mode = 'ENCRYPT'){
        if (strlen($key) < 32) {
            $key = str_pad($key, 32, 'x');
        }
        $key = substr($key, 0, 32);
        $iv = pack('C*', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
        if ($mode === 'ENCRYPT') {
            return base64_encode(openssl_encrypt($data, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv));
        }
        else {
            return openssl_decrypt(base64_decode($data), 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
        }
    }

    private function get_microtime(){
        return floor(microtime(true) * 1000);
    }

    private function get_checksum($type){
        $config         = $this->config;
        $checkSumSyntax = $config['phone'] . $this->get_microtime() . '000000' . $type . ($this->get_microtime() / 1000000000000.0) . 'E12';
        return $this->encryptDecrypt($checkSumSyntax, $config['setupKeyDecrypted']);
    }

    private function get_pHash(){
        $config         = $this->config;
        $pHashSyntax    = $config['imei'] . '|' . $config['password'];
        return $this->encryptDecrypt($pHashSyntax, $config['setupKeyDecrypted']);
    }

    public function get_auth(){
        $config         = $this->config;
        $type           = 'USER_LOGIN_MSG';
        $data_body = [
            'user'      => $config['phone'],
            'pass'      => $config['password'],
            'msgType'   => $type,
            'cmdId'     => $this->get_microtime() . '000000',
            'lang'      => $config['lang'],
            'channel'   => $config['channel'],
            'time'      => $this->get_microtime(),
            'appVer'    => $config['appVer'],
            'appCode'   => $config['appCode'],
            'deviceOS'  => $config['deviceOS'],
            'result'    => true,
            'errorCode' => 0,
            'errorDesc' => '',
            'extra'     => [
                'checkSum'          => $this->get_checksum($type),
                'pHash'             => $this->get_pHash(),
                'AAID'              => $config['aaid'],
                'IDFA'              => $config['idfa'],
                'TOKEN'             => $config['token'],
                'ONESIGNAL_TOKEN'   => $config['onesignalToken'],
                'SIMULATOR'         => $config['simulator']
            ],
            'momoMsg'   => [
                '_class'    => 'mservice.backend.entity.msg.LoginMsg'
                , 'isSetup' => true
            ]
        ];

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL             => "https://owa.momo.vn/public",
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_ENCODING        => "",
            CURLOPT_MAXREDIRS       => 10,
            CURLOPT_TIMEOUT         => 30,
            CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST   => "POST",
            CURLOPT_POSTFIELDS      => json_encode($data_body),
            CURLOPT_HTTPHEADER      => array(
                'User-Agent'    => "MoMoApp-Release/%s CFNetwork/978.0.7 Darwin/18.6.0",
                'Msgtype'       => "USER_LOGIN_MSG",
                'Accept'        => 'application/json',
                'Content-Type'  => 'application/json',
                'Userhash'      => md5($config['phone'])  ,
            )
        ));
        $response = curl_exec($curl);
        if(!$response){
            return false;
        }
        return json_decode($response)->extra->AUTH_TOKEN;
    }

    public function history($day = 1){
        $config = $this->config;
        $type   = 'QUERY_TRAN_HIS_MSG';
        $data_post =  [
            'user'      => $config['phone'],
            'msgType'   => $type,
            'cmdId'     => $this->get_microtime() . '000000',
            'lang'      => $config['lang'],
            'channel'   => $config['channel'],
            'time'      => $this->get_microtime(),
            'appVer'    => $config['appVer'],
            'appCode'   => $config['appCode'],
            'deviceOS'  => $config['deviceOS'],
            'result'    => true,
            'errorCode' => 0,
            'errorDesc' => '',
            'extra'     => [
                'checkSum' => $this->get_checksum($type)
            ],
            'momoMsg'   => [
                '_class'    => 'mservice.backend.entity.msg.QueryTranhisMsg',
                'begin'     => (time() - (86400 * $day)) * 1000,
                'end'       => $this->get_microtime()
            ]
        ];
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL             => "https://owa.momo.vn/api/sync/$type",
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_ENCODING        => "",
            CURLOPT_MAXREDIRS       => 10,
            CURLOPT_TIMEOUT         => 30,
            CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST   => "POST",
            CURLOPT_POSTFIELDS      => json_encode($data_post),
            CURLOPT_HTTPHEADER      => array(
                'User-Agent'    => "MoMoApp-Release/%s CFNetwork/978.0.7 Darwin/18.6.0",
                'Msgtype'       => $type,
                'Userhash'      => md5($config['phone']),
                'Accept'        => 'application/json',
                'Content-Type'  => 'application/json',
                'Authorization: Bearer ' . trim($this->get_auth()),
            )
        ));
        $result = curl_exec($ch);
        if(!$result){
            return false;
        }
        return $result;
    }
     public function oder_cash($sdt, $name, $cmt, $amount) {
         
        $config = $this->config;
        $type   = 'M2MU_INIT';
            $data_post = [
              'user'      => $config['phone'],
              'msgType'   => $type,
              'cmdId'     => $this->get_microtime() . '000000',
              'lang'      => $config['lang'],
              'channel'   => $config['channel'],
              'time'      => $this->get_microtime(),
              'appVer'    => $config['appVer'],
              'appCode'   => $config['appCode'],
              'deviceOS'  => $config['deviceOS'],
              'result' => true,
              'errorCode' => 0,
              'errorDesc' => '',
              'extra' => [
                'checkSum' => $this->get_checksum($type)
              ],
              'momoMsg' => [
                '_class' => 'mservice.backend.entity.msg.M2MUInitMsg',
                'ref' => '',
                'tranList' => [
                  0 => [
                    '_class' => 'mservice.backend.entity.msg.TranHisMsg',
                    'tranType' => 2018,
                    'partnerId' => $sdt,
                    'originalAmount' => $amount,
                    'comment' => $cmt,
                    'moneySource' => 1,
                    'partnerCode' => 'momo',
                    'partnerName' => $name,
                    'rowCardId' => NULL,
                    'serviceMode' => 'transfer_p2p',
                    'serviceId' => 'transfer_p2p',
                    'extras' => '{"vpc_CardType":"SML","vpc_TicketNo":"115.79.139.158","receiverMembers":[{"receiverNumber":"'.$sdt.'","receiverName":"'.$name.'","originalAmount":'.$amount.'}],"loanId":0,"contact":{}}',
                  ],
                ],
              ],
            ];
            
            $ch = curl_init();
            curl_setopt_array($ch, array(
                CURLOPT_URL             => "https://owa.momo.vn/api/$type",
                CURLOPT_RETURNTRANSFER  => true,
                CURLOPT_ENCODING        => "",
                CURLOPT_MAXREDIRS       => 10,
                CURLOPT_TIMEOUT         => 30,
                CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST   => "POST",
                CURLOPT_POSTFIELDS      => json_encode($data_post),
                CURLOPT_HTTPHEADER      => array(
                    'User-Agent'    => "MoMoApp-Release/%s CFNetwork/978.0.7 Darwin/18.6.0",
                    'Msgtype'       => $type,
                    'Userhash'      => md5($config['phone']),
                    'Accept'        => 'application/json',
                    'Content-Type'  => 'application/json',
                    'Authorization: Bearer ' . trim($this->get_auth()),
                )
            ));
            $result = curl_exec($ch);
            if(!$result){
                return false;
            }
            return $result;
        
    }
    
    public function comfirm_oderr($id) {
        $config = $this->config;
        $type   = 'M2MU_CONFIRM';

        $data_post = [ 
              'user'      => $config['phone'],
              'msgType'   => $type,
              'cmdId'     => $this->get_microtime() . '000000',
              'lang'      => $config['lang'],
              'channel'   => $config['channel'],
              'time'      => $this->get_microtime(),
              'appVer'    => $config['appVer'],
              'appCode'   => $config['appCode'],
              'deviceOS'  => $config['deviceOS'],
              'result' => true,
              'errorCode' => 0,
              'errorDesc' => '',
              'extra' => [ 
                'checkSum' => $this->get_checksum($type),
              ],
              'momoMsg' => [
                'ids' => [
                  0 => $id,
                ],
                'bankInId' => '',
                '_class' => 'mservice.backend.entity.msg.M2MUConfirmMsg',
                'otp' => '',
                'otpBanknet' => '',
                'extras' => '',
              ],
              'pass' => $config['password'],
            ];
            
            $ch = curl_init();
            curl_setopt_array($ch, array(
                CURLOPT_URL             => "https://owa.momo.vn/api/$type",
                CURLOPT_RETURNTRANSFER  => true,
                CURLOPT_ENCODING        => "",
                CURLOPT_MAXREDIRS       => 10,
                CURLOPT_TIMEOUT         => 30,
                CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST   => "POST",
                CURLOPT_POSTFIELDS      => json_encode($data_post),
                CURLOPT_HTTPHEADER      => array(
                    'User-Agent'    => "MoMoApp-Release/%s CFNetwork/978.0.7 Darwin/18.6.0",
                    'Msgtype'       => $type,
                    'Userhash'      => md5($config['phone']),
                    'Accept'        => 'application/json',
                    'Content-Type'  => 'application/json',
                    'Authorization: Bearer ' . trim($this->get_auth()),
                )
            ));
            $result = curl_exec($ch);
            if(!$result){
                return false;
            }
            return $result;
        
    }
    
    
}
/**
 * @package TOIDICODEDB
 * @author  Toidicode Team
 * @copyright   Copyright (c) 2017, toidicode.com
 * @link    https://toidicode.com
 * @since   Version 1.0
 */
class Database
{
    /**
     * $host Chứa thông tin host
     * @var string
     */
    private $host = 'localhost';
    /**
     * $username Tài khoản truy cập mysql
     * @var string
     */
    private $username = '';
    /**
     * $password Mật khẩu truy cập sql
     * @var string
     */
    private $password = 'Notad';
    /**
     * $databaseName Tên Database các bạn muốn kết nối
     * @var string
     */
    private $databaseName = '';
    /**
     * $charset Dạng ký tự
     * @var string
     */
    private $charset = 'utf8';
    /**
     * $conn Lưu trữ lớp kết nối
     * @var [objetc]
     */
    private $conn;
    /**
     * __construct Hàm khởi tạo
     * @return void;
     */
    public function __construct()
    {
        $this->connect();
    }
    /**
     * connect Kết nối
     * @return void
     */
    public function connect()
    {
        if(!$this->conn){
            $this->conn = mysqli_connect($this->host,$this->username,$this->password,$this->databaseName);
            if (mysqli_connect_errno()) {
                echo 'Failed: '. mysqli_connect_error();
                die();
            }
            mysqli_set_charset($this->conn,$this->charset);
        }
    }
    /**
     * disConnect Ngắt kết nối
     * @return void
     */
    public function disConnect()
    {
        if($this->conn)
            mysqli_close($this->conn);
    }
    /**
     * error Hiển thị lỗi
     * @return string
     */
    public function error()
    {
        if($this->conn)
            return mysqli_error($this->conn);
        else
            return false;
    }
    /**
     * insert thêm dữ liẹu
     * @param string $table tên bảng muốn thêm, array $data dữ liệu cần thêm
     * @return boolean
     */
    public function insert($table = '', $data = [])
    {
        $keys = '';
        $values= '';
        foreach ($data as $key => $value) {
            $keys .= ',' . $key;
            $values .= ',"' . mysqli_real_escape_string($this->conn,$value).'"';
        }
        $sql = 'INSERT INTO ' .$table . '(' . trim($keys,',') . ') VALUES (' . trim($values,',') . ')';
        return mysqli_query($this->conn,$sql);
    }
    /**
     * update sửa dữ liệu
     * @param string $table tên bảng muốn sửa, array $data dữ liệu cần sửa, array|int $id điều kiện
     * @return boolean
     */
    public function update($table = '',$data = [], $id =[])
    {
        $content = '';
        if(is_integer($id))
            $where = 'id = '.$id;
        else if(is_array($id) && count($id)==1){
            $listKey = array_keys($id);
            $where = $listKey[0].'='.$id[$listKey[0]];
        }
        else
            die('Không thể có nhiều hơn 1 khóa chính và id truyền vào phải là số');
        foreach ($data as $key => $value) {
            $content .= ','. $key . '="' . mysqli_real_escape_string($this->conn,$value).'"';
        }
        $sql = 'UPDATE ' .$table .' SET '.trim($content,',') . ' WHERE ' . $where ;
        return mysqli_query($this->conn,$sql);
    }
    /**
     * delete xóa dữ liệu
     * @param string $table tên bảng muốn xóa, array|int điều kiện
     * @return boolean
     */
    public function delete($table= '', $id = [])
    {
        $content = '';
        if(is_integer($id))
            $where = 'id = '.$id;
        else if(is_array($id) && count($id)==1){
            $listKey = array_keys($id);
            $where = $listKey[0].'='.$id[$listKey[0]];
        }
        else
            die('Không thể có nhiều hơn 1 khóa chính và id truyền vào phải là số');
        $sql = 'DELETE FROM ' . $table . ' WHERE '. $where;
        return mysqli_query($this->conn,$sql);
    }
    /**
     * getObject lấy hết dữ liệu trong bảng trả về mảng đối tượng
     * @param string $table tên bảng muốn lấy ra dữ liệu
     * @return array objetc
     */
    public function getObject($table = '')
    {
        $sql = 'SELECT * FROM '. $table;
        $data = null;
        if($result = mysqli_query($this->conn,$sql)){
            while($row = mysqli_fetch_object($result)){
                $data[] = $row;
            }
            mysqli_free_result($result);
            return $data;
        }
        return false;
    }
    /**
     * getObject lấy hết dữ liệu trong bảng trả về mảng dữ liệu
     * @param string $table tên bảng muốn lấy dữ liệu
     * @return array
     */
    public function getArray($table = '')
    {
        $sql = 'SELECT * FROM '. $table;
        $data = null;
        if($result = mysqli_query($this->conn,$sql)){
            while($row = mysqli_fetch_array($result)){
                $data[] = $row;
            }
            mysqli_free_result($result);
            return $data;
        }
        else
            return false;
    }
    /**
     * getRowObject lấy một dòng dữ liệu trong bảng trả về mảng dữ liệu
     * @param string $table tên bảng muốn lấy dữ liệu, array|int $id điều kiện
     * @return object
     */
    public function getRowObject($table = '', $id = [])
    {
        if(is_integer($id))
            $where = 'id = '.$id;
        else if(is_array($id) && count($id)==1){
            $listKey = array_keys($id);
            $where = $listKey[0].'='.$id[$listKey[0]];
        }
        else
            die('Không thể có nhiều hơn 1 khóa chính và id truyền vào phải là số');
        $sql = 'SELECT * FROM '. $table . ' WHERE '. $where;
        
        if($result = mysqli_query($this->conn,$sql)){
            $data = mysqli_fetch_object($result);
            mysqli_free_result($result);
            return $data;
        }
        else
            return false;
    }
    /**
     * getRowArray lấy một dòng dữ liệu trong bảng trả về mảng dữ liệu
     * @param string $table tên bảng muốn lấy dữ liệu, array|int $id điều kiện
     * @return array
     */
    public function getRowArray($table = '', $id = [])
    {
        if(is_integer($id))
            $where = 'id = '.$id;
        else if(is_array($id) && count($id)==1){
            $listKey = array_keys($id);
            $where = $listKey[0].'='.$id[$listKey[0]];
        }
        else
            die('Không thể có nhiều hơn 1 khóa chính và id truyền vào phải là số');
        $sql = 'SELECT * FROM '. $table . ' WHERE '. $where;
        
        if($result = mysqli_query($this->conn,$sql)){
            $data = mysqli_fetch_array($result);
            mysqli_free_result($result);
            return $data;
        }
        else
            return false;
    }
    /**
     * query thực hiện query
     * @param string $sql
     * @return boolean|array
     */
    public function query($sql ='', $return = true)
    {
        if($result = mysqli_query($this->conn,$sql))
        {
            if($return === true){
                while ($row = mysqli_fetch_array($result)) {
                    $data[] = $row;
                }
                mysqli_free_result($result);
                return $data;
            }
            else
                return true;
        }
        else
            return false;
    }
    /**
     * __destruct hàm hủy
     * @param none
     * @return void
     */
    public function __destruct()
    {
        $this->disConnect();
    }
}