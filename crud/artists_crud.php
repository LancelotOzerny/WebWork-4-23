<?php
    require_once "../modules/DBWorker.php";

    if (isset($_POST['create-artist']))
    {
        var_dump($_FILES);
        echo "<br><br>";
        var_dump($_POST);

        $field = DBWorker::Query("SELECT actors.artist.id FROM actors.artist ORDER BY id DESC LIMIT 1");
        $current_id = mysqli_fetch_assoc($field)['id'] + 1;

        $name = htmlentities($_POST['new-name']);
        $biography = htmlentities($_POST['new-biography']);
        $price = $_POST['new-price'];
        $categoryId = $_POST['new-category-id'];

        $image = "NULL";
        $image_exploded = explode(".", $_FILES['new-image']['name']);
        $image_path = "../images/image-" . $current_id . "." . end($image_exploded);

        if (move_uploaded_file($_FILES['new-image']['tmp_name'], $image_path));
        {
            $image = "image-" . $current_id . "." . end($image_exploded);
        }



        $sql = "INSERT INTO actors.artist (`name`, `image`, `biography`, `price`, `category_id`) 
                VALUES ('" . $name . "', '" . $image . "', '" . $biography . "', '" . $price . "', '" . $categoryId . "')";
        $query = DBWorker::Query($sql);


        header("Location: Artists.php");
    }


    if (isset($_POST['delete-artist']))
    {
        $id = $_POST['artist-id'];
        $sql = "DELETE FROM actors.artist WHERE `id` = '" . $id . "'";
        DBWorker::Query($sql);
        header("Location: Artists.php");
    }
?>