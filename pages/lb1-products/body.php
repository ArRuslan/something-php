<?php

include "constants.php";
global $PRODUCTS;

?>

<div style="display: flex; flex-direction: column; align-items: center; width: 100%; margin-top: 15px; overflow-y: scroll">
    <h3>
        <a href="/lb1-cart">Go to cart</a>
    </h3>
    <div style="display: flex; flex-direction: row; gap: 5px; flex-wrap: wrap; justify-content: space-between; margin: 0 20px;">
        <?php for ($i = 0; $i < count($PRODUCTS); $i++) { ?>
            <form action="/lb1_add_to_cart.php" method="POST"
                  style="margin: 2px; padding: 7px; border: 1px solid black; border-radius: 7px; text-align: center">
                <p style="width: 100%"><?php echo $PRODUCTS[$i]["name"]; ?></p>
                <p style="width: 100%">$ <?php echo $PRODUCTS[$i]["price"]; ?></p>
                <input type="hidden" name="id" value="<?php echo $i; ?>"/>
                <label>
                    Count:
                    <input type="number" name="count" value="1"/>
                </label>
                <button type="submit" style="background-color: black; color: white; border-radius: 5px">Add to cart</button>
            </form>
        <?php } ?>
    </div>
</div>


