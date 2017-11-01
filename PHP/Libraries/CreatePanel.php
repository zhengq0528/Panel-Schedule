<?php

//Draw a editing panel
function draw_edit_panel($_PanelName,$mid,$cid,$c)
{

  $editpanel = new popwin("Updating $_PanelName",1,$mid,$cid);
  $editpanel->draw_head();
  $editpanel->draw_table_s();
  $svs = $c->get_service();
  //echo "<script>alert('$svs');</script>";
  edit_Services($c->get_intype(),$svs);
  echo "<input type='hidden' name ='panel' value ='$_PanelName'>";
  //Panel Location Field
  $c_data = array("text","location",$c->get_location(),"","style='text-transform:uppercase' maxlength='25'");
  $editpanel->draw_content("<b>Location:",$c_data);

  //PanelMouting Options
  $arr_builder = array(
    $c->get_mounting() =>$c->get_mounting(),
    'Surface' => "Surface",
    'Flush' => "Flush",
  );
  $editpanel->draw_selection("<center><b>Mounting:</center>",'mounting',$arr_builder);

  //Main Breaker field
  $c_data = array("text","breaker",$c->get_breaker(),"","style='text-transform:uppercase' maxlength='10'");
  $editpanel->draw_content("<b>Main Breaker:",$c_data);

  //Main Lugs field
  $c_data = array("text","lug",$c->get_lugs(),"","style='text-transform:uppercase' maxlength='10'");
  $editpanel->draw_content("<b>Main Lugs:",$c_data);

  //Ground Bus field
  $arr_builder = array(
    $c->get_bus() => $c->get_bus(),
    'Yes' => "Yes",
    'No' => "No",
  );
  $editpanel->draw_selection("<center><b>Ground Bus:</center>",'groundbus',$arr_builder);

  //Short Circuit Rating field
  $c_data = array("text","rating",$c->get_rating(),"","");
  $editpanel->draw_content("<b>Short Circuit Rating:",$c_data);

  //Note Field
  $c_data = array("long-text","note",$c->get_note(),"","style='text-transform:uppercase; width:1100px;' maxlength='255'");
  $editpanel->draw_content("<b>Note:",$c_data);

  if($c->get_intype() ==3)
  {
    if($c->fuse == 1)
    {
      echo "
      <tr><td><b><center>Switch and Fuse: </center></td>
      <td><center><select id = 2 name='fuse' style='width:175px;height:30px;'>
      <option value='1'>Yes</option>
      <option value='0'>No</option>
      </select></center></td></tr>
      ";
    }
    else
    {
      echo "
      <tr><td><b><center>Switch and Fuse: </center></td>
      <td><center><select id = 2 name='fuse' style='width:175px;height:30px;'>
      <option value='0'>No</option>
      <option value='1'>Yes</option>
      </select></center></td></tr>
      ";
    }
  }
  else if($c->get_intype() ==4)
  {
    edittranp($c->transize,$c->secvol,$c->monitor);
  }


  $editpanel->draw_table_e();
  echo "<input type='submit' name='updatepanel' value='updatepanel' class ='button'>";
  $editpanel->draw_foot();


}

//Able to draw panel cration by the type
function draw_create_panel($_PanelType,$_PanelName,$mid,$cid,$s_name,$s_value)
{
  //Note that Panel Type value by integer here is different
  $createpanel = new popwin($_PanelName,$_PanelType,$mid,$cid);
  $createpanel->draw_head();
  $createpanel->draw_table_s();

  //Panel Name Field
  $c_data = array("text","panel","","","style='text-transform:uppercase' maxlength='8'");
  $createpanel->draw_content("<b>Panel:",$c_data);

  ulta_panel($_PanelType);

  //Panel Services Selection
  Create_Services($_PanelType,"");

  //Panel Location Field
  $c_data = array("text","location","","","style='text-transform:uppercase' maxlength='25'");
  $createpanel->draw_content("<b>Location:",$c_data);

  //Panel Circuit Number Selection
  Create_Circuits($_PanelType);

  //PanelMouting Options
  $arr_builder = array(
    'Surface' => "Surface",
    'Flush' => "Flush",
  );
  $createpanel->draw_selection("<center><b>Mounting:</center>",'mounting',$arr_builder);

  //Main Breaker field
  $c_data = array("text","breaker","","","style='text-transform:uppercase' maxlength='10'");
  $createpanel->draw_content("<b>Main Breaker:",$c_data);

  //Main Lugs field
  $c_data = array("text","lug","","","style='text-transform:uppercase' maxlength='10'");
  $createpanel->draw_content("<b>Main Lugs:",$c_data);

  //Ground Bus field
  $arr_builder = array(
    'Yes' => "Yes",
    'No' => "No",
  );
  $createpanel->draw_selection("<center><b>Ground Bus:</center>",'groundbus',$arr_builder);

  //Short Circuit Rating field
  $c_data = array("text","rating","","","");
  $createpanel->draw_content("<b>Short Circuit Rating:",$c_data);

  //Note Field
  $c_data = array("long-text","note","","","style='text-transform:uppercase;width:1100px;' maxlength='255' ");
  $createpanel->draw_content("<b>Note:",$c_data);

  Additional_Field($_PanelType);
  //End part, which create the finishing line
  $createpanel->draw_table_e();
  echo "<input type='submit' name='$s_name' value='$s_value' class ='button'>";
  $createpanel->draw_foot();
}

