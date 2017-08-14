if(document.getElementById('types')==null)
{
  $('#link').click(function(){
    alert("You Are Not Connecting Any Panel");
  });
  $('#trans').click(function(){
    alert("You Are Not Connecting Any Panel");
  });
}
else if(document.getElementById('types').value ==4)
{
  $('#link').click(function(){
    alert("Isolation Panel Can Not Link To Any Panels");
  });
  $('#trans').click(function(){
    alert("You Are Not Connecting Any Panel");
  });
}
else
{
  var ptl = document.getElementById('types').value;
  var linkp1 = document.getElementById('lk1');
  var clinkp1 = document.getElementById('clk1');
  $('#link').click(function(){
    linkp1.style.display = "block";
  });
  clinkp1.onclick = function() {
    linkp1.style.display = "none";
  }

  $('#trans').click(function(){
    document.getElementById('tf1').style.display = "block";
  });
  document.getElementById('ctf1').onclick = function() {
    document.getElementById('tf1').style.display = "none";
  }
}
if(document.getElementById('clk2')!=null)
{
  var clinkp2 = document.getElementById('clk2');
  clinkp2.onclick = function() {
    document.getElementById('lk2').style.display = "none";
  }

}

if(document.getElementById('dsc')!=null)
{
  $('#dic').click(function(){
    document.getElementById('dsc').style.display = "block";
  });
  document.getElementById('cdsc').onclick = function() {
    document.getElementById('dsc').style.display = "none";
  }
}
if(document.getElementById('ctf2'))
document.getElementById('ctf2').onclick = function() {
  document.getElementById('tf2').style.display = "none";
}
