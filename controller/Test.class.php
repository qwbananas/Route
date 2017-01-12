<?php
namespace Test;

use DB\Database as DB;

class Test {
    public function login() {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $data = Mencrypt("aaa", $password);
        echo $data;
        echo "<br>";
        $de_data = Mdencrypt("aaa", $data);
        echo $de_data;
        $db = new DB();

//        $res = $db -> table("user") -> where("id = 10") -> find();
//        $res = $db -> table("user") -> where("id = 10");
//        $res = $db -> table("user") -> data(array("username" => "aaa")) -> insert();
//        $res = $db -> table("user") -> data(array("username" => "bbb")) -> where("username = 'aaa'") -> update();
//        $res = $db -> table("user") -> where("id = 10") -> delete();
//        $res = $db -> table("user") -> limit(2) -> select();
//        $res = $db -> table("user") -> order("id desc") -> limit(3) -> select();
//        $res = $db -> table("user") -> group("username") -> count("username");
//        var_dump($res);
    }

    public function register() {
        $username = "'".$_POST['username']."'";
        $age      = "'".$_POST['password']."'";
        $sex      = "'".$_POST['repassword']."'";

    }

    public function test2() {
        var_dump($_GET);
    }

    public function test() {
        var_dump($_GET);
    }
}
?>