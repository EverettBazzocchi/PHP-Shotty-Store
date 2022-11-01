<?php
session_start();

// defines the login for the database accounting for the different environments (my server, someones local machine)
if ($_SERVER['SERVER_NAME'] == "ihatethis.website") {
    $ip = "192.168.0.21";
    $email = "school_webdev2_22";
    $password = "pFIj1RrN6uHYxkq(";
    $database = "SCHOOL_finalprojWD2";
} else {
    $ip = "localhost";
    $email = "serveruser";
    $password = "gorgonzola7!";
    $database = "serverside";
}

// connects to the database using the defined login
try {
    $db = new PDO("mysql:host=$ip;dbname=$database", $email, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}


function login($email, $password)
{
    global $db;

    try {

        $query = $db->prepare("SELECT * FROM Users WHERE email = :email");
        $query->bindParam(':email', $email);
        $query->execute();

        $user = $query->fetch(PDO::FETCH_ASSOC);

        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;
            header('Location: ./');
        }

    } catch (PDOException $e) {
        echo $e->getMessage();
        throw new Exception($e->getMessage());
    }
}

function createAccount($email, $password)
{
    global $db;


    $hash = password_hash($password, PASSWORD_DEFAULT);

    try {
        $query = $db->prepare("INSERT INTO Users (email, password) VALUES (:email, :password)");
        $query->bindParam(':email', $email);
        $query->bindParam(':password', $hash);
        $query->execute();
        
        login($email, $password);
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
        throw new Exception("Failed to create account");
    }

}


function logout()
{
    session_unset();

    session_destroy();
    
    header('Location: ../login.php');
}

function getProducts()
{
    global $db;
    $query = "SELECT * FROM Products";
    $statement = $db->prepare($query);
    $statement->execute();
    $products = $statement->fetchAll();
    return $products;
}

function getShoppingCart()
{
    if ($_SESSION['user']) {
        global $db;

        $cart = $_SESSION['user']->id . "cart";

        $query = "SELECT * FROM $cart";
        $statement = $db->prepare($query);
        $statement->execute();
        $shoppingCart = $statement->fetchAll();
        return $shoppingCart;
    } else {
        echo "You must be logged in to view your cart";
        throw new Exception('User not logged in');
    }
}

function getUserID()
{
    if ($_SESSION['user']) {
        return $_SESSION['user']->id;
    } else {
        throw new Exception('User not logged in');
    }
}

function getReviews($product_id)
{
    global $db;
    $query = "SELECT * FROM Reviews WHERE product_id = :product_id";
    $statement = $db->prepare($query);
    $statement->bindValue(':product_id', $product_id);
    $statement->execute();
    $reviews = $statement->fetchAll();
    return $reviews;
}



?>