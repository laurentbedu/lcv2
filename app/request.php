<?php
include_once $_SERVER['DOCUMENT_ROOT']."/app/App.php";
App::init();
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'navigate'){
    //view (template) + model (data)
    //echo $_POST['page'];
    //echo $_POST['id'];
    //var_dump($_SERVER['DOCUMENT_ROOT']);
    if(file_exists("view/" . $_POST['page'] . ".php")){
        include_once "view/" . $_POST['page'] . ".php";
    }
    else if(file_exists("view/" . $_POST['page'] . ".html")){
        include_once "view/" . $_POST['page'] . ".html";
    }
    else{
        // echo "La page ".$_POST['page']." n'existe pas ou est en construction ...";
        include_once "view/construct.html";
    }
}

if($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['action']) && $_GET['action'] == 'contact_message'){
    //db insert and send email to APP
    $table = ucfirst(Utils::validateInput($_GET['action']));
    $fields = [];
    foreach($_GET as $k=>$v){
        if($k != "action"){
            $fields[$k] = Utils::validateInput($v);
        }
    }
    $result = true;//$table::insert($fields);
    if($result){
        //send email

        echo mail (APP::CONTACT_EMAIL , "Message du formulaire de contact ludi-créa.fr" ,
        $fields['fullname'] . '(' . $fields['email'] . ') vous a anvoyé le message suivant : "' .$fields['message'] .
        '" - Pour lui répondre, faites "Répondre" à cet email.' ,
         'From: contact@ludi-crea.fr' . "\r\n" .
         'Reply-To: ' . $fields['email']);
    }
    //return $result;
}