<?php 
require_once "inc/functions.php";
$info = "";
$task = $_GET["task"] ?? "report";
$error = $_GET["error"] ?? "0";
$successfully = $_GET["successfully"] ?? "0";




if("delete" == $task){
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if($id > 0){
        deleteStudent($id);
        header('Location: /crud/index.php?task=report'); 
        // echo "Deleted student with ID: $id";
        $successfully = 1;

    } else {
        // echo "Invalid ID";
        $error= 3;
    }
}

if("seed" == $task){
    seed();
    $info = "Sedding is complete";
}

$fname = '';
$lname = '';
$roll = '';
if (isset($_POST['submit'])) {
    $fname = trim(filter_input(INPUT_POST, 'fname', FILTER_UNSAFE_RAW) ?? '');
    $lname = trim(filter_input(INPUT_POST, 'lname', FILTER_UNSAFE_RAW) ?? '');
    $roll  = trim(filter_input(INPUT_POST, 'roll', FILTER_SANITIZE_NUMBER_INT) ?? '');
    $id    = trim(filter_input(INPUT_POST, 'id', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');

    if ($id !== '') {
        if ($fname !== '' && $lname !== '' && ctype_digit($roll)) {
            $result = updateStudent($id, $fname, $lname, $roll);
            if ($result === true) {
                header('Location: /crud/index.php?task=report'); 
            } else {
                $error= 1;
            }
        }
    } else {
        if ($fname !== '' && $lname !== '' && ctype_digit($roll)) {
            $result = addStudent($fname, $lname, $roll);
            if ($result === true) {
                header('Location: /crud/index.php?task=report');
                exit;
            } else {
                $error= 1;
            }
        } else {
            $error= 2;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Example</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,300italic,700,700italic">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.4.1/milligram.css">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="column column-60 column-offset-20">
                <h2>Project 2 - CRUD</h2>
                <p>A Sample Project to perform CRUD operations using Plain files and Php</p>
                <?php include_once ('inc/template/nav.php'); ?>
                <hr/>
                <?php
                if($info != ""){
                    echo "<p>{$info}</p>";
                }
                ?>
            </div>
        </div>
 
        <?php if("report" == $task) : ?>
            <div class="row">
                <div class="column column-60 column-offset-20">
                    <?php generateReport(); ?> 
                </div>
            </div>
        <?php endif; ?>    
        <?php if("add" == $task) : ?>
            <div class="row">
                <div class="column column-60 column-offset-20">
                    <form action="/curd/index.php?/task=add" method="post">
                        <label for="fname">First Name</label>
                        <input type="text" name="fname" id="fname" required value="<?php echo $fname?>">
                        <label for="lname">Last Name</label>
                        <input type="text" name="lname" id="lname" required value="<?php echo $lname?>">
                        <label for="roll">Roll</label>
                        <input type="number" name="roll" id="roll" required value="<?php echo $roll?>">
                        <button type="submit" class="button-primary" name="submit" value="save">Save</button>
                    </form>
                </div>
            </div>
        <?php endif; ?>    
<?php if ("edit" == $task) :
    $id = htmlspecialchars(filter_input(INPUT_GET, "id") ?? '');
    $student = getStudent($id);
    if ($student) {
        $fname = $student['fname'] ?? '';
        $lname = $student['lname'] ?? '';
        $roll = $student['roll'] ?? '';
    ?>
        <div class="row">
            <div class="column column-60 column-offset-20">
                <form method="post">
                    <input type="hidden" name="id" value="<?php echo $id ?>">
                    
                    <label for="fname">First Name</label>
                    <input type="text" name="fname" id="fname" required value="<?php echo htmlspecialchars($fname); ?>">
                    
                    <label for="lname">Last Name</label>
                    <input type="text" name="lname" id="lname" required value="<?php echo htmlspecialchars($lname); ?>">
                    
                    <label for="roll">Roll</label>
                    <input type="number" name="roll" id="roll" required value="<?php echo htmlspecialchars($roll); ?>">
                    
                    <button type="submit" class="button-primary" name="submit" value="update">Update</button>
                </form>
            </div>
        </div>
    <?php 
    } 
endif; 
?>

    <?php if("1" == $error) : ?>
        <div class="row">
            <div class="column column-60 column-offset-20">
                <blockquote>
                    Duplicate Roll Number
                </blockquote>
            </div>
        </div>
    <?php endif; ?> 
    <?php if("2" == $error) : ?>
        <div class="row">
            <div class="column column-60 column-offset-20">
                <blockquote>
                    Please fill all fields correctly.
                </blockquote>
            </div>
        </div>
    <?php endif; ?>  
    <?php if("3" == $error) : ?>
        <div class="row">
            <div class="column column-60 column-offset-20">
                <blockquote>
                    Invalid ID
                </blockquote>
            </div>
        </div>
    <?php endif; ?>  
    <?php if("1" == $successfully) : ?>
        <div class="row">
            <div class="column column-60 column-offset-20">
                <blockquote>
                    Deleted student with ID
                </blockquote>
            </div>
        </div>
    <?php endif; ?>  
    </div>
    <script>    
        document.addEventListener('DOMContentLoaded', function() {
            var links = document.querySelectorAll(".delete");
            console.log(links);
            links.forEach(function(link) {
                link.addEventListener("click", function(e) {
                    if (!confirm("Are you sure you want to delete this item?")) {
                        e.preventDefault();
                    }
                });
            });
        });
    </script>
</body>

</html>