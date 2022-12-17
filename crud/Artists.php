<?php
    require_once "../modules/DBWorker.php";
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

                            foreach($artists as $artist):
                        ?>
                        <form action="artists_crud.php" method="post" class="form-control">
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


                                <!-- Edit -->
                                <div class="modal fade" id="edit-<?php echo $artist['id'] ?>" tabindex="-1"
                                     role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Изменить запись</h5>
                                                <button type="button" class="btn-danger close" data-bs-dismiss="modal"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="artists_crud.php" method="post" enctype="multipart/form-data">
                                                <div class="modal-body">
                                                    <!--Name-->
                                                    <div class="form-group mt-3">
                                                        <p class="text-center">Name</p>
                                                        <input name="new-name" required class="form-control" type="text"
                                                               value="<?php echo $artist['name'] ?>">
                                                    </div>

                                                    <!--biography-->
                                                    <div class="form-group mt-3">
                                                        <p class="text-center">Biography</p>
                                                        <textarea class="form-control" name="new-biography"
                                                                  cols="30" rows="10"><?php echo $artist['biography'] ?></textarea>
                                                    </div>

                                                    <!--price-->
                                                    <div class="form-group mt-3">
                                                        <p class="text-center">Price</p>
                                                        <input name="new-price" min="0" required class="form-control"
                                                               type="number" value="<?php echo $artist['price'] ?>">
                                                    </div>

                                                    <!--category -->
                                                    <div class="form-group mt-3">
                                                        <p class="text-center">Category </p>
                                                        <select class="form-select" name="new-category-id">
                                                            <?php
                                                            $categories = DBWorker::Query("SELECT * FROM actors.category");
                                                            foreach($categories as $category):
                                                                ?>
                                                                <option <?php if ($category['id'] == $artist['category_id']) echo "selected"?> value="<?php echo $category['id'] ?>">
                                                                    <?php echo $category['category'] ?>
                                                                    (<?php echo $category['id'] ?>)</option>
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
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- Edit-->

                                <?php
                                    endforeach;
                                ?>
                            </tr>
                        </form>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Create -->
        <div class="modal fade" id="create" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Добавить запись</h5>
                        <button type="button" class="btn-danger close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="artists_crud.php?go" class="form-control" method="post" enctype="multipart/form-data">
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