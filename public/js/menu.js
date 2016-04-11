$(document).ready(function(){
  $('.dropdown a').on("click", function(e){
    $(this).next('ul').toggle();
    e.stopPropagation();
  });
});
