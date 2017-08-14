var copy = document.getElementById('copy');
var copyclose = document.getElementById("copyclose");
$('#myBtn2').click(function(){
    copy.style.display = "block";
});
copyclose.onclick = function() {
    copy.style.display = "none";
}
