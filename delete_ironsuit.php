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

    if (!empty($_GET['ironsuit_id'])) {
        $ironsuit_id = $_GET['ironsuit_id'];
    } else {
        // if no id provided message and exit
        echo "<p> Error! Ironsuit Id not found!</p>";
        exit;
    }

    if ($ironsuit_id) {
        $query = "DELETE FROM ironsuits WHERE ironsuit_id = ?;";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'i', $ironsuit_id);
        $result = mysqli_stmt_execute($stmt);

        if($result) {
            $affected_rows = mysqli_stmt_affected_rows($stmt);
            if($affected_rows == 1) {
                echo "<p> Ironsuit deleted successfully!</p>";
                header("Location: details.php");
            } else {
                echo "<p> Error! Ironsuit with id: {$ironsuit_id} not found!</p>";
            }
        } else {
            echo "<p> Error! Ironsuit not deleted!</p>" . mysqli_error($conn);
        }
    }
    ?>

</body>

</html>