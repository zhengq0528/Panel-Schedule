var modal = document.getElementById('myModal');
var modal1 = document.getElementById('myModal1');
var span = document.getElementsByClassName("close")[0];
var span1 = document.getElementsByClassName("close")[1];
$('#myBtn').click(function(){
    modal.style.display = "block";
});
$('#myBtn1').click(function(){
    modal1.style.display = "block";
});
span.onclick = function() {
    modal.style.display = "none";
}
span1.onclick = function() {
    modal1.style.display = "none";
}

window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
    if (event.target == modal1) {
        modal1.style.display = "none";
    }
    if (event.target == copy) {
        copy.style.display = "none";
    }
}
function verifyorder(message) {
  document.getElementById('error').innerHTML = message;
}
