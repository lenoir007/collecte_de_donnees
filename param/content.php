<?php
    session_start();
   header("Content-type:application/json");
    require "zo_frame.php";
    require "db.php";
    $user = "1";

    //$add_collect = $db->prepare("INSERT INTO ".TABLE_COLLECT."(denre_,source_,circuit_,achat_,livraison_,user_,save_) VALUES(?,?,?,?,?,?,?)");

    $request = ["end" => false,"data" => [],"pack" => ""]; $do = new zo();
    if(isset($_POST["action"])){
        switch($_POST["action"]){
            case 'add':case "get":case 'user':
                if($_POST["action"] == "user"){
                    
                }
                else{
                   /*$colle = $db -> prepare("SELECT * FROM ".TABLE_COLLECT." WHERE user_=? ORDER BY id DESC");
                    $colle -> execute([$user]);
                    $colle -> setFetchMode(PDO::FETCH_ASSOC);$o = 0;
                    while ($dat = $colle -> fetch()) {
                        $request["data"][$o] = [
                            "title" => $dat["denre_"]." (".$dat["source_"].")",
                            "circuit" => $dat["circuit_"],
                            "date" => [$dat["achat_"],$dat["livraison"]]
                        ];
                        $o++;
                    }*/
                }
               $request["end"] = true; 
                break;
            case 'add_collect':
                $answers = $do -> is_isset([
                    "denre" => ["not_empty" => true],
                    "source" => ["not_empty" => true],
                    "circuit" => ["not_empty" => true],
                    "achat" => ["not_empty" => true],
                    "livraison" => ["not_empty" => true],
                ]);
                if(count($answers["errors"]) > 0){
                    $request["error"] = $answers["errors"];
                }
                else{
                    $request["end"] = true;
                    extract($answers["contents"]);
                    //$add_collect -> execute([$denre,$source,$circuit,$achat,$livraison,$user,@date("d/M/Y")]);
                }
                break;
        }
        if($request["end"]){
            $request["pack"] = $_POST["action"]; 
        }
    }
    echo json_encode($request);
?>
