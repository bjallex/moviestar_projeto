<?php

// O __DIR__ garante que o PHP parta da pasta 'dao'
require_once(__DIR__ . "/../models/User.php");
require_once(__DIR__ . "/../models/Message.php");

class UserDAO implements UserDAOInterface {

    private $conn;
    private $url;
    private $message;

    public function __construct(PDO $conn, $url, $message) {
        $this->conn = $conn;
        $this->url = $url;
        $this->message = $message;
    }

    public function buildUser($data) {

        $user = new User();

        $user->id = $data["id"];
        $user->name = $data["name"];
        $user->lastname = $data["lastname"];
        $user->email = $data["email"];
        $user->password = $data["password"];
        $user->image = $data["image"];
        $user->bio = $data["bio"];
        $user->token = $data["token"];

        return $user;

    }

    public function create(User $user, $authUser = false) {
        // Autenticar usuário, caso auth seja true
        $stmt = $this->conn->prepare("INSERT INTO users(name, lastname, password, email,  token) VALUES(:name, :lastname, :password, :email, :token)");

        $stmt->bindParam(":name", $user->name, PDO::PARAM_STR);
        $stmt->bindParam(":lastname", $user->lastname);
        $stmt->bindParam(":password", $user->password);
        $stmt->bindParam(":email", $user->email);
        $stmt->bindParam(":token", $user->token);

        $stmt->execute();
        
        if($authUser){

            $this->setTokenToSession($user->token);
        }

       $this->message->setMessage("Usuário cadastrado com sucesso!", "success", "auth.php");
    }

    public function update(User $user, $redirect = true) {
        $stmt->conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(":email", $user->email);
        $stmt->execute();

        if($stmt->rowCount() > 0) {
            // Redireciona para o perfil do usuario
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            return $this->buildUser($data);
        }
        
    }

    public function verifyToken($protected = false) {

        if($protected) {
            // Redireciona usuário não autenticado
        }

        return false;

    }

    public function setTokenToSession($token, $redirect = true) {

    $_SESSION["token"] = $token;
    
        if($redirect) {
            // Redireciona para o perfil do usuario
            header("Location : " . $this->$url . "editprofile.php");
        }
    }

    public function authenticateUser($email, $password) {
        return false;
    }

    public function findByEmail($email) {
        return false;
    }

    public function findById($id) {

        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = :id");

        $stmt->bindParam(":id",$id);
        $stmt->execute();

        if($stmt->rowCount() > 0){
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $this->buildUser($data);
        }
    }

    public function findByToken($token) {
        $stmt->conn->prepare("SELECT * FROM users WHERE token = :token");
        $stmt->bindParam(":token", $user->token);
        $stmt->execute();
        
         if($stmt->rowCount() > 0) {
            // Redireciona para o perfil do usuario
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            return $this->buildUser($data);
        }
    }

    public function destroyToken() {
        // Redirecionar e apresentar a mensagem de sucesso
        $_SESSION["token"] = "token excluido";
        header("Location :" . $this->$url. "index.php");
    }

    public function changePassword(User $user) {
        // Redirecionar e apresentar a mensagem de sucesso
        $_SESSION["password"] = "Senha Alterada";
        header("Location : " . $this->$url . "");
    }

}
