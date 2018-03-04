$(function () {
   const qtyField =   $('.qty');


   qtyField.keyup(changeUrl);
   
   function changeUrl() {
      let id = $(this).attr('id');
      let quantity = $(this).val();
      $(this).next().attr("href", "/shoppingCart/" + id + "/setQuantity/" + quantity);
   }
});