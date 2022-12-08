<?php

session_start();

require $_SERVER['DOCUMENT_ROOT'] . '/project/vendor/autoload.php';
use Intervention\Image\ImageManager;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;


$ip = "192.168.0.21";
$email = "school_webdev2_22";
$password = "2S(6XIpCt*uiLe.I";
$database = "SCHOOL_finalprojWD2";

// connects to the database using the defined login.
try {
    $db = new PDO("mysql:host=$ip;dbname=$database", $email, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}


/**
 * Function to log the user in.
 *
 * @param  string $email
 * @param  string $password
 * @return void
 */
function login(string $email, string $password): void
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
        } else
            throw new Exception("Incorrect Email or Password");

    } catch (Exception $e) {
        throw new Exception("Incorrect Email or Password");
    }
}

/**
 * Function to create user account.
 *
 * @param  string $email
 * @param  string $password
 * @return void
 */
function createAccount(string $email, string $password): void
{
    global $db;

    $hash = password_hash($password, PASSWORD_DEFAULT);

    try {
        $query = $db->prepare("INSERT INTO Users (email, password) VALUES (:email, :password)");
        $query->bindParam(':email', $email);
        $query->bindParam(':password', $hash);
        $query->execute();

        header('Location: ./please_verify?email=' . $email);
    } catch (Exception $e) {
        if (str_contains($e->getMessage(), "SQLSTATE[23000]:")) {
            throw new Exception("Email already in use");
        } else {
            throw new Exception($e->getMessage());
        }
    }

}

/**
 * Function to send a user a verification email.
 *
 * @param  string $email
 * @return void
 */
function sendVerifyEmail(string $email): void
{
    global $db;

    $query = $db->prepare("SELECT * FROM Users WHERE email = :email");
    $query->bindParam(':email', $email);
    if (!$query->execute()) {
        throw new Exception("Account not found");
    }
    $query = $query->fetch(PDO::FETCH_ASSOC);
    $user_id = $query['id'];

    if ($query['verified'] == 1) {
        throw new Exception("Email already verified");
    }

    $token = bin2hex(random_bytes(16));

    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->isSMTP();
        $mail->Host = 'smtppro.zoho.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'noreply@ihatethis.website';

        $mail->Password = require($_SERVER['DOCUMENT_ROOT'] . '/project/functions/key.php');
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        // Email Settings
        $mail->setFrom('noreply@ihatethis.website', 'Shotty Tech - No Reply');
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Verify Your Email';
        $mail->Body = "Please click the link below to verify your email: <a href='https://ihatethis.website/project/functions/verify_email.php?token=$token'>Verify Email</a>";
        $mail->AltBody = "Please click the link below to verify your email: https://ihatethis.website/project/functions/verify_email.php?token=$token";

        $mail->send();

        $query = $db->prepare("INSERT INTO Email_Verification (user_id, verify_code) VALUES (:user_id, :verify_code)");
        $query->bindParam(':user_id', $user_id);
        $query->bindParam(':verify_code', $token);
        $query->execute();

    } catch (Exception $e) {
        throw new Exception($e->getMessage() . $mail->ErrorInfo);
    }
}

/**
 * Function that verifies the user's email.
 *
 * @param  string $token
 * @return void
 */
function verifyEmail(string $token): void
{
    global $db;

    $query = $db->prepare("SELECT * FROM Email_Verification WHERE verify_code = :token");
    $query->bindParam(':token', $token);
    if (!$query->execute()) {
        throw new Exception("Invalid Token");
    }
    $query = $query->fetch(PDO::FETCH_ASSOC);

    $query2 = $db->prepare("UPDATE Users SET verified = 1 WHERE id = :user_id");
    $query2->bindParam(':user_id', $query['user_id']);
    if (!$query2->execute()) {
        throw new Exception("Invalid Token");
    }

    $query3 = $db->prepare("DELETE FROM Email_Verification WHERE verify_code = :token");
    $query3->bindParam(':token', $token);
    if (!$query3->execute()) {
        throw new Exception("Invalid Token");
    }

    header('Location: ./login');
}


/**
 * A function to test smtp.
 *
 * @return void
 */
function testSMTP(): void
{
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->isSMTP();
        $mail->Host = 'smtppro.zoho.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'noreply@ihatethis.website';
        $mail->Password = '2S(6XIpCt*uiLe.I';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        // Email Settings
        $mail->setFrom('noreply@ihatethis.website', 'Shotty Tech - No Reply');
        $mail->addAddress('contact@everettbazzocchi.ca');

        // Content
        $mail->Subject = 'Test';
        $mail->Body = "Test Email";
        $mail->send();

    } catch (Exception $e) {
        throw new Exception("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
    }
}

/**
 * Logout function.
 *
 * @return void
 */
function logout(): void
{
    session_unset();

    session_destroy();

    header('Location: /project/login.php');
}


/**
 * getProducts
 *
 * @param  string $category
 * @param  string $search = ""
 * @return array
 */
function getProducts(string $category, string $sort = "price ASC"): array
{
    global $db;

    if (str_ends_with($sort, "ASC")) {
        $order_by = "ORDER BY " . substr($sort, 0, strlen($sort) - 4) . " ASC;";
    } else if (str_ends_with($sort, "DESC")) {
        $order_by = "ORDER BY " . substr($sort, 0, strlen($sort) - 5) . " DESC;";
    } else {
        $order_by = "ORDER BY $sort;";
    }

    $products = array();
    if ($category == "all") {
        $query = $db->prepare("SELECT * FROM Products $order_by");
    } else {
        $query = $db->prepare("SELECT * FROM Products WHERE category = :category $order_by");
        $query->bindParam(':category', $category);
    }

    if ($query->execute()) {
        $products = $query->fetchAll(PDO::FETCH_ASSOC);
    } else {
        throw new Exception("Something went wrong contact admin.");
    }
    return $products;
}

