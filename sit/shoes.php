<?php
include("config.php");

session_start();
$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    header('Location: /store.php');
    exit;
}

if (isset($_GET['logout'])) {
    unset($_SESSION['user_id']);
    session_destroy();
    header('Location: /store.php');
    exit;
}

$message = [];

if (isset($_POST['add_to_cart'])) {
    $product_name = mysqli_real_escape_string($con, $_POST['product_name']);
    $product_price = mysqli_real_escape_string($con, $_POST['product_price']);
    $product_image = mysqli_real_escape_string($con, $_POST['product_image']);
    $product_quantity = mysqli_real_escape_string($con, $_POST['product_quantity']);

    $select_cart = mysqli_query($con, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'");

    if (mysqli_num_rows($select_cart) > 0) {
        $message[] = 'Product already added to cart!';
    } else {
        mysqli_query($con, "INSERT INTO `cart`(user_id, name, price, image, quantity) VALUES('$user_id', '$product_name', '$product_price', '$product_image', '$product_quantity')");
        $message[] = 'Product added to cart!';
    }
}

if (isset($_POST['update_cart'])) {
    $update_quantity = mysqli_real_escape_string($con, $_POST['cart_quantity']);
    $update_id = mysqli_real_escape_string($con, $_POST['cart_id']);
    mysqli_query($con, "UPDATE `cart` SET quantity = '$update_quantity' WHERE id = '$update_id'");
    $message[] = 'Cart quantity updated successfully!';
}

if (isset($_GET['remove'])) {
    $remove_id = mysqli_real_escape_string($con, $_GET['remove']);
    mysqli_query($con, "DELETE FROM `cart` WHERE id = '$remove_id'");
    header('Location: godass.php');
    exit;
}

if (isset($_GET['delete_all'])) {
    mysqli_query($con, "DELETE FROM `cart` WHERE user_id = '$user_id'");
    header('Location: godass.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="godass.css">
</head>

<body>
    <div class="video-container">
        <video autoplay muted loop id="background-video">
            <source src="videos/2.mp4" type="video/mp4">
        </video>
    </div>
    <?php
    if (!empty($message)) {
        foreach ($message as $msg) {
            echo '<div class="message" onclick="this.remove();">' . $msg . '</div>';
        }
    }
    ?>

    <div class="container">
        <div class="user-profile">
            <?php
            $select_user = mysqli_query($con, "SELECT * FROM `users` WHERE id = '$user_id'");
            if (mysqli_num_rows($select_user) > 0) {
                $fetch_user = mysqli_fetch_assoc($select_user);
            }
            ?>
            <div class="flex">
                <a href="../store.php?logout=<?php echo $user_id; ?>" onclick="return confirm('Are you sure you want to logout?');" class="delete-btn">Logout</a>
            </div>
        </div>

        <div class="products">
            <h1 class="heading">Latest Products</h1>
            <div class="box-container">
                <?php
                $select_product = mysqli_query($con, "SELECT * FROM `products`");
                if (mysqli_num_rows($select_product) > 0) {
                    while ($fetch_product = mysqli_fetch_assoc($select_product)) {
                ?>
                        <form method="post" class="box" action="">
                            <img src="sitt/<?php echo $fetch_product['image']; ?>" alt="">
                            <div class="name"><?php echo $fetch_product['name']; ?></div>
                            <div class="price">$<?php echo $fetch_product['price']; ?>/-</div>
                            <input type="number" min="1" name="product_quantity" value="1">
                            <input type="hidden" name="product_image" value="<?php echo $fetch_product['image']; ?>">
                            <input type="hidden" name="product_name" value="<?php echo $fetch_product['name']; ?>">
                            <input type="hidden" name="product_price" value="<?php echo $fetch_product['price']; ?>">
                            <input type="submit" value="Add to Cart" name="add_to_cart" class="btn">
                        </form>
                <?php
                    };
                };
                ?>
            </div>
        </div>

        <div class="shopping-cart">
            <h1 class="heading">Shopping Cart</h1>
            <table>
                <thead>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th>Action</th>
                </thead>
                <tbody>
                    <?php
                    $cart_query = mysqli_query($con, "SELECT * FROM `cart` WHERE user_id = '$user_id'");
                    $grand_total = 0;
                    if (mysqli_num_rows($cart_query) > 0) {
                        while ($fetch_cart = mysqli_fetch_assoc($cart_query)) {
                            $sub_total = $fetch_cart['price'] * $fetch_cart['quantity'];
                            $grand_total += $sub_total;
                    ?>
                            <tr>
                                <td><img src="sitt/<?php echo $fetch_cart['image']; ?>" height="100" alt=""></td>
                                <td><?php echo $fetch_cart['name']; ?></td>
                                <td>$<?php echo $fetch_cart['price']; ?>/-</td>
                                <td>
                                    <form action="" method="post">
                                        <input type="hidden" name="cart_id" value="<?php echo $fetch_cart['id']; ?>">
                                        <input type="number" min="1" name="cart_quantity" value="<?php echo $fetch_cart['quantity']; ?>">
                                        <input type="submit" name="update_cart" value="Update" class="option-btn">
                                    </form>
                                </td>
                                <td>$<?php echo $sub_total; ?>/-</td>
                                <td><a href="godass.php?remove=<?php echo $fetch_cart['id']; ?>" class="delete-btn" onclick="return confirm('Remove item from cart?');">Remove</a></td>
                            </tr>
                    <?php
                        }
                    } else {
                        echo '<tr><td style="padding:20px; text-transform:capitalize;" colspan="6">No item added</td></tr>';
                    }
                    ?>
                    <tr class="table-bottom">
                        <td colspan="4">Grand Total :</td>
                        <td>$<?php echo $grand_total; ?>/-</td>
                        <td><a href="godass.php?delete_all" onclick="return confirm('Delete all from cart?');" class="delete-btn <?php echo ($grand_total > 1) ? '' : 'disabled'; ?>">Delete All</a></td>
                    </tr>
                </tbody>
            </table>
            <div class="cart-btn">
                <a href="achat.php" class="btn <?php echo ($grand_total > 1) ? '' : 'disabled'; ?>">Proceed to Checkout</a>
            </div>
        </div>
    </div>
</body>

</html>
