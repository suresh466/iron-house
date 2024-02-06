<?php

require('db_conn.php');
$query = 'SELECT * FROM ironsuits;';
// get all ironsuits from db
$results = @mysqli_query($conn, $query);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iron House</title>
</head>
<body>
<!-- display all ironsuits in a table -->
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>SR.</th>
            <th>Name</th>
            <th>Added By</th>
            <th>Color</th>
            <th>Description</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php

        // loop through all ironsuits and display them in a table
        // mysqli_fetch_array returns an associative array from the query object
        $sr_no = 0;
        while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
            $sr_no++;
            // no need to initialize $str_to_print with empty string as we are assigning it in the next line
            $str_to_print = "<tr> <td>{$row['ironsuit_id']}</td>";
            $str_to_print .= "<td>{$sr_no}</td>";
            $str_to_print .= "<td>{$row['ironsuit_name']}</td>";
            $str_to_print .= "<td>{$row['product_added_by']}</td>";
            $str_to_print .= "<td>{$row['ironsuit_color']}</td>";
            $str_to_print .= "<td>{$row['ironsuit_description']}</td>";
            $str_to_print .= "<td>{$row['ironsuit_quantity_available']}</td>";
            $str_to_print .= "<td>{$row['ironsuit_price']}</td>";
            $str_to_print .= "<td> <a href='edit_ironsuit.php?ironsuit_id={$row['ironsuit_id']}'>Edit</a> | <a href='delete_ironsuit.php?ironsuit_id={$row['ironsuit_id']}'>Delete</a> </td> </tr>";
            
            echo $str_to_print;
        }
        ?>
    </tbody>
</table>

</body>
</html>