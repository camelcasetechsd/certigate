// click on tabs passed inside url after hash "#"
$(document).ready(function(){
  if(window.location.hash != "") {
      $('a[href="' + window.location.hash + '"]').click()
  }
});