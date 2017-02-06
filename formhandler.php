<?php
/**
 * formhandler.php checks if the user input was valid,
 * stores the user data into an array of Food objects, calls the calculation methods
 * and displays the order summary, subtotal(per item) price
 * (with detailed price breakdown the user can see if they click a button),
 * as well as the total price for the order.
 *
 */
require 'food.php';
include 'includes/header.php';

//check if the input is valid
if(!isset($_POST["items"])) {
    echo '<div class = "col-md-4 col-md-offset-4">
    <h5 class="errorMsg">Undecided what you want to order? We suggest you order one of each!</h5>
    <a href = "index.php">Take me back</a>';
} else {
    //loop through the $_POST array and create an array of Food objects the user ordered
    for ($i = 0; $i < count($_POST["items"]); $i++) {

        //store object parameters from the $_POST array into variables
        $type = $_POST["items"][$i];
        $quantity = $_POST["quantity"][$i];
        
        //check if toppings were selected
        if(isset($_POST["topping" . $i])) {
            $toppings = $_POST["topping" . $i];
        } else {
            $toppings = [];
        }
        /** @var array $foodOrder stores the ordered food items*/
        $foodOrder[] = new Food($type, $quantity, $toppings);
    }

    //create the order summary showing all the items and toppings ordered,
    //the subtotal for each item, and a cumulative total cost due.
    $total = 0;
    foreach ($foodOrder as $food) {
        echo '<div class = "orderSummary menuItem col-md-6 col-md-offset-3">
              
        <h5 class="foodName">' . $food->name . ' x ' . $food->quantity . '</h5>
        <p class="foodName cost">$' . $food->calculatePerItemSubtotal() . ' </p>
        <button type="button" class="btn details"><i class="fa fa-chevron-down"></i></button>
        <div class = "priceDetails hide" >
        <p>Base price:(' . $food->price . ' /each)</p>
        <p class="cost">$' . $food->calculateBasePrice() . ' </p>';

        //don't display toppings price if no toppings were selected
        if($toppings != []) {
            echo '<p>+' . implode(", ", $food->toppings) . '(' . $food->calculateToppingsCost() . ' /each) </p>
            <p class="cost">$' . $food->calculateToppingsCostTotal() . '  </p>';
        }//end of if statement

        echo '
        <!-- added by Ayumi 2/3-->
        <!-- <p>Subtotal before tax (' . $food->quantity . ' orders): </p>
        <p class="cost">$' . $food->calculateSubtotalBeforeTax() . ' </p>-->
              
        <p>Tax (9.6%)</p>
        <p class="cost">$' . $food->calculateTax() . ' </p>
        <hr>
        <p>Subtotal:</p>
        <p class="cost">$' . $food->calculatePerItemSubtotal() . ' </p>             
        </div>
        </div>';
    
        //calculate total
        $total += $food->calculatePerItemSubtotal();
    }//end of foreach loop

    //display total
    echo '<div id="finalPrice" class = "orderSummary menuItem col-md-6 col-md-offset-3">
    <h5 class="total">Total price:</h5>
    <p class="total cost">$' . number_format($total, 2) . ' </p>
    </div>';
}

include 'includes/footer.php';
