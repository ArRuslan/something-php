<?php

include "constants.php";
global $PRODUCTS;

if(session_status() !== PHP_SESSION_ACTIVE)
    session_start();

if (!isset($_SESSION["cart"]))
    $_SESSION["cart"] = array();

?>

<div style="display: flex; flex-direction: column; align-items: center; width: 100%; margin-top: 15px; overflow-y: scroll">
    <div>
        <h3>
            <a href="/lb1">Go to main page</a>
        </h3>

        <?php if (!count($_SESSION["cart"])) { ?>
            <h3>No items in cart</h3>
        <?php } else { ?>
            <h3>Items in cart:</h3>
        <?php } ?>

        <?php for ($i = 0; $i < count($_SESSION["cart"]); $i++) { ?>
            <p style="font-size: 16pt"><?php echo $PRODUCTS[$_SESSION["cart"][$i]["id"]]["name"]; ?>
                - x<?php echo $_SESSION["cart"][$i]["count"]; ?></p>
        <?php } ?>
    </div>
</div>