function Create_Circuits($_PanelType)
{
  echo "<tr> <td> <center><b>Number of Circuits: </center></td> <td><center>";
  if($_PanelType==1)
  {
    echo "<select id = 1 name='circuit' style='width:175px;height:30px;'>";
    echo "<option value='16'>16</option>";
    $index = 2;
    for($index = 1;$index < 84; $index++)
    {
      $index++;
      echo "<option value='$index'>$index</option>";
    }
    echo "</select>";
  }
  else if($_PanelType==2)
  {
    echo "<select id = 1 name='circuit' style='width:175px;height:30px;'>";
    echo "<option value='30'>30</option>";
    for($index = 1;$index < 84; $index++)
    {
      $index+=5;
      echo "<option value='$index'>$index</option>";
    }
    echo "</select>";
  }
  else if($_PanelType==3)
  {
    echo "<select id = 1 name='circuit' style='width:175px;height:30px;'>";
    echo "<option value='4'>4</option>";
    echo "<option value='6'>6</option>";
    echo "<option value='8'>8</option>";
    echo "<option value='12'>12</option>";
    echo "<option value='16'>16</option>";
    echo "<option value='20'>20</option>";
    echo "<option value='24'>24</option>";
    echo "<option value='30'>30</option>";
    echo "<option value='32'>32</option>";
    echo "<option value='36'>36</option>";
    echo "<option value='40'>40</option>";
    echo "<option value='42'>42</option>";
    echo "</select>";
  }
  else if($_PanelType==4)
  {
    echo "<select id = 1 name='circuit' style='width:175px;height:30px;'>";
    echo "<option value='8'>8</option>";
    echo "<option value='10'>10</option>";
    echo "<option value='12'>12</option>";
    echo "<option value='14'>14</option>";
    echo "<option value='16'>16</option>";
    echo "</select>";
  }
  else if($_PanelType==5)
  {
    echo "<select id='slc2' name = 'circuit' style='width:175px;height:30px;'>";
    echo "<option data-group='SHOW' value='0'>-- Select --</option>";
    echo "</select></td></tr>";
  }
  echo "</center></td></tr>";
}

