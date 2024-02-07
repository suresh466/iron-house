<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iron House - Delete</title>
</head>

<body>

    <?php
    require('db_conn.php');
    $ironsuit_id = null;
    // if no id provided message and exit
    if (empty($_GET['ironsuit_id'])) {
        echo "<p class='error'> Error! Ironsuit Id not found!</p>";
        exit;
    } else {
        $ironsuit_id = $_GET['ironsuit_id'];
        $query = "DELETE FROM ironsuits WHERE ironsuit_id = ?;";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'i', $ironsuit_id);
        $result = mysqli_stmt_execute($stmt);

        if($result) {
            $affected_rows = mysqli_stmt_affected_rows($stmt);
            if($affected_rows == 1) {
                header("Location: details.php");
            } else {
                echo "<p class='error'> Error! Ironsuit with id: {$ironsuit_id} not found!</p>";
            }
        } else {
            echo "<p class='error'> Error! Ironsuit not deleted!</p>" . mysqli_error($conn);
        }
    }
    ?>

</body>

</html>