<?php

class userpdo {
  private $id;
  public $login;
  public $email;
  public $firstname;
  public $lastname;

  function getId(){
    return $this->id;
  }

  function setId($id){
    $this->id = $id;
  }
  function getLogin(){
    return $this->login;
  }

  function setLogin($login){
    $this->login = $login;
  }

  function getEmail(){
    return $this->email;
  }

  function setEmail($email){
    $this->email = $email;
  }

  function getFirstname(){
    return $this->firstname;
  }

  function setFirstname($firstname){
    $this->firstname = $firstname;
  }

  function getLastname(){
    return $this->firstname;
  }

  function setLastname($lastname){
    $this->lastname = $lastname;
  }



  function register($login, $password, $email, $firstname, $lastname){
    $password = password_hash($password,PASSWORD_BCRYPT);
    $pdo = new PDO('mysql:host=localhost;dbname=classes', 'root', '');
    $stmt = $pdo->prepare("INSERT INTO utilisateurs ( `login`, `password`, `email`, `firstname`, `lastname`) VALUES ('$login','$password','$email','$firstname','$lastname')");
    $stmt->execute();

    if ($stmt->execute() == true) {
      echo "Enregistré";

    }
    else {
      echo "Erreur";

    }
  }



  function login($login,$password){
    $pdo = new PDO('mysql:host=localhost;dbname=classes', 'root', '');
    $stmtLogin = $pdo->prepare("SELECT id FROM utilisateurs WHERE login = '$login'");
    $stmtLogin->execute();
    $stmtPass = $pdo->prepare("SELECT password FROM utilisateurs WHERE login = '$login'");
    $stmtPass->execute();
    $resultL = $stmtLogin->fetch(PDO::FETCH_ASSOC);
    $resultP = $stmtPass->fetch(PDO::FETCH_ASSOC);
    $cryptedPass = $resultP['password'];

    if (count($resultL) == 1) {
      if (password_verify($password,$cryptedPass)) {
        $stmtGet = $pdo->prepare("SELECT id, login, email, firstname, lastname FROM utilisateurs WHERE login = '$login'");
        $stmtGet->execute();
        $result = $stmtGet->fetch(PDO::FETCH_ASSOC);
        $this->setId($result['id']);
        $this->setLogin($result['login']);
        $this->setEmail($result['email']);
        $this->setFirstname($result['firstname']);
        $this->setLastname($result['lastname']);
        echo ("Login: ".$this->getLogin()."<br/>Email: ".$this->getEmail()."<br/>Firstname: ".$this->getFirstname()."<br/>Lastname: ".$this->getLastname());
        return true;

      }
      else {
        echo "Mauvais Mot de passe";
        return false;
      }
    }
    else {
      echo "Mauvais utilisateur";
      return false;
    }
  }



  function disconnect(){
    foreach ($this as $key => $value) {
      $this->$key = null;

    }
    echo "Déconnecté";
  }



  function delete(){
    if (isset($this)) {
      $pdo = new PDO('mysql:host=localhost;dbname=classes', 'root', '');
      $stmt = $pdo->prepare("DELETE FROM utilisateurs WHERE login = '$this->login'");
      $stmt->execute();

      foreach ($this as $key => $value) {
        $this->$key = null;
      }
    }
  }



  function isConnected(){
    if(isset($this->login)){
      return true;
    }
    else {
      return false;
    }
  }



  function update($login,$password,$email,$firstname,$lastname){
    if ($this->isConnected() == true) {
      $oldLogin = $this->getLogin();
      $password = password_hash($password, PASSWORD_BCRYPT);
      $pdo = new PDO('mysql:host=localhost;dbname=classes', 'root', '');
      $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE login = '$login'");
      $stmt->execute();
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      var_dump($result);

      if ($result == false) {
        $stmt = $pdo->prepare("UPDATE `utilisateurs` SET `login`='$login',`password`='$password',`email`='$email',`firstname`='$firstname',`lastname`='$lastname' WHERE login = '$oldLogin'");
        $stmt->execute();
        echo "Compte modifié";
        return true;

      }
      else {
        echo "<br/>Impossible de modifier: Un compte possède le même Login";
        return false;
      }
    }
    else {
      echo "Non connecté";
      return false;
    }
  }



  function refresh(){
    $pdo = new PDO('mysql:host=localhost;dbname=classes', 'root', '');
    $stmt = $pdo->prepare("SELECT id, login, email, firstname, lastname FROM utilisateurs WHERE id = '$this->id'");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $this->setId($result['id']);
    $this->setLogin($result['login']);
    $this->setEmail($result['email']);
    $this->setFirstname($result['firstname']);
    $this->setLastname($result['lastname']);
    echo "Woosh!";

  }




}

$userpdo = new userpdo;

//$userpdo->register("Alain","alain","alain@mail","Alain","alain");
$userpdo->login("Antoine","antoine");
var_dump($userpdo);
$userpdo->update("Madelaine","madelaine","madelaine@mail","Madelaine","madelaine");
var_dump($userpdo);
$userpdo->refresh();
var_dump($userpdo);
//$userpdo->disconnect();
//$userpdo->delete();
//$userpdo->isConnected();
?>
