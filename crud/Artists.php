<?php
require_once "../modules/DBWorker.php";
require_once "ArtistTable.php";

$errors = [];

if (isset($_POST['delete-artist']))
{
    ArtistTable::Delete($_POST['artist-id']);
}
if (isset($_POST['create-artist']))
{
    $name = Validator::Clean($_POST['new-name']);
    $biography = Validator::Clean($_POST['new-biography']);

    $price = $_POST['new-price'];
    $category_id = $_POST['new-category-id'];
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
    else if (empty($name) || empty($biography))
    {
        $errors[] = "Ошибка при считывании полей. Проверьте обязательные поля на пустые значения.";
    }

    // Проверка на корректность категории. Через код элемента категории можно поставить любое значение.
    else if ($category_count == 0)
    {
        $errors[] = "Ошибка при считывании категории. Повторите попытку еще раз.";
    }

    // Ошибок нет? Создаем поле
    else
    {
        ArtistTable::Create($_FILES['new-image'], $name, $biography, $price, $category_id);
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>

    <!--BOOTSTRAP 5-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
          rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
          crossorigin="anonymous">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
            crossorigin="anonymous"></script>

    <!--FONT AWESOME-->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"
          integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm"
          crossorigin="anonymous"/>
</head>
<body>
	<?php include_once "../navigation.html"; ?>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <a href="" class="btn btn-success mt-5" data-bs-toggle="modal" data-bs-target="#create">
                    <i class="fa fa-plus"></i>
                </a>

                <div class="errors-block">
                    <?php
                        foreach ($errors as $error)
                        {
                            echo "<p class='fs-5 mt-2'>" . $error . "</p>";
                        }
                    ?>
                </div>

                <table class="table table-bordered mt-5 text-center">
                    <thead>
                        <tr>
                            <th scope="col">id</th>
                            <th scope="col">image</th>
                            <th scope="col">name</th>
                            <th scope="col">biography</th>
                            <th scope="col">price</th>
                            <th scope="col">category (category_id)</th>
                            <th scope="col">Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $artists = DBWorker::Query("SELECT actors.artist.*, actors.category.category 
                                                            FROM actors.artist 
                                                            JOIN actors.category ON actors.category.id = 
                                                                                    actors.artist.category_id");

                            foreach($artists as $artist): ?>
                        <form action="Artists.php" method="post" class="form-control">
                            <input type="text" name="artist-id" hidden value="<?php echo $artist['id'] ?>">
                            <tr>
                                <td><?php echo $artist['id'] ?></td>
                                <td><img src="../images/<?php echo $artist['image']; ?>" width="100px" alt="Image"></td>
                                <td><?php echo $artist['name'] ?></td>
                                <td><?php echo $artist['biography'] ?></td>
                                <td><?php echo $artist['price'] ?></td>
                                <td><?php echo $artist['category'] . " (" . $artist['category_id'] . ")" ?></td>
                                <td>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#edit-<?php echo $artist['id'] ?>">
                                        <i class="fa fa-pen"></i></button>
                                    <button type="submit" name="delete-artist" class="btn btn-danger">
                                        <i class="fa fa-trash"></i></button>
                                </td>

                                <!-- EDIT -->
                                <div class="modal fade" id="edit-<?php echo $artist['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Изменить запись</h5>
                                                <button type="button" class="btn-danger close" data-bs-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <!--Image-->
                                                <div class="form-group mt-3">
                                                    <p class="text-center">Image</p>
                                                    <input type="file" name="new-image" class="form-control"
                                                </div>

                                                <!--Name-->
                                                <div class="form-group mt-3">
                                                    <p class="text-center">Name</p>
                                                    <input name="new-name" required class="form-control" type="text">
                                                </div>

                                                <!--biography-->
                                                <div class="form-group mt-3">
                                                    <p class="text-center">Biography</p>
                                                    <textarea class="form-control" name="new-biography" id="" cols="30" rows="10"></textarea>
                                                </div>

                                                <!--price-->
                                                <div class="form-group mt-3">
                                                    <p class="text-center">Price</p>
                                                    <input name="new-price" min="0" required class="form-control" type="number">
                                                </div>

                                                <!--category -->
                                                <div class="form-group mt-3">
                                                    <p class="text-center">Category </p>
                                                    <select class="form-select" required name="new-category-id">
                                                        <?php
                                                        $categories = DBWorker::Query("SELECT * FROM actors.category");
                                                        foreach($categories as $category):
                                                            ?>
                                                            <option value="<?php echo $category['id'] ?>"><?php echo $category['category'] ?> (<?php echo $category['id'] ?>)</option>
                                                        <?php
                                                        endforeach;
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                                                <button type="submit" name="edit-artist" class="btn btn-primary">Сохранить</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- EDIT-->

                            </tr>
                        </form>

                        <?php
                            endforeach;
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- CREATE -->
        <div class="modal fade" id="create" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Добавить запись</h5>
                        <button type="button" class="btn-danger close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="Artists.php?go" class="form-control" method="post" enctype="multipart/form-data">
                        <div class="modal-body">
                            <!--Image-->
                            <div class="form-group mt-3">
                                <p class="text-center">Image</p>
                                <input type="file" name="new-image" class="form-control"
                            </div>

                            <!--Name-->
                            <div class="form-group mt-3">
                                <p class="text-center">Name</p>
                                <input name="new-name" required class="form-control" type="text">
                            </div>

                            <!--biography-->
                            <div class="form-group mt-3">
                                <p class="text-center">Biography</p>
                                <textarea class="form-control" name="new-biography" id="" cols="30" rows="10"></textarea>
                            </div>

                            <!--price-->
                            <div class="form-group mt-3">
                                <p class="text-center">Price</p>
                                <input name="new-price" min="0" required class="form-control" type="number">
                            </div>

                            <!--category -->
                            <div class="form-group mt-3">
                                <p class="text-center">Category </p>
                                <select class="form-select" required name="new-category-id">
                                    <?php
                                        $categories = DBWorker::Query("SELECT * FROM actors.category");
                                        foreach($categories as $category):
                                    ?>
                                    <option value="<?php echo $category['id'] ?>"><?php echo $category['category'] ?> (<?php echo $category['id'] ?>)</option>
                                    <?php
                                        endforeach;
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                            <button type="submit" name="create-artist" class="btn btn-primary">Сохранить</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- CREATE-->

    </div>
</body>
</html>