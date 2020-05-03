<?php


require_once 'src/class.instagram.php';
use ZquaRe\Instagram\instagram;

if(isset($_POST['submit']))
{
    $Instagram = new Instagram($_POST['Username']);
}
else if(isset($_POST['downloadpic']))
{
    $image = $_POST['downloadpic'];
    header("Content-type: image/jpeg");
    header("Cache-Control: no-store, no-cache");
    header('Content-Disposition: attachment; filename="'.rand(150000, 550000).rand(1, 999).rand(999, 99999).'.jpg"');
    readfile($image);
}


?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Instagram Enlarge Profile Picture">
    <meta name="author" content="ZquaRe">
    <title>Instagram Enlarge Profile Picture</title>

    <!-- Bootstrap core CSS -->
    <link href="https://getbootstrap.com/docs/4.4/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body>
<div class="container" style="margin-top:30px;">
<div class="card">
    <div class="card-header">
        Instagram Enlarge Profile Picture
    </div>
    <div class="card-body">
        <h5 class="card-title">Username or instagram profile address</h5>
        <form action="" method="POST">
        <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Username or instagram profile address"  aria-describedby="button-addon2" name="Username" required>
            <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="submit" name="submit" id="button-addon2">Submit</button>
            </div>
        </div>
        </form>
    </div>
</div>
</div>

<?php if(isset($_POST['submit']))
    {
        if (empty(@$Instagram->Picture())) {
            echo '<div class="container" style="margin-top:10px;   text-align: center;">';
                echo '<h4>User not found</h4>';
            echo '</div>';
            }
        else{
            ?>


<div class="container" style="margin-top:10px;">
    <div class="card">
        <div class="card-header">
            Information Profile
        </div>
        <div class="card-body">

                <img src="<?php echo @$Instagram->Picture(); ?>" class="img-fluid" style="display: block; margin-left: auto; margin-right: auto;">

            <form action="" method="POST">
                <button type="submit" name="downloadpic" value="<?php echo @$Instagram->Picture(); ?>" class="btn btn-secondary" style="display: block; margin-left: auto; margin-right: auto; margin-top: 10px; margin-bottom: 10px;">Download picture</button>
            </form>

            <table class="table table-dark table-responsive-sm">
                <thead>
                <tr>
                    <th scope="col">User name</th>
                    <th scope="col">Name</th>
                    <th scope="col">Biography</th>
                    <th scope="col">Follower</th>
                    <th scope="col">Followed</th>
                    <th scope="col">Business</th>
                    <th scope="col">Business Category</th>
                    <th scope="col">Verified</th>
                    <th scope="col">Private</th>

                </tr>
                </thead>
                <tbody>
                <tr>
                    <th><?php echo @$Instagram->Username(); ?></th>
                    <th><?php echo @$Instagram->Fullname(); ?></th>
                    <th><?php echo @$Instagram->Biography(); ?></th>
                    <th><?php echo number_format(@$Instagram->Follower()); ?></th>
                    <th><?php echo number_format(@$Instagram->Followed()); ?></th>
                    <th><?php if(@$Instagram->Business()) echo'Yes'; ?></th>
                    <th><?php echo @$Instagram->Business_Category(); ?></th>
                    <th><?php if(@$Instagram->Verified()) echo 'Yes'; ?></th>
                    <th><?php if(@$Instagram->Private()) echo 'Yes'; ?></th>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

           <?php } ?>
<?php } ?>
</body>