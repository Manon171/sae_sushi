<?php

class BoxManager
{
    private $pdo;
    public function _construct(){
        $this->pdo=new PDO('mysql:host=localhost;dbname=api,'root,'');   
    }
    public function findAll(){
        $boxes=$this->pdo->query("SELCT *FROM boxes")->fetchAll(PDO::FETCH_ASSOC);
        foreach($boces as&$box){
            $box['price'] =round($box['price'],2);
        }
    }
}

$boxManager=new BoxManager();
$boxes=$boxManager->findAll();

header('Content-type:application/json;chaeset=utf-8');
echo json_encode($boxes);

















?>