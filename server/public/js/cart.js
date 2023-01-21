document.addEventListener("DOMContentLoaded", function () {
    const checkboxes = document.querySelectorAll("input[type='checkbox']");
    const total = document.getElementById("total");


    //get total price from cart.php stored in $total variable and update total
    const totalPrice = document.getElementById("total").value;
    
    

    
    console.log(totalPrice);
    if(!isNaN(totalPrice)){
      checkboxes.forEach((checkbox) => {
      checkbox.addEventListener("change", (event) => {
        const productId = event.target.dataset.productId;
        const quantityInput = document.getElementById(`${productId}_quantity`);
        const quantity = "<?php echo ($quantity); ?>";
        const price = "<?php echo ($price); ?>";
  
        if (event.target.checked) {
          totalPrice += (price * quantity);
        } else {
          totalPrice -= (price * quantity);
        }
        total.innerHTML = `Total: &#8369; ${totalPrice.toFixed(2)}`;
      });
    });
    }
  });
  