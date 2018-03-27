<?php
    session_start();
    header("Content-type:application/json");
    require "zo_frame.php";

    $request = ["end" => false,"user" => 0]; $do = new zo();
    if(isset($_POST["action"])){
        switch($_POST["action"]){
            case 'session':
                if(isset($_SESSION["USER_iD"]) && is_numeric($_SESSION["USER_iD"])){
                    $request["end"] = true;
                }
                break;
            case 'login':
                $answers = $do -> is_isset([
                    "user_" => ["not_empty" => true,"type_value" => "login","limite_char" => "2"],
                    "secret_" => ["not_empty" => true,"limite_char" => "8"],
                ]);
                if(count($answers["errors"]) > 0){
                    $request["error"] = $answers["errors"];
                }
                else{
                    extract($answers["contents"]);
                    if($user_ == "admin" && md5($secret_) == md5("Secret00")){
                        $request["end"] = true;$_SESSION["USER_iD"] = 1;
                    }
                    else{$request["error"] = ["user_" => false, "secret_" => false];}
                }
                break;
        }
    }
    echo json_encode($request);
?>