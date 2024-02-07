<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iron House - Delete</title>
    <link rel="stylesheet" href="styles.css">
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
        // get the ironsuit id from the get request
        $ironsuit_id = $_GET['ironsuit_id'];
        $query = "DELETE FROM ironsuits WHERE ironsuit_id = ?;";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'i', $ironsuit_id);
        $result = mysqli_stmt_execute($stmt);

        // if query execution is successful
        if ($result) {
            $affected_rows = mysqli_stmt_affected_rows($stmt);
            // if delete is successful redirect to details.php
            if ($affected_rows == 1) {
                header("Location: details.php");
                // if no rows affected, then the ironsuit was not found
            } else {
                echo "<p class='error'> Error! Ironsuit with id: {$ironsuit_id} not found!</p>";
            }
            // if query execution failed then display error
        } else {
            echo "<p class='error'> Error! Ironsuit not deleted!</p>" . mysqli_error($conn);
        }
    }
    ?>

</body>

</html>