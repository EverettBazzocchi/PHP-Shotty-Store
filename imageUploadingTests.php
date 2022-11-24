<?php 

require('./functions/api.php');

var_dump($_POST);

if (isset($_FILES['image'])) {
    try {
        echo uploadImage($_FILES['image']);
    } catch (Exception $e) {
        echo $e->getMessage();
    }
} else {
    echo "No image";
}
?>

<!DOCTYPE html>
<html lang="en">
    <body>
        <form action="./imageUploadingTests.php" method="post" enctype="multipart/form-data">
            <input type="file" name="image">
            <input type="submit" value="Upload">
        </form>
    </body>
</html>