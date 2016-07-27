// click on tabs passed inside url after hash "#"
$(document).ready(function(){
  if(window.location.hash != "") {
      $('a[data-target="' + window.location.hash + '"]').click()
  }
});