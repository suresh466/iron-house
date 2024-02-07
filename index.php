<?php

require('db_conn.php');
// handle Post request here, Get request handled by default, and disallow other request in the else block
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

        // quantity must be an integer
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

    // if there are errors, display them
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<p class='error'>$error</p>";
        }
    } else {


        // sanitize string
        $name_clean = prepare_string($conn, $_POST['name']);
        $color_clean = prepare_string($conn, $_POST['color']);
        $description_clean = prepare_string($conn, $_POST['description']);
        // sanitize int using builtin filter
        $quantity_clean = filter_var(trim($_POST['quantity']), FILTER_SANITIZE_NUMBER_INT);
        // sanitize decimal, apply flags to preserve decimal points
        $price_clean = filter_var(trim($_POST['price']), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

        $query = "INSERT INTO ironsuits (ironsuit_name, ironsuit_color, ironsuit_description, ironsuit_quantity_available, ironsuit_price)
        VALUES (?,?,?,?,?)";

        // prepare the query and bind the parameters, to protect from injection attacks
        $stmt = mysqli_prepare($conn, $query);

        mysqli_stmt_bind_param(
            $stmt,
            // types of the parameters
            'sssid',
            $name_clean,
            $color_clean,
            $description_clean,
            $quantity_clean,
            $price_clean
        );

        $result = mysqli_stmt_execute($stmt);

        // if success redirect to detail page
        if ($result) {
            header("Location: details.php");
        } else {
            echo "<p class='error'>Some error in Saving the data</p>";
        }
    }
}
// if the request method is not post, then it must be get, other requests are not allowed
else if ($_SERVER['REQUEST_METHOD'] != 'GET') {
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
            <form action="index.php" class="form" method="POST" id="ironsuit_add_form">
                <label for="name">Name</label>
                <input type="text" name="name" id="ironsuit_name" class="input">
                <label for="color">Color</label>
                <input type="text" name="color" id="ironsuit_color" class="input">
                <label for="description">Description</label>
                <textarea name="description" id="ironsuit_description" class="input" rows=10></textarea>
                <label for="quantity">Quantity</label>
                <input type="text" name="quantity" id="ironsuit_quantity" class="input">
                <label for="price">Price</label>
                <input type="text" name="price" id="ironsuit_price" class="input">
                <button type="submit" class="submit" id="ironsuit_submit">Add Iron Suit</button>
            </form>
        </div>
    </div>
</body>

</html>