<?php
namespace Test;

use DB\Database as DB;

class Test {
    private $output = array();
    private $a = "users";
    private $uid = "10001";

    public function result() {
        if(empty($this -> a)) {
            $this -> uid = array('data' => NULL, 'info' => 'There is nothing' , 'code' => -201);
            exit(json_encode($this -> uid));
        }

        if($this -> a == 'users') {
            if($this -> uid == 0) {
                $this -> output = array('data' => NULL, 'info' => 'The uid is null!', 'code' => -202);
                exit(json_encode($this -> output));
            }
            $mysql = array(
                10001 => array(
                    'uid'=>10001,
                    'vip'=>5,
                    'nickname' => 'Shine X',
                    'email'=>'979137@qq.com',
                    'qq'=>979137,
                    'gold'=>1500,
                    'powerplay'=> array('2xp'=>12,'gem'=>12,'bingo'=>5,'keys'=>5,'chest'=>8),
                    'gems'=> array('red'=>13,'green'=>3,'blue'=>8,'yellow'=>17),
                    'ctime'=>1376523234,
                    'lastLogin'=>1377123144,
                    'level'=>19,
                    'exp'=>16758,
                ),
                10002 => array(
                    'uid'=>10002,
                    'vip'=>50,
                    'nickname' => 'elva',
                    'email'=>'elva@ezhi.net',
                    'qq'=>NULL,
                    'gold'=>14320,
                    'powerplay'=> array('2xp'=>1,'gem'=>120,'bingo'=>51,'keys'=>5,'chest'=>8),
                    'gems'=> array('red'=>13,'green'=>3,'blue'=>8,'yellow'=>17),
                    'ctime'=>1376523234,
                    'lastLogin'=>1377123144,
                    'level'=>112,
                    'exp'=>167588,
                ),
                10003 => array(
                    'uid' => 10003,
                    'vip' => 5,
                    'nickname' => 'Lily',
                    'email' => 'Lily@ezhi.net',
                    'qq' => NULL,
                    'gold' => 1541,
                    'powerplay'=> array('2xp'=>2,'gem'=>112,'bingo'=>4,'keys'=>7,'chest'=>8),
                    'gems' => array('red'=>13,'green'=>3,'blue'=>9,'yellow'=>7),
                    'ctime' => 1376523234,
                    'lastLogin'=> 1377123144,
                    'level' => 10,
                    'exp' => 1758,
                )
            );
            $uidArr = array(10001, 10002,10003);
            if(in_array($this -> uid, $uidArr, true)) {
                $this -> output = array('data' => NULL, 'info' => 'The user does not exist!'.$this -> uid, 'code' => -203);
                exit(json_encode($this -> output));
            }

            $userInfo = $mysql[$this -> uid];
            $this -> output = array(
                'data' => array(
                    'userinfo' => $userInfo,
                    'isLogin'  => true,
                    'unread'   => 4,
                    'untask'   => 3,
                ),
                'info' => 'Here is the message which, commonly used in popup window',
                'code' => 200
            );
            $data = json_encode($this -> output);
            echo $data;
            exit;
        } elseif($this -> a == 'games_result') {
            die('You are using games_result API');
        } elseif($this -> a == 'upload_avatars') {
            die('You are using upload_avatars API');
        }
    }

    public function getuser() {
        $link = mysqli_connect("192.168.221.115:10001", "root", "123456", "test");
        mysqli_query($link, "set charset utf8");
        $arr = array();
        $i = 0;
        $res = mysqli_query($link, "select * from user");
        while($row = mysqli_fetch_assoc($res)) {
            $arr[$i]['ID'] = $row['id'];
            $arr[$i]['USERNAME'] = $row['username'];
            $arr[$i]['SEX'] = $row['sex'];
            $arr[$i]['AGE'] = $row['age'];

            $i ++;
        }
        $data =  json_encode($arr);
        echo $data;
        mysqli_close($link);
    }

    public function ecrypt_test() {
        $arr = array("username" => "banana", "password" => "123456");
        $data = json_encode($arr);
        $keyPath = __DIR__;
        createKey($keyPath);
        $pubEncrypt = pubEncrypt($keyPath, $data);
        echo $pubEncrypt;
        echo "<br>";
        $data_decrypt = privDecrypt($keyPath, $pubEncrypt);
        echo $data_decrypt;
    }

    public function db_test() {
        $db = new DB('localhost', 'root', '123456', 'test');
        var_dump($db);
    }
}