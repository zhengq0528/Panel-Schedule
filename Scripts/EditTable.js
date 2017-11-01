
$('#loadp').click(function(){
    document.getElementById('tloadp').submit();
});


//Set up the total connected load
if(document.getElementById('ts1'))
{
  document.getElementById('c1').innerHTML = "<center>"+ parseInt(document.getElementById('ts1').value)/1000 + " KVA</center>";
  document.getElementById('c2').innerHTML = "<center>"+ parseInt(document.getElementById('ts2').value)/1000 + " KVA</center>";
  if(document.getElementById('c3')!=null)
  document.getElementById('c3').innerHTML = "<center>"+ parseInt(document.getElementById('ts3').value)/1000 + " KVA</center>";
  var a = parseInt(document.getElementById('ts1').value);
  var b = parseInt(document.getElementById('ts2').value);
  var c = parseInt(document.getElementById('ts3').value);
  var stol = a+b+c; stol=stol/1000;
  document.getElementById('ct').innerHTML = "<center>"+ stol + " KVA</center>";
  var p_type = parseInt(document.getElementById('types').value);
  var ph3 = parseInt(document.getElementById('3volt').value);
  if(p_type==1)
  stol = ((stol*1000)/ph3).toFixed(2);
  else
  stol = ((stol*1000)/(Math.sqrt(3)*ph3)).toFixed(2);
  document.getElementById('cta').innerHTML ="<center>"+ stol + " AMPS</center>";
}

if(document.getElementById("idmin"))
var mi = document.getElementById("idmin").value;
if(document.getElementById("idmax"))
var ma = document.getElementById("idmax").value;
mi = parseInt(mi);
ma = parseInt(ma);
//for(var i = mi; i < ma ; i++)
//{
  //alert(i);
  //voltChecking(i);
//}