//Ulta Panel Select
function ulta_panel($_PanelType)
{
  //Only use for ULTA Panels
  if($_PanelType == 5)
  {
    ?>
    <tr><td><center><b>Panel Type: </center></td>
      <td><center><select id='catagory' data-child='family' name = 'type' onchange="populate(this.id,'slc2','slc3')">
        <option selected disabled>Select Device Catagory</option>
        <option value='SinglePhase'>SinglePhase</option>
        <option value='ThreePhase'>ThreePhase</option>
      </select></center></td></tr>
      <?php
    }
  }

  function edit_Services($_PanelType,$fs)
  {
    if($_PanelType==3)
    {
      //Create a Distribution Service
      echo "<tr> <td> <center><b> Service: </center></td> <td><center>";
      echo "<select id = 1 name='service' style='width:175px;height:30px;'>";
      if(!empty($fs))
      {
        echo "<option value='$fs'>$fs</option>";
      }
      echo "<option value='120/208V.,3PH.,4W.'>120/208V.,3PH.,4W.</option>";
      echo "<option value='120/208V.,3PH.,4W.'>120/208V.,3PH.,5W.</option>";
      echo "<option value='120/208V.,3PH.,4W.'>120/240V.,3PH.,4W.</option>";
      echo "<option value='120/208V.,3PH.,4W.'>120/240V.,3PH.,5W.</option>";
      echo "<option value='208V.,3PH.,3W.'>208V.,3PH.,3W.</option>";
      echo "<option value='208V.,3PH.,4W.'>208V.,3PH.,4W.</option>";
      echo "<option value='240V.,3PH.,3W.'>240V.,3PH.,3W.</option>";
      echo "<option value='240V.,3PH.,4W.'>240V.,3PH.,4W.</option>";
      echo "<option value='277/480V.,3PH.,4W.'>277/480V.,3PH.,4W.</option>";
      echo "<option value='277/480V.,3PH.,5W.'>277/480V.,3PH.,5W.</option>";
      echo "<option value='480V.,3PH.,3W.'>480V.,3PH.,3W.</option>";
      echo "<option value='480V.,3PH.,4W.'>480V.,3PH.,4W.</option>";
      echo "<option value='600V.,3PH.,4W.'>600V.,3PH.,4W.</option>";
      echo "<option value='600V.,3PH.,5W.'>600V.,3PH.,5W.</option>";
      echo "<option value='2400V.,3PH.,4W.'>2400V.,3PH.,4W.</option>";
      echo "<option value='2400V.,3PH.,5W.'>2400V.,3PH.,5W.</option>";
      echo "<option value='4160V.,3PH.,4W.'>4160V.,3PH.,4W.</option>";
      echo "<option value='4160V.,3PH.,5W.'>4160V.,3PH.,5W.</option>";
      echo "<option value='7200/12470V.,3PH.,4W.'>7200/12470V.,3PH.,4W.</option>";
      echo "<option value='12470V.,3PH.,3W.'>12470V.,3PH.,3W.</option>";
      echo "<option value='13200V.,3PH.,3W.'>13200V.,3PH.,3W.</option>";
      echo "<option value='13200V.,3PH.,4W.'>13200V.,3PH.,4W.</option>";
      echo "</select> </center></td></tr>";
    }
    else if($_PanelType==2)
    {
      echo "<tr><td><center><b> Service: <center></td> <td>";
      echo "<center><select id = 1 name='service' style = 'width:175px;height:30px;'>";
      if(!empty($fs))
      {
        echo "<option value='$fs'>$fs</option>";
      }
      echo "<option value='120/208V.,3PH.,4W.'>120/208V.,3PH.,4W.</option>";
      echo "<option value='120/208V.,3PH.,4W.'>120/208V.,3PH.,5W.</option>";
      echo "<option value='120/208V.,3PH.,4W.'>120/240V.,3PH.,4W.</option>";
      echo "<option value='120/208V.,3PH.,4W.'>120/240V.,3PH.,5W.</option>";
      echo "<option value='208V.,3PH.,3W.'>208V.,3PH.,3W.</option>";
      echo "<option value='208V.,3PH.,4W.'>208V.,3PH.,4W.</option>";
      echo "<option value='240V.,3PH.,3W.'>240V.,3PH.,3W.</option>";
      echo "<option value='240V.,3PH.,4W.'>240V.,3PH.,4W.</option>";
      echo "<option value='277/480V.,3PH.,4W.'>277/480V.,3PH.,4W.</option>";
      echo "<option value='277/480V.,3PH.,5W.'>277/480V.,3PH.,5W.</option>";
      echo "<option value='480V.,3PH.,3W.'>480V.,3PH.,3W.</option>";
      echo "<option value='480V.,3PH.,4W.'>480V.,3PH.,4W.</option>";
      echo "<option value='600V.,3PH.,4W.'>600V.,3PH.,4W.</option>";
      echo "<option value='600V.,3PH.,5W.'>600V.,3PH.,5W.</option>";
      echo "<option value='2400V.,3PH.,4W.'>2400V.,3PH.,4W.</option>";
      echo "<option value='2400V.,3PH.,5W.'>2400V.,3PH.,5W.</option>";
      echo "<option value='4160V.,3PH.,4W.'>4160V.,3PH.,4W.</option>";
      echo "<option value='4160V.,3PH.,5W.'>4160V.,3PH.,5W.</option>";
      echo "<option value='7200/12470V.,3PH.,4W.'>7200/12470V.,3PH.,4W.</option>";
      echo "<option value='12470V.,3PH.,3W.'>12470V.,3PH.,3W.</option>";
      echo "<option value='13200V.,3PH.,3W.'>13200V.,3PH.,3W.</option>";
      echo "<option value='13200V.,3PH.,4W.'>13200V.,3PH.,4W.</option>";
      echo "</select></center></td></tr>";

    }
    else if($_PanelType==1)
    {
      echo "<tr><td><center><b> Service: <center></td> <td>";
      echo "<center><select id = 1 name='service' style = 'width:175px;height:30px;'>";
      if(!empty($fs))
      {
        echo "<option value='$fs'>$fs</option>";
      }
      echo "<option value='120/208V.,1PH.,3W.'>120/208V.,1PH.,3W.</option>";
      echo "<option value='120/240V.,1PH.,3W.'>120/240V.,1PH.,3W.</option>";
      echo "</select></center></td></tr>";
    }
    else if($_PanelType==4)
    {
      echo "<tr><td><center><b> Service: <center></td> <td><center>";
      echo "<select id = 1 name='service' style = 'width:175px;height:30px;'>";
      if(!empty($fs))
      {
        echo "<option value='$fs'>$fs</option>";
      }
      echo "<option value='120/120V.,1PH.,3W.'>120/120V.,1PH.,3W.</option>";
      echo "<option value='120/208V.,1PH.,3W.'>120/208V.,1PH.,3W.</option>";
      echo "<option value='120/240V.,1PH.,3W.'>120/240V.,1PH.,3W.</option>";
      echo "<option value='120/277V.,1PH.,3W.'>120/277V.,1PH.,3W.</option>";
      echo "<option value='120/480V.,1PH.,3W.'>120/480V.,1PH.,3W.</option>";
      echo "<option value='208/120V.,1PH.,3W.'>208/120V.,1PH.,3W.</option>";
      echo "<option value='208/208V.,1PH.,3W.'>208/208V.,1PH.,3W.</option>";
      echo "<option value='208/240V.,1PH.,3W.'>208/240V.,1PH.,3W.</option>";
      echo "<option value='208/277V.,1PH.,3W.'>208/277V.,1PH.,3W.</option>";
      echo "<option value='208/480V.,1PH.,3W.'>208/480V.,1PH.,3W.</option>";
      echo "</select></center></td></tr>";
    }
  }


  function Create_Services($_PanelType,$fs)
  {
    if($_PanelType==1)
    {
      //Create a Distribution Service
      echo "<tr> <td> <center><b> Service: </center></td> <td><center>";
      echo "<select id = 1 name='service' style='width:175px;height:30px;'>";
      if(!empty($fs))
      {
        echo "<option value='$fs'>$fs</option>";
      }
      echo "<option value='120/208V.,3PH.,4W.'>120/208V.,3PH.,4W.</option>";
      echo "<option value='120/208V.,3PH.,4W.'>120/208V.,3PH.,5W.</option>";
      echo "<option value='120/208V.,3PH.,4W.'>120/240V.,3PH.,4W.</option>";
      echo "<option value='120/208V.,3PH.,4W.'>120/240V.,3PH.,5W.</option>";
      echo "<option value='208V.,3PH.,3W.'>208V.,3PH.,3W.</option>";
      echo "<option value='208V.,3PH.,4W.'>208V.,3PH.,4W.</option>";
      echo "<option value='240V.,3PH.,3W.'>240V.,3PH.,3W.</option>";
      echo "<option value='240V.,3PH.,4W.'>240V.,3PH.,4W.</option>";
      echo "<option value='277/480V.,3PH.,4W.'>277/480V.,3PH.,4W.</option>";
      echo "<option value='277/480V.,3PH.,5W.'>277/480V.,3PH.,5W.</option>";
      echo "<option value='480V.,3PH.,3W.'>480V.,3PH.,3W.</option>";
      echo "<option value='480V.,3PH.,4W.'>480V.,3PH.,4W.</option>";
      echo "<option value='600V.,3PH.,4W.'>600V.,3PH.,4W.</option>";
      echo "<option value='600V.,3PH.,5W.'>600V.,3PH.,5W.</option>";
      echo "<option value='2400V.,3PH.,4W.'>2400V.,3PH.,4W.</option>";
      echo "<option value='2400V.,3PH.,5W.'>2400V.,3PH.,5W.</option>";
      echo "<option value='4160V.,3PH.,4W.'>4160V.,3PH.,4W.</option>";
      echo "<option value='4160V.,3PH.,5W.'>4160V.,3PH.,5W.</option>";
      echo "<option value='7200/12470V.,3PH.,4W.'>7200/12470V.,3PH.,4W.</option>";
      echo "<option value='12470V.,3PH.,3W.'>12470V.,3PH.,3W.</option>";
      echo "<option value='13200V.,3PH.,3W.'>13200V.,3PH.,3W.</option>";
      echo "<option value='13200V.,3PH.,4W.'>13200V.,3PH.,4W.</option>";
      echo "</select> </center></td></tr>";
    }
    else if($_PanelType==2)
    {
      echo "<tr><td><center><b> Service: <center></td> <td>";
      echo "<center><select id = 1 name='service' style = 'width:175px;height:30px;'>";
      echo "<option value='120/208V.,3PH.,4W.'>120/208V.,3PH.,4W.</option>";
      echo "<option value='120/208V.,3PH.,4W.'>120/208V.,3PH.,5W.</option>";
      echo "<option value='120/208V.,3PH.,4W.'>120/240V.,3PH.,4W.</option>";
      echo "<option value='120/208V.,3PH.,4W.'>120/240V.,3PH.,5W.</option>";
      echo "<option value='208V.,3PH.,3W.'>208V.,3PH.,3W.</option>";
      echo "<option value='208V.,3PH.,4W.'>208V.,3PH.,4W.</option>";
      echo "<option value='240V.,3PH.,3W.'>240V.,3PH.,3W.</option>";
      echo "<option value='240V.,3PH.,4W.'>240V.,3PH.,4W.</option>";
      echo "<option value='277/480V.,3PH.,4W.'>277/480V.,3PH.,4W.</option>";
      echo "<option value='277/480V.,3PH.,5W.'>277/480V.,3PH.,5W.</option>";
      echo "<option value='480V.,3PH.,3W.'>480V.,3PH.,3W.</option>";
      echo "<option value='480V.,3PH.,4W.'>480V.,3PH.,4W.</option>";
      echo "<option value='600V.,3PH.,4W.'>600V.,3PH.,4W.</option>";
      echo "<option value='600V.,3PH.,5W.'>600V.,3PH.,5W.</option>";
      echo "<option value='2400V.,3PH.,4W.'>2400V.,3PH.,4W.</option>";
      echo "<option value='2400V.,3PH.,5W.'>2400V.,3PH.,5W.</option>";
      echo "<option value='4160V.,3PH.,4W.'>4160V.,3PH.,4W.</option>";
      echo "<option value='4160V.,3PH.,5W.'>4160V.,3PH.,5W.</option>";
      echo "<option value='7200/12470V.,3PH.,4W.'>7200/12470V.,3PH.,4W.</option>";
      echo "<option value='12470V.,3PH.,3W.'>12470V.,3PH.,3W.</option>";
      echo "<option value='13200V.,3PH.,3W.'>13200V.,3PH.,3W.</option>";
      echo "<option value='13200V.,3PH.,4W.'>13200V.,3PH.,4W.</option>";
      echo "</select></center></td></tr>";

    }
    else if($_PanelType==3)
    {
      echo "<tr><td><center><b> Service: <center></td> <td>";
      echo "<center><select id = 1 name='service' style = 'width:175px;height:30px;'>";
      echo "<option value='120/208V.,1PH.,3W.'>120/208V.,1PH.,3W.</option>";
      echo "<option value='120/240V.,1PH.,3W.'>120/240V.,1PH.,3W.</option>";
      echo "</select></center></td></tr>";
    }
    else if($_PanelType==4)
    {
      echo "<tr><td><center><b> Service: <center></td> <td><center>";
      echo "<select id = 1 name='service' style = 'width:175px;height:30px;'>";
      echo "<option value='120/120V.,1PH.,3W.'>120/120V.,1PH.,3W.</option>";
      echo "<option value='120/208V.,1PH.,3W.'>120/208V.,1PH.,3W.</option>";
      echo "<option value='120/240V.,1PH.,3W.'>120/240V.,1PH.,3W.</option>";
      echo "<option value='120/277V.,1PH.,3W.'>120/277V.,1PH.,3W.</option>";
      echo "<option value='120/480V.,1PH.,3W.'>120/480V.,1PH.,3W.</option>";
      echo "<option value='208/120V.,1PH.,3W.'>208/120V.,1PH.,3W.</option>";
      echo "<option value='208/208V.,1PH.,3W.'>208/208V.,1PH.,3W.</option>";
      echo "<option value='208/240V.,1PH.,3W.'>208/240V.,1PH.,3W.</option>";
      echo "<option value='208/277V.,1PH.,3W.'>208/277V.,1PH.,3W.</option>";
      echo "<option value='208/480V.,1PH.,3W.'>208/480V.,1PH.,3W.</option>";
      echo "</select></center></td></tr>";
    }
    else if($_PanelType==5)
    {
      echo "<tr><td><center><b> Service: <center></td> <td><center>";
      echo "<center><select id='slc3' name = 'service' style='width:175px;height:30px;'>";
      echo "<option data-group='SHOW' value='0'>-- Select --</option>";
      echo "</center></select></td></tr>";
    }
  }


  //Use additional fields for special cases
  function Additional_Field($_PanelType)
  {
    if($_PanelType==1)
    {
      echo "<input type = 'hidden' value ='Distribution' name = 'type'>";
      echo "
      <tr><td><b><center>Switch and Fuse: </center></td>
      <td><center><select id = 2 name='fuse' style='width:175px;height:30px;'>
      <option value='0'>No</option>
      <option value='1'>Yes</option>
      </select></center></td></tr>
      ";
    }
    else if($_PanelType==2)
    {
      echo "<input type = 'hidden' value ='ThreePhase' name = 'type'>";
    }
    else if($_PanelType==3)
    {
      echo "<input type = 'hidden' value ='SinglePhase' name = 'type'>";
    }
    else if($_PanelType==5)
    {
      echo "<input type = 'hidden' value ='1' name = 'ulta'>";
    }
    else if($_PanelType==4)
    {
      ?>
      <tr>
        <td><b><center>Transformer Size: </center></td>
          <td><?php
          echo "<input type = 'hidden' value ='Isolation' name = 'type'>";
          echo "<center><select id = 1 name='transize' style='width:175px;height:30px;'>";
          echo "<option value='1'>1</option>";
          echo "<option value='1.5'>1.5</option>";
          echo "<option value='3'>3</option>";
          echo "<option value='5'>5</option>";
          echo "<option value='7.5'>7.5</option>";
          echo "<option value='10'>10</option>";
          echo "<option value='12.5'>12.5</option>";
          echo "<option value='25'>25</option>";
          echo "</select></center>";
          ?>
        </td>
      </tr>
      <tr>
        <td><b><center>Secondary Voltage: </center></td>
          <td><?php
          echo "<center><select id = 1 name='secvol' style='width:175px;height:30px;'>";
          echo "<option value='120'>120</option>";
          echo "<option value='208'>208</option>";
          echo "<option value='240'>240</option>";
          echo "<option value='277'>277</option>";
          echo "<option value='480'>480</option>";
          echo "</select></center>";
          ?>
        </td>
      </tr>
      <tr>
        <td><b><center>Line Isolation Monitor: </center></td>
          <td><?php
          echo "<center><select id = 2 name='monitor' style='width:175px;height:30px;'>";
          echo "<option value='Yes'>Yes</option>";
          echo "<option value='No'>No</option>";
          echo "</select></center>";
          ?>
        </td>
      </tr>
      <?php
    }
  }
  function edittranp($t,$s,$l)
  {

      ?>
      <tr>
        <td><b><center>Transformer Size: </center></td>
          <td><?php
          echo "<input type = 'hidden' value ='Isolation' name = 'type'>";
          echo "<center><select id = 1 name='transize' style='width:175px;height:30px;'>";
          echo "<option value='$t'>$t</option>";
          echo "<option value='1'>1</option>";
          echo "<option value='1.5'>1.5</option>";
          echo "<option value='3'>3</option>";
          echo "<option value='5'>5</option>";
          echo "<option value='7.5'>7.5</option>";
          echo "<option value='10'>10</option>";
          echo "<option value='12.5'>12.5</option>";
          echo "<option value='25'>25</option>";
          echo "</select></center>";
          ?>
        </td>
      </tr>
      <tr>
        <td><b><center>Secondary Voltage: </center></td>
          <td><?php
          echo "<center><select id = 1 name='secvol' style='width:175px;height:30px;'>";
          echo "<option value='$s'>$s</option>";
          echo "<option value='120'>120</option>";
          echo "<option value='208'>208</option>";
          echo "<option value='240'>240</option>";
          echo "<option value='277'>277</option>";
          echo "<option value='480'>480</option>";
          echo "</select></center>";
          ?>
        </td>
      </tr>
      <tr>
        <td><b><center>Line Isolation Monitor: </center></td>
          <td><?php
          echo "<center><select id = 2 name='monitor' style='width:175px;height:30px;'>";
          echo "<option value='$l'>$l</option>";
          echo "<option value='Yes'>Yes</option>";
          echo "<option value='No'>No</option>";
          echo "</select></center>";
          ?>
        </td>
      </tr>
      <?php
  }
  ?>
  <script>
  $("[data-child]").change(function() {
    //store reference to current select
    var me = $(this);
    //get selected group
    var group = me.find(":selected").val();
    //get the child select by it's ID
    var child = $("#" + me.attr("data-child"));
    //hide all child options except the ones for the current group, and get first item
    var newValue = child.find('option').hide().not('[data-group!="' + group + '"]').show().eq(0).val();
    child.trigger("change");
    //set default value
    child.val(newValue);
  });

  function populate(s1,s2,s3){
    var s1 = document.getElementById(s1);
    var s2 = document.getElementById(s2);
    var s3 = document.getElementById(s3);
    s2.innerHTML = "";
    s3.innerHTML = "";
    if(s1.value == "SinglePhase"){
      var optionArray = ["4|4","6|6","8|8","12|12","16|16","20|20","24|24","30|30","32|32","36|36","40|40","42|42"];
      var optionArray1 = ["120/208V.,1PH.,3W.|120/208V.,1PH.,3W.","120/240V.,1PH.,3W.|120/240V.,1PH.,3W."];
    } else if(s1.value == "ThreePhase"){
      var index;
      var optionArray = [];
      var optionArray1 = [
        "120/208V.,3PH.,4W.|120/208V.,3PH.,4W.",
        "120/208V.,3PH.,5W.|120/208V.,3PH.,5W.",
        "120/240V.,3PH.,4W.|120/240V.,3PH.,4W.",
        "120/240V.,3PH.,5W.|120/240V.,3PH.,5W.",
        "208V.,3PH.,3W.|208V.,3PH.,3W.",
        "208V.,3PH.,4W.|208V.,3PH.,4W.",
        "240V.,3PH.,3W.|240V.,3PH.,3W.",
        "240V.,3PH.,4W.|240V.,3PH.,4W.",
        "277/480V.,3PH.,4W.|277/480V.,3PH.,4W.",
        "277/480V.,3PH.,5W.|277/480V.,3PH.,5W.",
        "480V.,3PH.,3W.|480V.,3PH.,3W.",
        "480V.,3PH.,4W.|480V.,3PH.,4W.",
        "600V.,3PH.,4W.|600V.,3PH.,4W.",
        "600V.,3PH.,5W.|600V.,3PH.,5W.",
        "2400V.,3PH.,4W.|2400V.,3PH.,4W.",
        "2400V.,3PH.,5W.|2400V.,3PH.,5W.",
        "4160V.,3PH.,4W.|4160V.,3PH.,4W.",
        "4160V.,3PH.,5W.|4160V.,3PH.,5W.",
        "7200/12470V.,3PH.,4W.|7200/12470V.,3PH.,4W.",
        "12470V.,3PH.,3W.|12470V.,3PH.,3W.",
        "13200V.,3PH.,3W.|13200V.,3PH.,3W.",
        "13200V.,3PH.,4W.|13200V.,3PH.,4W."
      ];
      for(index = 1; index < 84 ; index++)
      {
        index+=5;
        var tmp = index + "|"+index;
        optionArray.push(tmp);
      }
    }
    for(var option in optionArray){
      var pair = optionArray[option].split("|");
      var newOption = document.createElement("option");
      newOption.value = pair[0];
      newOption.innerHTML = pair[1];
      s2.options.add(newOption);
    }
    for(var option in optionArray1){
      var pair = optionArray1[option].split("|");
      var newOption = document.createElement("option");
      newOption.value = pair[0];
      newOption.innerHTML = pair[1];
      s3.options.add(newOption);
    }
  }
  </script>
