<?php

/**
 * Class Food stores the data relevant for a food item:
 *
 * Public fields:
 * type - short name that describes the general food type (pizza, burrito etc.)
 * name - a descriptive name that appears on the menu (Italian Pizza, Mexican Burrito etc.)
 * description - short explanation about the food item, enlisting the ingredients
 * price - the food base price, without extras or tax
 * quantity -  the number of food items ordered
 * toppings - user selected additions from the list of
 * extras - available topping choices a user can select from. The extras are food type specific.
 *
 * Class level variables:
 * tax - the decimal amount of sales tax
 * toppings_fee - all toppings have the same per topping fee.
 *
 * In addition to these fields, the class has a custom constructor,
 * one private method SetProperties() (used by the constructor), and
 * several public methods used for price calculations.
 *
 */
class Food
{
    //class fields
    public $type;
    public $name;
    public $description;
    public $price;
    public $quantity;
    //user selected additions
    public $toppings = array();
    //available extras(toppings) per food type
    public  $extras = array();
    //create class level property TAX
    public static $TAX = 0.096;
    private static $TOPPINGS_FEE = 0.75;

    //constructor
    /**
     * Food constructor.
     * @param $type
     * @param int $quantity
     * @param array $toppings
     *
     * The constructor calls the SetProperties() method that sets the properties' values
     * dependent on the food type
     */
    public function __construct($type, $quantity = 0, $toppings = array())
    {
        $this->type = $type;
        $this->quantity = $quantity;
        $this->toppings = $toppings;
        $this->SetProperties();
    }

    //methods

    /**
     *Sets the name, description, price and extras fields based on the food type.
     */
    private function SetProperties()
    {
        switch ($this->type) 
        {
            case "pizza":
                $this->name = "Italian Pizza";
                $this->description = "Delicious taste of Italy, with fresh mozzarella and olive oil";
                $this->price = 7.50;
                $this->extras = ["Peppers", "Mushrooms", "Tomatoes", "Pesto"];
                break;
            case "burrito":
                $this->name = "Mexican Burrito";
                $this->description = "¡Ay, caramba! Straight from burrito heaven to your table";
                $this->price = 5.25;
                $this->extras = ["Cilantro", "Sour Cream", "Guacamole", "Salsa"];
                break;
            case "salad":
                $this->name = "Greek Salad";
                $this->description = "Fresh like a summer breeze over the Mediterranean";
                $this->price = 4.50;
                $this->extras = ["Olives", "Feta Cheese", "Oregano", "Onions"];
                break;
            case "curry":
                $this->name = "Indian Curry";
                $this->description = "Spicy and rich vegetable curry, simply irresistible";
                $this->price = 7.25;
                $this->extras = ["Hot Peppers", "Coconut Chutney", "Raita"];
                break;
            default:
                $this->name = "Name not set";
                $this->description = "No description";
                $this->price = 0;
                $this->extras = [];
                break;
        }
    }
    /**
     * Calculates the base price for the number of the items ordered
     * @return string $basePrice formatted to two decimal places
    */
    public function CalculateBasePrice()
    {
        $basePrice = $this->price * $this->quantity;
        return number_format($basePrice, 2);
    }
    /**
     * Calculates the cost of the added toppings
     * Counts the number of toppings selected and multiplies it by the base toppings fee
     * @return string $toppingsCost formatted to two decimal places
     */
    public function CalculateToppingsCost()
    {
        $toppingsCost = count($this->toppings) * self::$TOPPINGS_FEE;
        return number_format($toppingsCost, 2);
    }

    /**
     * Calculates the total cost of all the selected toppings
     * @return string formatted to two decimal places
     */
    public function CalculateToppingsCostTotal()
    {
        $toppingsCostTotal = $this->CalculateToppingsCost() * $this->quantity;
        return number_format($toppingsCostTotal, 2);
    }

    /**
    *Calculates the total of each item price with toppings before tax.
     * This takes quantity into consideration
     * @return string formatted to two decimal places
    */
    
    public function CalculateSubtotalBeforeTax() 
    {
        $subtotalBT = ($this->price + $this->CalculateToppingsCost())* $this->quantity;
        return number_format($subtotalBT, 2);
    }


    /**
     * Calculates the price of the food item with tax
     * @return string formatted to two decimal places
     */
    public function CalculateTax() 
    {
        //$taxTotal = ($this->price + $this->CalculateToppingsCost()) * self::$TAX;
        $taxTotal = $this->CalculateSubtotalBeforeTax() * self::$TAX;
        return number_format($taxTotal, 2);
    }

    /**
     * Calculates the cost of ordering a food item
     * with toppings and tax included
     * @return string $subtotal formatted to two decimal places
     */
    public function CalculatePerItemSubtotal()
    {
        //@todo implement method
        //$subtotal = ($this->price  + $this->CalculateToppingsCost())  * (self::$TAX + 1);
        $subtotal = $this->CalculateSubtotalBeforeTax()  * (self::$TAX + 1);

        return number_format($subtotal, 2);
    }
}
