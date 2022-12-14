<?php
require_once "../modules/DBWorker.php";
require_once "../modules/Validator.php";

class ArtistTable
{
    public static function Create($image, string $name, string $biography, int $price, int $category_id)
    {
        $current_id = mysqli_fetch_assoc(DBWorker::Query("SHOW TABLE STATUS FROM `actors` WHERE `name` LIKE 'artist'"))['Auto_increment']+ 1;
        $artist_image = "no_img.png";

        // Проверяем поля на наличие ошибок
        $errors = self::CheckFields($name, $biography, $price, $category_id);
        if (empty($errors))
        {
            if (!empty($image['name'])) {
                $image_exploded = explode(".", $image['name']);
                $image_path = "../images/image-" . $current_id . "." . end($image_exploded);

                $imgEnd = end($image_exploded);
                if ($imgEnd != "png" && $imgEnd != "jpg" && $imgEnd != "jpeg" && $imgEnd != "gif")
                {
                    $errors[] = "Изображение должно быть в формате png\\jpeg\\gif. Повторите попытку исправив изображение!";
                    return $errors;
                }

                if (move_uploaded_file($image['tmp_name'], $image_path)) ;
                {
                    $artist_image = "image-" . $current_id . "." . end($image_exploded);
                }
            }

            // Ошибок нет? Создаем поле
            $sql = "INSERT INTO actors.artist (`name`, `image`, `biography`, `price`, `category_id`) 
                VALUES ('" . $name . "', '" . $artist_image . "', '" . $biography . "', '" . $price . "', '" . $category_id . "')";

            DBWorker::Query($sql);
        }

        return $errors;
    }

    public static function Update(int $id, $image, string $name, string $biography, int $price, int $category_id)
    {
        $field = mysqli_fetch_assoc(DBWorker::Query("SELECT image FROM actors.artist WHERE `id` = " . $id));
        $artist_image = $field['image'];

        // Проверяем поля на наличие ошибок
        $errors = self::CheckFields($name, $biography, $price, $category_id);

        if (empty($errors))
        {
            // Ошибок нет? Редактируем  поле
            if (!empty($image['name'])) {
                $image_exploded = explode(".", $image['name']);
                $image_path = "../images/image-" . $id . "." . end($image_exploded);

                $imgEnd = end($image_exploded);
                if ($imgEnd != "png" && $imgEnd != "jpg" && $imgEnd != "jpeg" && $imgEnd != "gif")
                {
                    $errors[] = "Изображение должно быть в формате png\\jpeg\\gif. Повторите попытку исправив изображение!";
                    return $errors;
                }

                if ($field['image'] != "no_img.png") {
                    unlink("../images/" . $field['image']);
                }

                if (move_uploaded_file($image['tmp_name'], $image_path)) ;
                {
                    $artist_image = "image-" .$id . "." . end($image_exploded);
                }
            }

            $sql = "UPDATE actors.artist SET 
                         `name` = '" . $name . "', 
                         `image` = '" . $artist_image . "', 
                         `biography` = '" . $biography . "', 
                         `price` = '" . $price . "', 
                         `category_id` = '" . $category_id . "' 
                         WHERE id = " . $id;

            DBWorker::Query($sql);
        }

        return $errors;
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

    public static function CheckFields(string $name, string $biography, int $price, int $category_id)
    {
        $errors = [];
        $category_count = count(mysqli_fetch_all(DBWorker::Query("SELECT * FROM actors.category WHERE actors.category.id = " . $category_id)));

        // Проверка на числовые поля. Через код элемента можно поставить text
        if (!is_numeric($price) || !is_numeric($category_id))
        {
            $errors[] = "Ошибка при считывании полей. Проверьте числовые поля на соответствие типу.";
        }

        // Проверка поля цены на отрицательное значение
        else if ($price < 0)
        {
            $errors[] = "Ошибка при работе с ценой. Она не может быть отрицательной. Проверьте цену и повторите снова.";
        }

        // Проверка полей на пустые значения. Можно поставить 2 пробела и все пройдет, то есть могут быть пустые значения.
        else if (empty($name))
        {
            $errors[] = "Ошибка при считывании полей. Проверьте обязательные поля на пустые значения.";
        }

        // Проверка на корректность категории. Через код элемента категории можно поставить любое значение.
        else if ($category_count == 0)
        {
            $errors[] = "Ошибка при считывании категории. Повторите попытку еще раз.";
        }

        return $errors;
    }
}