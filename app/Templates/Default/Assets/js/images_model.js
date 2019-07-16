// Get the modal
var modal = document.getElementsByClassName("modal-img");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close-img")[0];

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  $('#myImg').modal('hide');
}
