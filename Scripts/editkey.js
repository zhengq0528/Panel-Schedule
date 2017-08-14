


var createkey = document.getElementById('nkeypop');
var close_ckey = document.getElementById('ckeypop');
var deletekey = document.getElementById('dkey');
var close_dkey = document.getElementById('cdkey');
$('#keycc').click(function(){
    createkey.style.display = "block";
});
close_ckey.onclick = function() {
  createkey.style.display = "none";
}

$('#delcc').click(function(){
    deletekey.style.display = "block";
});
close_dkey.onclick = function() {
  deletekey.style.display = "none";
}




$(document).ready(function()
{

  $(".editbox").mouseup(function()
  {
    return false
  });

  // Outside click action
  $(document).mouseup(function()
  {
    $(".editbox").hide();
    $(".text").show();
  });


  $(".rating").click(function()
  {
    var ID=$(this).attr('id');
    $("#rating_"+ID).hide();
    $("#rating_input_"+ID).show().focus();
  }).change(function()
  {
    var ID=$(this).attr('id');
    var uses=$("#rating_input_"+ID).val();
    var dataString = 'id='+ ID +'&rating='+uses;
    $.ajax({
      type: "POST",
      url: "../Database/edittran.php",
      data: dataString,
      cache: false,
      success: function(html)
      {
        $("#rating_"+ID).html(uses);
      }
    });
  });

  $(".remark").click(function()
  {
    var ID=$(this).attr('id');
    $("#remark_"+ID).hide();
    $("#remark_input_"+ID).show().focus();
  }).change(function()
  {
    var ID=$(this).attr('id');
    var uses=$("#remark_input_"+ID).val();
    var dataString = 'id='+ ID +'&remark='+uses;
    $.ajax({
      type: "POST",
      url: "../Database/edittran.php",
      data: dataString,
      cache: false,
      success: function(html)
      {
        $("#remark_"+ID).html(uses);
      }
    });
  });

  $(".keyname").click(function()
  {
    var ID=$(this).attr('id');
    $("#keyname_"+ID).hide();
    $("#keyname_input_"+ID).show().focus();
  }).change(function()
  {
    var ID=$(this).attr('id');
    var uses=$("#keyname_input_"+ID).val();
    var codes = ["p","P","t","T","l","L","r","R","m","M","E","e","X","x","S","s"];
    if(!codes.includes(uses))
    {
      var dataString = 'id='+ ID +'&keyname='+uses;
      $.ajax({
        type: "POST",
        url: "../Database/editkey.php",
        data: dataString,
        cache: false,
        success: function(html)
        {
          $("#keyname_"+ID).html(uses);
        }
      });
    }
    else
    {
      alert("Invalid Input, You can not define constant keys");
    }
  });

  $(".description").click(function()
  {
    var ID=$(this).attr('id');
    $("#description_"+ID).hide();
    $("#description_input_"+ID).show().focus();
  }).change(function()
  {
    var ID=$(this).attr('id');
    var uses=$("#description_input_"+ID).val();
    var dataString = 'id='+ ID +'&description='+uses;
    $.ajax({
      type: "POST",
      url: "../Database/editkey.php",
      data: dataString,
      cache: false,
      success: function(html)
      {
        $("#description_"+ID).html(uses);
      }
    });
  });

  $(".derating").click(function()
  {
    var ID=$(this).attr('id');
    $("#derating_"+ID).hide();
    $("#derating_input_"+ID).show().focus();
  }).change(function()
  {
    var ID=$(this).attr('id');
    var uses=$("#derating_input_"+ID).val();
    var dataString = 'id='+ ID +'&derating='+uses;
    $.ajax({
      type: "POST",
      url: "../Database/editkey.php",
      data: dataString,
      cache: false,
      success: function(html)
      {
        $("#derating_"+ID).html(uses);
      }
    });
  });

});