/**
 * Function to get all products.
 *
 * @param  int $id
 * @return array
 */
function getProduct(int $id): array |bool
{
    global $db;
    $query = $db->prepare("SELECT * FROM Products WHERE id = :id");
    $query->bindParam(':id', $id);
    if ($query->execute()) {
        $product = $query->fetch();
        return $product;
    } else {
        throw new Exception("Product not found");
    }
}


/**
 * Function to disable a specific product.
 *
 * @param  int $id
 * @return void
 */
function deleteProduct(int $id): void
{
    if (!(isset($_SESSION['user']) && (($_SESSION['user']['role'] >= 5)))) {
        throw new Exception("You do not have permission to delete this product");
    }

    global $db;

    $query = $db->prepare("UPDATE Products SET blocked = '1' WHERE id = :id");
    $query->bindParam(':id', $id);

    if (!$query->execute()) {
        throw new Exception("Something went wrong contact admin.");
    }
}

/**
 * Function to edit a product.
 *
 * @param  array $product
 * @return void
 */
function editProduct(array $product): void
{
    global $db;

    getProduct($product['id']);

    if (!(isset($_SESSION['user']) && (($_SESSION['user']['role'] >= 5)) || ($_SESSION['user']['id'] === $product['owner_id']))) {
        throw new Exception("You do not have permission to edit this product");
    }

    $query = $db->prepare("UPDATE Products SET name = :name, description = :description, price = :price, category = :category, image = :image WHERE id = :id");
    $query->bindParam(':id', $product['id']);
    $query->bindParam(':name', $product['name']);
    $query->bindParam(':description', $product['description']);
    $query->bindParam(':price', $product['price']);
    $query->bindParam(':category', $product['category']);
    $query->bindParam(':image', $product['image']);

    if (!$query->execute()) {
        throw new Exception("Product not found");
    }
}

/**
 * Function that uploads an image to the server.
 *
 * @param  array $image
 * @return string
 * @throws Exception if file is not an image or file already exists
 * @throws Exception if file extension is not allowed
 * @throws Exception if there was an error uploading the file
 */
function uploadImage(array $image): string
{
    $imageFolder = $_SERVER['DOCUMENT_ROOT'] . '/project/uploads/images/';

    $uploadFileType = strtolower(pathinfo(basename($image['name']), PATHINFO_EXTENSION));

    $fileName = uniqid() . '.' . $uploadFileType;
    $uploadFile = $imageFolder . $fileName;

    // Check if image file is an image    
    $check = getimagesize($image['tmp_name']);
    if (!$check) {
        throw new Exception("File is not an image");
    }

    // Check if file already exists
    if (file_exists($uploadFile)) {
        throw new Exception("Sorry, file already exists.");
    }

    // Check file extension
    if (
        $uploadFileType != "jpg" && $uploadFileType != "png" && $uploadFileType != "jpeg" && $uploadFileType != "gif"
    ) {
        throw new Exception("Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
    }

    // Resizes images and uploads, returns the new file name
    try {
        $manager = new ImageManager();
        $newImage = $manager->make($image['tmp_name']);
        $newImage->resize(500, 500);
        $newImage->save($uploadFile);

        return $fileName;
    } catch (Exception $e) {
        throw $e;
    }
}


/**
 * Returns users shopping cart.
 *
 * @return void
 */
function getShoppingCart()
{
    if ($_SESSION['user']) {
        global $db;

        // TO WRITE
    }
}


/**
 * Function to return users id.
 *
 * @return int
 */
function getUserID(): int
{
    if ($_SESSION['user']) {
        return $_SESSION['user']->id;
    } else {
        throw new Exception('User not logged in');
    }
}


/**
 * Function to retrieve comments left on a specified product page.
 *
 * @param  int $product_id
 * @return array
 */
function getReviews(int $product_id): array
{
    global $db;
    $query = "SELECT * FROM Reviews WHERE product_id = :product_id ORDER BY date_created DESC";
    $statement = $db->prepare($query);
    $statement->bindValue(':product_id', $product_id);
    $statement->execute();
    $reviews = $statement->fetchAll();
    return $reviews;
}

/**
 * Funtion to create a product.
 *
 * @param  array $products
 * @param  int $user_id
 * @return void
 */
function createProduct(array $products, int $user_id): void
{
    if (!(isset($_SESSION['user']) && (($_SESSION['user']['role'] >= 5)))) {
        throw new Exception("You do not have permission to create a product");
    }
    global $db;

    $image = uploadImage($products['image']);

    $query = "INSERT INTO Products (image_text, owner_id, name, description, price, category, image) VALUES (:image_text, :user_id, :name, :description, :price, :category, :image)";
    $statement = $db->prepare($query);
    $statement->bindValue(':name', $products['name']);
    $statement->bindValue(':description', $products['description']);
    $statement->bindValue(':price', $products['price']);
    $statement->bindValue(':category', $products['category']);
    $statement->bindValue(':image', $image);
    $statement->bindValue(':user_id', $user_id);
    $statement->bindValue(':image_text', $products['name']);
    if (!$statement->execute()) {
        throw new Exception("Something went wrong contact admin.");
    }
}


?>