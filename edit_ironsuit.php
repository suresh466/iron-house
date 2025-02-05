<?php
require('db_conn.php');
// handle post request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    function validate_form($form_data)
    {
        // get the input from the form
        $name = $form_data['name'];
        $color = $form_data['color'];
        $description = $form_data['description'];
        $quantity = $form_data['quantity'];
        $price = $form_data['price'];

        $errors = [];

        if (empty($name)) {
            $errors[] = "Name is required";
        } else {
            // checking for string because name of iron suit can be like Mark 5 or Mark_5 or Mark-5
            if (!is_string($name)) {
                $errors[] = "Name can only be a string";
            }
        }

        // only string is allowed for color
        if (empty($color)) {
            $errors[] = "Color is required";
        } else {
            if (!is_text_only($color)) {
                $errors[] = "Color can only contain letters and spaces";
            }
        }

        if (empty($description)) {
            $errors[] = "Description is required";
        } else {
            // Description can contain all kinds of characters in order to describe the suits
            if (!is_string($description)) {
                $errors[] = "Description can only be a string";
            }
        }

        // must be an integer 10.1 is not allowed for instance
        if (empty($quantity)) {
            $errors[] = "Quantity is required";
        } else {
            if (!filter_var($quantity, FILTER_VALIDATE_INT)) {
                $errors[] = "Quantity must be an integer";
            }
        }

        // Validate price
        if (empty($price)) {
            $errors[] = "Price is required";
            // check if price is a valid number with a maximum of two decimal places
        } else if (!preg_match('/^\d+(\.\d{1,2})?$/', $price)) {
            $errors[] = "Price must be a valid positive number with a maximum of two decimal places";
        }

        return $errors;
    }

    function is_text_only($input)
    {
        // no need for if else blocks because preg_match returns 1 for true and 0 for false itself
        return !preg_match("/[^a-zA-Z- ]/", $input);
    }

    // validate the form
    $errors = validate_form($_POST);

    // if there are any errors display them
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<p class='error'>$error</p>";
        }
        // if no errors then update the ironsuit
    } else {
        // sanitize string
        $name_clean = prepare_string($conn, $_POST['name']);
        $color_clean = prepare_string($conn, $_POST['color']);
        $description_clean = prepare_string($conn, $_POST['description']);
        // sanitize int using builtin filter
        $quantity_clean = filter_var(trim($_POST['quantity']), FILTER_SANITIZE_NUMBER_INT);
        // sanitize decimal, apply flags to preserve decimal points
        $price_clean = filter_var(trim($_POST['price']), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $ironsuit_id_clean = filter_var(trim($_POST['ironsuit_id']), FILTER_SANITIZE_NUMBER_INT);
        var_dump($ironsuit_id_clean);

        $query = "UPDATE ironsuits SET ironsuit_name = ?, ironsuit_color = ?, ironsuit_description = ?, ironsuit_quantity_available = ?, ironsuit_price = ? WHERE  ironsuit_id = ?;";

        // prepare the query and bind the parameters to protect from injection attacks
        $stmt = mysqli_prepare($conn, $query);

        mysqli_stmt_bind_param(
            $stmt,
            // types of the parameters
            'sssidi',
            $name_clean,
            $color_clean,
            $description_clean,
            $quantity_clean,
            $price_clean,
            $ironsuit_id_clean,
        );

        $result = mysqli_stmt_execute($stmt);

        // if update is successful redirect to details.php
        if ($result) {
            header("Location: details.php");
        } else {
            echo "<p class='error'>Some error in updating the data.</p>";
        }
    }
}
// for get request
else if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $ironsuit_id = null;
    // if no ironsuit id provided message and exit
    if (empty($_GET['ironsuit_id'])) {
        echo "<p class='error'>Error! Ironsuit Id not found.</p>";
        exit;
    } else {
        // get the ironsuit id from the get request
        $ironsuit_id = $_GET['ironsuit_id'];
        $query = "SELECT * FROM ironsuits WHERE ironsuit_id = $ironsuit_id;";
        $result = @mysqli_query($conn, $query);

        // get the ironsuit from the database and display it in the form
        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $name = $row['ironsuit_name'];
            $color = $row['ironsuit_color'];
            $description = $row['ironsuit_description'];
            $quantity = $row['ironsuit_quantity_available'];
            $price = $row['ironsuit_price'];
        } else {
            echo "<p class='error'>Error! cannot the particular iron suit from the database</p>";
            exit;
        }
    }
}
// do nothing for any other request
else {
    echo "The script only works with get and post requests.";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iron House</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>

    <nav>
        <ul class="navigation">
            <li class='item' id='add'><a href="index.php">Add</a></li>
            <li class='item' id='view'><a href="details.php">View</a></li>
        </ul>
    </nav>

    <div class="container">
        <div class="form-container">
            <form action="edit_ironsuit.php" class="form" method="POST" id="ironsuit_add_form">
                <!-- id for ironsuit_id is added to keep track of the ironsuit being updated -->
                <input type="hidden" name="ironsuit_id" value="<?php echo $ironsuit_id ?>">
                <label for="name">Name</label>
                <input type="text" name="name" id="ironsuit_name" class="input" value="<?php echo $name ?>">
                <label for="color">Color</label>
                <input type="text" name="color" id="ironsuit_color" class="input" value="<?php echo $color ?>">
                <label for="description">Description</label>
                <textarea name="description" id="ironsuit_description" class="input"
                    rows=10><?php echo $description ?></textarea>
                <label for="quantity">Quantity</label>
                <input type="text" name="quantity" id="ironsuit_quantity" class="input" value="<?php echo $quantity ?>">
                <label for="price">Price</label>
                <input type="text" name="price" id="ironsuit_price" class="input" value="<?php echo $price ?>">
                <button type="submit" class="submit" id="ironsuit_submit">Update Iron Suit</button>
            </form>
        </div>
    </div>
</body>

</html>