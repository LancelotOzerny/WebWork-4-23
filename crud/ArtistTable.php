<?php
require_once "../modules/DBWorker.php";

class ArtistTable
{
    public static function Create($image, string $name, string $biography, int $price, int $category_id)
    {
        $field = DBWorker::Query("SELECT actors.artist.id FROM actors.artist ORDER BY id DESC LIMIT 1");
        $current_id = mysqli_fetch_assoc($field)['id'] + 1;

        $artist_image = "NULL";
        $image_exploded = explode(".", $image['name']);
        $image_path = "../images/image-" . $current_id . "." . end($image_exploded);

        if (move_uploaded_file($image['tmp_name'], $image_path)) ;
        {
            $artist_image = "image-" . $current_id . "." . end($image_exploded);
        }

        $sql = "INSERT INTO actors.artist (`name`, `image`, `biography`, `price`, `category_id`) 
                VALUES ('" . $name . "', '" . $artist_image . "', '" . $biography . "', '" . $price . "', '" . $category_id . "')";

        DBWorker::Query($sql);
    }

    public static function Delete(int $id)
    {
        $field = DBWorker::Query("SELECT image FROM actors.artist WHERE actors.artist.id = " . $id);
        $image = mysqli_fetch_assoc($field)['image'];

        if ($image != "no_img.png")
        {
            unlink("../images/" . $image);
        }

        $sql = "DELETE FROM actors.artist WHERE `id` = '" . $id . "'";

        DBWorker::Query($sql);
    }
}