function dis(ID)
{
  var type = document.getElementById("type").value;
  var volt = document.getElementById("volt").value;
  if(document.getElementById("bftype_input_"+ID)!= null)
  {
    var bf = String(document.getElementById("bftype_input_"+ID).value);
  }
  else var bf = " ";
  //var str2= bf.substring(bf.lastIndexOf("-")+1,bf.lastIndexOf("P"));
  var p = bf.indexOf('-');
  var str1= bf.substring(0,parseInt(p)-1);
  var start_pos = bf.indexOf('-')+1;
  var str2 = bf[start_pos];
  var w1=0,w2=0,w3=0;
  if(document.getElementById("watts1_input_"+ID))
  {
    var w1 = document.getElementById("watts1_input_"+ID).value;
  }
  if(document.getElementById("watts2_input_"+ID))
  {
    var w2 = document.getElementById("watts2_input_"+ID).value;
  }
  if(document.getElementById("watts3_input_"+ID))
  {
    var w3 = document.getElementById("watts3_input_"+ID).value;
  }
  var count = 0;
  if(parseInt(w1)>0) count++;
  if(parseInt(w2)>0) count++;
  if(parseInt(w3)>0) count++;

  var arr = [parseInt(w1),parseInt(w2),parseInt(w3)];
  var max = volt * str1;
  var min = max * 0.8;
  var num = Math.max.apply(Math,arr);
  if(num >= max)
  {
    if(document.getElementById("bftype_"+ID))
    document.getElementById("bftype_"+ID).style.color = "red";
  }
  else if(num >= min)
  {
    document.getElementById("bftype_"+ID).style.color = "orange";
  }
  else
  {
    if(document.getElementById("bftype_"+ID))
    document.getElementById("bftype_"+ID).style.color = "black";
  }

  if(count > parseInt(str2) ||parseInt(str2) > 3)
  {
    document.getElementById("bftype_"+ID).style.color = "red";
  }
}
function three(ID)
{
  var type = document.getElementById("type").value;
  var volt = document.getElementById("volt").value;
  if(document.getElementById("bftype_input_"+ID)!= null)
  {
    var bf = String(document.getElementById("bftype_input_"+ID).value);
  }
  else var bf = " ";
  //var str2= bf.substring(bf.lastIndexOf("-")+1,bf.lastIndexOf("P"));
  var p = bf.indexOf('-');
  var str1= bf.substring(0,parseInt(p)-1);
  var start_pos = bf.indexOf('-')+1;
  var str2 = bf[start_pos];
  var w1=0,w2=0,w3=0;
  if(document.getElementById("watts1_input_"+ID))
  {
    var w1 = document.getElementById("watts1_input_"+ID).value;
  }
  if(document.getElementById("watts2_input_"+ID))
  {
    var w2 = document.getElementById("watts2_input_"+ID).value;
  }
  if(document.getElementById("watts3_input_"+ID))
  {
    var w3 = document.getElementById("watts3_input_"+ID).value;
  }
  var arr = [parseInt(w1),parseInt(w2),parseInt(w3)];
  var num = Math.max.apply(Math,arr);
  var max = volt * str1;
  var min = max * 0.8;
  //document.getElementById("uses_"+ID).innerHTML = max +"+"+min + w1;
  if(num >= max)
  {
    if(document.getElementById("bftype_"+ID)!=null)
    document.getElementById("bftype_"+ID).style.color = "red";
  }
  else if(parseInt(num) >= min)
  {
    document.getElementById("bftype_"+ID).style.color = "orange";
  }
  else
  {
    if(document.getElementById("bftype_"+ID))
    document.getElementById("bftype_"+ID).style.color = "black";
  }

  if(parseInt(str2)==2)
  {
    var po = parseInt(ID);
    var po = po+2;
    if(document.getElementById("bftype_input_"+po))
    {
      var tmp =  document.getElementById("bftype_input_"+po).value;
      if(tmp!=0)
      {
        document.getElementById("bftype_"+ID).style.color = "red";
      }
    }
  }
  if(parseInt(str2)==3)
  {
    var po = parseInt(ID);
    var po = po+2;
    var po1 = po +2;
    if(document.getElementById("bftype_input_"+po))
    {
      var tmp =  document.getElementById("bftype_input_"+po).value;
      if(tmp!=0)
      {
        document.getElementById("bftype_"+ID).style.color = "red";
      }
    }
    if(document.getElementById("bftype_input_"+po1))
    {
      var tmp =  document.getElementById("bftype_input_"+po1).value;
      if(tmp!=0)

      {
        document.getElementById("bftype_"+ID).style.color = "red";
      }
    }
  }

  if(parseInt(str2) > 3)
  {
    document.getElementById("bftype_"+ID).style.color = "red";
  }

}
function single(ID)
{
  var type = document.getElementById("type").value;
  var volt = document.getElementById("volt").value;
  if(document.getElementById("bftype_input_"+ID)!= null)
  {
    var bf = String(document.getElementById("bftype_input_"+ID).value);
  }
  else var bf = " ";
  var p = bf.indexOf('-');
  var str1= bf.substring(0,parseInt(p)-1);
  var start_pos = bf.indexOf('-')+1;
  var str2 = bf[start_pos];
  var w1=0,w2=0,w3=0;
  if(document.getElementById("watts1_input_"+ID))
  {
    var w1 = document.getElementById("watts1_input_"+ID).value;
  }
  if(document.getElementById("watts2_input_"+ID))
  {
    var w2 = document.getElementById("watts2_input_"+ID).value;
  }
  if(document.getElementById("watts3_input_"+ID))
  {
    var w3 = document.getElementById("watts3_input_"+ID).value;
  }
  var arr = [parseInt(w1),parseInt(w2),parseInt(w3)];
  var num = Math.max.apply(Math,arr);
  var max = volt * str1;
  var min = max * 0.8;
  //document.getElementById("uses_"+ID).innerHTML = max +"+"+min + w1;
  if(num >= max)
  {
    if(document.getElementById("bftype_"+ID)!=null)
    document.getElementById("bftype_"+ID).style.color = "red";
  }
  else if(parseInt(num) >= min)
  {
    document.getElementById("bftype_"+ID).style.color = "orange";
  }
  else
  {
    if(document.getElementById("bftype_"+ID))
    document.getElementById("bftype_"+ID).style.color = "black";
  }

  if(parseInt(str2)==2)
  {
    var po = parseInt(ID);
    var po = po+2;
    if(document.getElementById("bftype_input_"+po))
    {
      var tmp =  document.getElementById("bftype_input_"+po).value;
      if(tmp!=0)
      {
        document.getElementById("bftype_"+ID).style.color = "red";
      }
    }
  }

  if(parseInt(str2) > 2)
  {
    document.getElementById("bftype_"+ID).style.color = "red";
  }

}

