<?php
class user {
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

  function getAllInfos(){
    if ($this->isConnected() == true) {
      foreach ($this as $key => $value) {
        echo ("<br/>".$key." = ".$value);

      }
    }
  }

  function register($login, $password, $email, $firstname, $lastname) {
    $db = mysqli_connect('localhost', 'root', '', 'classes');
    $password = password_hash($password, PASSWORD_BCRYPT);
    $sql = "INSERT INTO utilisateurs ( `login`, `password`, `email`, `firstname`, `lastname`) VALUES ('$login','$password','$email','$firstname','$lastname')";
    $query = mysqli_query($db,$sql);

    if ($query == true) {
      echo "<table><th>Login</th><th>Password</th><th>Email</th><th>FirstName</th><th>LastName</th><tr><td>".$login."</td><td>".$password."</td><td>".$email."</td><td>".$firstname."</td><td>".$lastname."</td></tr></table>";
    }

    elseif ($query == false) {
      echo "error";

    }
  }

  function login($login, $password) {
    $db = mysqli_connect('localhost', 'root', '', 'classes');
    $queryLogin = "SELECT id FROM utilisateurs WHERE login = '$login'";
    $queryPassword = "SELECT password FROM utilisateurs WHERE login = '$login'";
    $reqL = mysqli_query($db,$queryLogin);
    $reqP = mysqli_query($db,$queryPassword);
    $resultP = mysqli_fetch_assoc($reqP);
    $cryptedPass = $resultP['password'];

    if (mysqli_num_rows($reqL) == 1) {

      if (password_verify($password,$cryptedPass)) {
        $db = mysqli_connect('localhost', 'root', '', 'classes');
        $queryAll = "SELECT id, login, email, firstname, lastname FROM utilisateurs WHERE login = '$login'";
        $req = mysqli_query($db,$queryAll);
        $result = mysqli_fetch_assoc($req);
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
  }

  function delete(){
    if (isset($this)) {
      $db = mysqli_connect('localhost', 'root', '', 'classes');
      $sql = "DELETE FROM utilisateurs WHERE login = '$this->login'";
      $req = mysqli_query($db,$sql);

      foreach ($this as $key => $value) {
        $this->$key = null;
      }
    }
  }

  function update($login,$password,$email,$firstname,$lastname){
    if ($this->isConnected() == true) {
      $oldUser = $this->getLogin();
      $db = mysqli_connect('localhost', 'root', '', 'classes');
      $password = password_hash($password, PASSWORD_BCRYPT);
      $queryLogin = "SELECT id FROM utilisateurs WHERE login = '$login'";
      $reqL = mysqli_query($db,$queryLogin);
      if (mysqli_num_rows($reqL) == 0) {
        $sql = "UPDATE `utilisateurs` SET `login`='$login',`password`='$password',`email`='$email',`firstname`='$firstname',`lastname`='$lastname' WHERE login = '$oldUser'";
        $query = mysqli_query($db,$sql);
        echo "<br/>Compte modifié";
        return true;
      }
      else {
        echo "<br/>Impossible de modifier: Un compte possède le même Login";
        return false;
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


  function toEcho($function){
    echo ("<br/>".$function);
  }

  function refresh(){
    $db = mysqli_connect('localhost', 'root', '', 'classes');
    $sql = "SELECT id, login, email, firstname, lastname FROM utilisateurs WHERE id = '$this->id'";
    $req = mysqli_query($db,$sql);
    $result = mysqli_fetch_assoc($req);
    $this->setId($result['id']);
    $this->setLogin($result['login']);
    $this->setEmail($result['email']);
    $this->setFirstname($result['firstname']);
    $this->setLastname($result['lastname']);
  }

}




$user = new User();
var_dump($user);
//$user->register('Admin','admin','admin@admin','admin','admin');
//$user->login("Marie","Olait");
//$user->disconnect();
//$user->delete
//$user->update('Marie','Olait','MarieOlait@mail','Marie','Olait');
//$user->isConnected();
//$user->getAllInfos();
//$user->toEcho();
//$user->getLogin();
//$user->getEmail();
//$user->getFirstname();
//$user->getLastname();
//$user->refresh();
?>
