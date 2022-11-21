<?php

session_start();

//require $_SERVER['DOCUMENT_ROOT'] . '/project/vendor/autoload.php';

//use Intervention\Image\ImageManager;

$ip = "192.168.0.21";
$email = "school_webdev2_22";
$password = "2S(6XIpCt*uiLe.I";
$database = "SCHOOL_finalprojWD2";

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
        var_dump($query);
        $query->bindParam(':email', $email);
        $query->execute();

        $user = $query->fetch(PDO::FETCH_ASSOC);

        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;
            header('Location: ./');
        } else
            throw new Exception("Incorrect Email or Password");

    } catch (Exception $e) {
        throw new Exception("Incorrect Email or Password");
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
    } catch (Exception $e) {
        if (str_contains($e->getMessage(), "SQLSTATE[23000]:")) {
            throw new Exception("Email already in use");
        } else {
            echo "Connection failed: " . $e->getMessage();
            throw new Exception("Something went wrong");
        }
    }

}


function logout()
{
    session_unset();

    session_destroy();

    header('Location: /project/login.php');
}

function getProducts($product, $search = NULL)
{
    global $db;
    $products = array();
    if (!$product === "all") {
        $query = $db->prepare("SELECT * FROM Products WHERE category = :category");
        $query->bindParam(':category', $product);
    } else {
        $query = $db->prepare("SELECT * FROM Products");
    }

    $query->execute();
    $products = $query->fetchAll();

    return $products;
}

function getProduct($product)
{
    global $db;
    $query = $db->prepare("SELECT * FROM Products WHERE id = :id");
    $query->bindParam(':id', $product);
    if ($query->execute()) {
        $product = $query->fetch();
        return $product;
    } else {
        return false;
    }
}

function deleteProduct($id)
{
    global $db;
    $query = $db->prepare("UPDATE Products SET blocked = '1' WHERE id = :id");
    $query->bindParam(':id', $id);
    return $query->execute();
}

function getShoppingCart()
{
    if ($_SESSION['user']) {
        global $db;

        // TO WRITE
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
    $query = "SELECT * FROM Reviews WHERE product_id = :product_id ORDER BY date_created DESC";
    $statement = $db->prepare($query);
    $statement->bindValue(':product_id', $product_id);
    $statement->execute();
    $reviews = $statement->fetchAll();
    return $reviews;
}

function createProduct($products, $user_id)
{
    global $db;
    $error = "";

    $target_dir = "uploads/images/";
    $target_file = $target_dir . basename($_FILES['image']["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        echo "File already exists.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if (
        $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif"
    ) {
        echo "Only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    if ($uploadOk == 0) {
        echo "An Error Occurred";
    } else {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            echo "The file " . htmlspecialchars(basename($_FILES["image"]["name"])) . " has been uploaded.";
            //$manager = new ImageManager(['driver' => 'imagick']);
            //$image = $manager->make($target_file)->resize(300, 300);
            //$image->save($target_file);
        } else {
            echo "An Error Occurred";
        }
    }

    $query = "INSERT INTO Products (image_text, owner_id, name, description, price, category, image) VALUES (:image_text, :user_id, :name, :description, :price, :category, :image)";
    $statement = $db->prepare($query);
    $statement->bindValue(':name', $products['name']);
    $statement->bindValue(':description', $products['description']);
    $statement->bindValue(':price', $products['price']);
    $statement->bindValue(':category', $products['category']);
    $statement->bindValue(':image', $products['image']['name']);
    $statement->bindValue(':user_id', $user_id);
    $statement->bindValue(':image_text', $products['name']);
    $statement->execute();
}


?>