function voltChecking(ID)
{
  var type = document.getElementById("types").value;
  if(parseInt(type)==3)
  {
    dis(ID);
  }
  else if(parseInt(type)==2)
  {
    three(ID);
  }
  else if(parseInt(type)==1)
  {
    single(ID);
  }
}
var active = 4;
var a = 0;
$(document).ready(function()
{

  $('#mydata tbody').on( 'focus' , 'td', function (event) {
    active = $(this).closest('table').find('td').index(this);
  });
  $('input').keydown(function(event) {

    /*
    var columns =  $('#mydata tfoot th').length + 3;
    var rows = $('#mydata td').length;
    var c = $('#mydata tfoot th').length;
    if (event.keyCode == 37) { //move left
      $(this).trigger('select');
      a = active - 1;
      if(a >= 0)
      {
        active = active - 1;
        $('.two').removeClass('two');
        $('#mydata tr td').eq(active).addClass('two').trigger( "focus" );
        //$(this).select();
        //$(this).trigger('select');

      }
    }
    if (event.keyCode == 39) { //move right
      $(this).trigger('select');
      a = active + 1;
      if(a < rows)
      {
        active = active + 1;
        $('.two').removeClass('two');
        $('#mydata tr td').eq(active).addClass('two').trigger( "focus" );
        //$(this).select();
        //$(this).trigger('select');
      }
    }
    if (event.keyCode == 38) { //move up
      $(this).trigger('select');
      a = active - c;
      if(a >= 0)
      {
        active = active - c;
        $('.two').removeClass('two');
        $('#mydata tr td').eq(active).addClass('two').trigger( "focus" );
        //$(this).select();
        //$(this).trigger('select');
      }
    }
    if (event.keyCode == 40) { //move down
      $(this).trigger('select');
      a = active + c;
      if(a < rows)
      {
        active = active + c;
        $('.two').removeClass('two');
        $('#mydata tr td').eq(active).addClass('two').trigger( "focus" );
        //$(this).select();
      //$(this).trigger('select');
      }
    }
    */

    if (event.keyCode == 13) {
      $(".editbox").hide();
      $(".text").show().trigger( "focus" );
      $('.two').removeClass('two');
      var ID=$(this).attr('id');
      var uses=$("#"+ID).val();
      var res = ID.split("_");
      var ips = res[0]+"_"+res[1]
      var dataString = 'id='+ res[2] +'&'+res[0]+'='+uses;
      $.ajax({
        type: "POST",
        url: "../Database/editpaneltable.php",
        data: dataString,
        cache: false,
        success: function(html)
        {
          $("#"+res[0]+"_"+res[2]).html(uses);
        }
      });
    }
    //$(this).select();
    //$(this).find('option:selected').text();
  });

  // Edit input box click action
  $(".editbox").mouseup(function()
  {
    $('.active').removeClass('active');
    $('.two').removeClass('two');
    return false;
  });

  $(".uses").focus(function()
  {
    $(".editbox").hide();
    $(".text").show();

    var ID=$(this).attr('id');
    $("#uses_"+ID).hide();
    $("#uses_input_"+ID).show();
    $("#uses_input_"+ID).focus().select();
    //$(this).keyup(function(event) {
    //  $("#uses_input_"+ID).select();
    //});
    //alert(ID);

    //$(this);
  }).change(function()
  {
    var ID=$(this).attr('id');
    $(this).select();
    var uses=$("#uses_input_"+ID).val();
    var dataString = 'id='+ ID +'&uses='+uses;
    $.ajax({
      type: "POST",
      url: "../Database/editpaneltable.php",
      data: dataString,
      cache: false,
      success: function(html)
      {
        $("#uses_"+ID).html(uses);
      }
    });
  });

  $(".watts1").focus(function()
  {
    $(".editbox").hide();
    $(".text").show();
    var ID=$(this).attr('id');
    $("#watts1_"+ID).hide();
    $("#watts1_input_"+ID).show().focus().select();
    //$(this).keyup(function(event) {
    //  $("#watts1_input_"+ID).select();
    //});
    var wdate = document.getElementById("watts1_input_"+ID).value;
    if(wdate == 0) document.getElementById("watts1_input_"+ID).value = null;
  }).change(function()
  {
    var ID=$(this).attr('id');
    var uses=$("#watts1_input_"+ID).val();
    var dataString = 'id='+ ID +'&watts1='+uses;
    var tmp = parseInt(document.getElementById('watts1_'+ID).innerHTML);
    if(isNaN(tmp)) tmp = 0;
    $.ajax({
      type: "POST",
      url: "../Database/editpaneltable.php",
      data: dataString,
      cache: false,
      success: function(html)
      {
        var s1 = document.getElementById('s1').innerHTML;
        $("#watts1_"+ID).html(uses);
        var tmp1 = parseInt(uses);
        if(isNaN(tmp1)) tmp1 = 0;
        voltChecking(ID);
        var s1;
        var totalid;
        if(ID%2 == 1)
        {
          s1 = document.getElementById('s1').innerHTML;
          totalid = 's1';
        }
        else
        {
          s1 = document.getElementById('s4').innerHTML;
          totalid = 's4';
        }
        var tmpStr  = s1.match(">(.*)<");
        s1 = tmpStr[1];
        var s1i = parseInt(s1);
        s1i += tmp1 - tmp;
        document.getElementById(totalid).innerHTML = "<center>"+s1i+"</center>";
      }
    });
  });

  $(".watts2").focus(function()
  {
    $(".editbox").hide();
    $(".text").show();
    var ID=$(this).attr('id');
    $("#watts2_"+ID).hide();
    $("#watts2_input_"+ID).show().focus().select();
    //$(this).keyup(function(event) {
    //  $("#watts2_input_"+ID).select();
    //});
    var wdate = document.getElementById("watts2_input_"+ID).value;
    if(wdate == 0) document.getElementById("watts2_input_"+ID).value = null;
  }).change(function()
  {
    var ID=$(this).attr('id');
    var uses=$("#watts2_input_"+ID).val();
    var dataString = 'id='+ ID +'&watts2='+uses;
    var tmp = parseInt(document.getElementById('watts2_'+ID).innerHTML);
    if(isNaN(tmp)) tmp = 0;
    $.ajax({
      type: "POST",
      url: "../Database/editpaneltable.php",
      data: dataString,
      cache: false,
      success: function(html)
      {
        $("#watts2_"+ID).html(uses);
        voltChecking(ID);
        var tmp1 = parseInt(uses);
        if(isNaN(tmp1)) tmp1 = 0;
        voltChecking(ID);
        var s1; var totalid;
        if(ID%2 == 1)
        {
          s1 = document.getElementById('s2').innerHTML;
          totalid = 's2';
        }
        else
        {
          s1 = document.getElementById('s5').innerHTML;
          totalid = 's5';
        }
        var tmpStr  = s1.match(">(.*)<");
        s1 = tmpStr[1];
        var s1i = parseInt(s1);
        s1i += tmp1 - tmp;
        document.getElementById(totalid).innerHTML = "<center>"+s1i+"</center>";
      }
    });
  });
  $(".watts3").focus(function()
  {
    $(".editbox").hide();
    $(".text").show();
    var ID=$(this).attr('id');
    $("#watts3_"+ID).hide();
    $("#watts3_input_"+ID).show().focus().select();
    //$(this).keyup(function(event) {
    //  $("#watts3_input_"+ID).select();
    //});
    var wdate = document.getElementById("watts3_input_"+ID).value;
    if(wdate == 0) document.getElementById("watts3_input_"+ID).value = null;
  }).change(function()
  {
    var ID=$(this).attr('id');
    var uses=$("#watts3_input_"+ID).val();
    var dataString = 'id='+ ID +'&watts3='+uses;
    var tmp = parseInt(document.getElementById('watts3_'+ID).innerHTML);
    if(isNaN(tmp)) tmp = 0;
    $.ajax({
      type: "POST",
      url: "../Database/editpaneltable.php",
      data: dataString,
      cache: false,
      success: function(html)
      {
        $("#watts3_"+ID).html(uses);
        voltChecking(ID);
        var tmp1 = parseInt(uses);
        if(isNaN(tmp1)) tmp1 = 0;
        voltChecking(ID);
        var s1;
        var totalid;
        if(ID%2 == 1)
        {
          s1 = document.getElementById('s3').innerHTML;
          totalid = 's3';
        }
        else
        {
          s1 = document.getElementById('s6').innerHTML;
          totalid = 's6';
        }
        var tmpStr  = s1.match(">(.*)<");
        s1 = tmpStr[1];
        var s1i = parseInt(s1);
        s1i += tmp1 - tmp;
        document.getElementById(totalid).innerHTML = "<center>"+s1i+"</center>";
      }
    });
  });

  $(".code").focus(function()
  {
    $(".editbox").hide();
    $(".text").show();
    var ID=$(this).attr('id');
    $("#code_"+ID).hide();
    $("#code_input_"+ID).show().focus().select();
    //$(this).keyup(function(event) {
    //  $("#code_input_"+ID).select();
    //});
  }).change(function()
  {
    var codes = ["p","P","t","T"];

    var ID=$(this).attr('id');
    var code=$("#code_input_"+ID).val();
    if(!codes.includes(code))
    {
      var dataString = 'id='+ ID +'&code='+code;
      $.ajax({
        type: "POST",
        url: "../Database/editpaneltable.php",
        data: dataString,
        cache: false,
        success: function(html)
        {
          $("#code_"+ID).html(code);
        }
      });
    }
    else
    {
      alert("Invalid Input, no t and p");
    }
  });


  $(".bftype").focus(function()
  {
    $(".editbox").hide();
    $(".text").show();
    var ID=$(this).attr('id');
    $("#bftype_"+ID).hide();
    $("#bftype_input_"+ID).show().focus().select();
    //$(this).keyup(function(event) {
    //  $("#bftype_input_"+ID).select();
    //});
  }).change(function()
  {
    var ID=$(this).attr('id');
    var uses=$("#bftype_input_"+ID).val();
    var dataString = 'id='+ ID +'&bftype='+uses;
    $.ajax({
      type: "POST",
      url: "../Database/editpaneltable.php",
      data: dataString,
      cache: false,
      success: function(html)
      {
        $("#bftype_"+ID).html(uses);
        voltChecking(ID);
        document.getElementById("bftype_"+ID).style.visibility="visible";
      }
    });
  });

  $(".breaker").focus(function()
  {
    $(".editbox").hide();
    $(".text").show();
    var ID=$(this).attr('id');
    $("#breaker_"+ID).hide();
    $("#breaker_input_"+ID).show().focus().select();
    //$(this).keyup(function(event) {
    //  $("#breaker_input_"+ID).select();
    //});
  }).change(function()
  {
    var ID=$(this).attr('id');
    var uses=$("#breaker_input_"+ID).val();
    var dataString = 'id='+ ID +'&breaker='+uses;
    $.ajax({
      type: "POST",
      url: "../Database/editpaneltable.php",
      data: dataString,
      cache: false,
      success: function(html)
      {
        $("#breaker_"+ID).html(uses);
        document.getElementById("breaker_"+ID).style.visibility="visible";
      }
    });
  });

  $(".fuse").focus(function()
  {
    $(".editbox").hide();
    $(".text").show();
    var ID=$(this).attr('id');
    $("#fuse_"+ID).hide();
    $("#fuse_input_"+ID).show().focus().select();
    //$(this).keyup(function(event) {
    //  $("#fuse_input_"+ID).select();
    //});
  }).change(function()
  {
    var ID=$(this).attr('id');
    var uses=$("#fuse_input_"+ID).val();
    var dataString = 'id='+ ID +'&fuse='+uses;
    $.ajax({
      type: "POST",
      url: "../Database/editpaneltable.php",
      data: dataString,
      cache: false,
      success: function(html)
      {
        $("#fuse_"+ID).html(uses);
        document.getElementById("fuse_"+ID).style.visibility="visible";
      }
    });
  });

  // Outside click action
  $(document).mouseup(function()
  {
    $('.active').removeClass('active');
    $('.two').removeClass('two');
    $(".editbox").hide();
    $(".text").show();
  });

});
