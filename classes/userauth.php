<?php
include_once 'Dbh.php';
session_start();

class UserAuth extends Dbh{
    private static $db;

    public function __construct(){
        $this->db = new Dbh();
    }

    public function register($fullname, $email, $password, $confirmPassword, $country, $gender){
        $conn = $this->db->connect();
        if($this->confirmPasswordMatch($password, $confirmPassword)){
            $sql = "INSERT INTO Students (`full_names`, `email`, `password`, `country`, `gender`) VALUES ('$fullname','$email', '$password', '$country', '$gender')";
           $select = "SELECT email FROM students where email='$email'";
            $result = $conn->query($select);
            if ($result->num_rows > 0) {
                echo "<script>alert('User already exists')</script>";
                header('refresh:1; url=forms/register.php');
            } else {
            if($conn->query($sql)){
               echo "connection successful";
                header('location:forms/login.php');
            } else {
                echo "Opps". $conn->error;
            }
        }

        
    }

    public function login($email, $password){
        $conn = $this->db->connect();
        $sql = "SELECT * FROM Students WHERE email='$email' AND `password`='$password'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            session_start();
            $_SESSION['email'] = $email;
            header("Location: ../dashboard.php");
        } else {
            echo "<script>alert('Incorrect email and /or password')</script>";
            header('refresh:1; url=forms/login.php");
        }
    }

    public function getUser($username){
        $conn = $this->db->connect();
        $sql = "SELECT * FROM users WHERE username = '$username'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            return $result->fetch_assoc();
        } else {
            return false;
        }
    }

    public function getAllUsers(){
        $conn = $this->db->connect();
        $sql = "SELECT * FROM Students";
        $result = $conn->query($sql);
        echo"<html>
        <head>
        <link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css' integrity='sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T' crossorigin='anonymous'>
        </head>
        <body>
        <center><h1><u> ZURI PHP STUDENTS </u> </h1> 
        <table class='table table-bordered' border='0.5' style='width: 80%; background-color: smoke; border-style: none'; >
        <tr style='height: 40px'>
            <thead class='thead-dark'> <th>ID</th><th>Full Names</th> <th>Email</th> <th>Gender</th> <th>Country</th> <th>Action</th>
        </thead></tr>";
        if($result->num_rows > 0){
            while($data = mysqli_fetch_assoc($result)){
                //show data
                echo "<tr style='height: 20px'>".
                    "<td style='width: 50px; background: gray'>" . $data['id'] . "</td>
                    <td style='width: 150px'>" . $data['full_names'] .
                    "</td> <td style='width: 150px'>" . $data['email'] .
                    "</td> <td style='width: 150px'>" . $data['gender'] . 
                    "</td> <td style='width: 150px'>" . $data['country'] . 
                    "</td>
                    <td style='width: 150px'> 
                    <form action='action.php' method='post'>
                    <input type='hidden' name='id'" .
                     "value=" . $data['email'] . ">".
                    "<button class='btn btn-danger' type='submit', name='delete'> DELETE </button> </form> </td>".
                    "</tr>";
            }
            echo "</table></table></center></body></html>";
        }
    }

    public function deleteUser($email){
        $conn = $this->db->connect();
        $sql = "DELETE FROM Students WHERE id = '$email'";
        if($conn->query($sql) === TRUE){
            echo "<script>alert('Successfully Deleted')</script>";
            header("refresh:0.5; url=action.php?all");
        } else {
         echo "<script>alert('Unable to delete user')</script>";
            header("refresh:0.5; url=action.php?all=?message=Error");
        }
    }

    public function updateUser($email, $password){
        $conn = $this->db->connect();
         if ($this->checkEmailExist($email)) {
        $sql = "UPDATE users SET password = '$password' WHERE email = '$email'";
        if($conn->query($sql) === TRUE){
         echo "<script>alert('Password reset was successfull')</script>";
            header('refresh:0.5; url=forms/login.php');
        } else {
            header("Location: forms/resetpassword.php?error=1");
        }
        else {
            echo "<script>alert('This user does not exist')</script>";
            header('refresh:0.5;url=forms/resetpassword.php');
        }
    }

    public function checkEmailExist($email){
        $conn = $this->db->connect();
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            return true;
        } else {
            return false;
        }
         public function getUserByUsername($username)
    {
        $conn = $this->db->connect();
        $sql = "SELECT * FROM users WHERE username = '$username'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return false;
        }
    }

    public function logout($username){
        session_start();
        session_destroy();
        header('Location: index.php');
    }

    public function confirmPasswordMatch($password, $confirmPassword){
        if($password === $confirmPassword){
            return true;
        } else {
            return false;
        }
    }
}
