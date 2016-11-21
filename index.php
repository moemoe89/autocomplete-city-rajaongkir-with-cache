<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Autocomplete RajaOngkir</title>
  <link rel="stylesheet" href="./jquery-ui.css">
  <script src="./jquery-1.10.2.js"></script>
  <script src="./jquery-ui.js"></script>
  <script>
  $(document).ready(function(){
    $("#load").hide();
    $('.autocomplete').each(function() {
      var $al = $(this);
        $al.autocomplete({
          source: function( request, response ) {
            window.globalVar = $al.attr('id');
            $.ajax({
              type: "POST",
              url: "execute.php?city=true",
              dataType: "json",
              data: {term: request.term},
              success: function(data) {
                response($.map(data, function(item) {
                  return {
                    label: item.city_name+' ('+item.type+')',
                    city_id: item.city_id
                  };
                }));
              }
            });
          },
          minLength: 2,
            select: function(event, ui) {
              $('#'+window.globalVar+'_id').val(ui.item.city_id);
            }
        });
    });

    $("#calculate").click(function(){

      var origin_id      = $("#origin_id").val();
      var destination_id = $("#destination_id").val();
      var weight         = $("#weight").val();

      if(!origin_id || !destination_id || !weight){
        alert('Please fill all form');
        return false;
      }

      if(parseInt(weight) < 1){
        alert('Weight min 1');
        return false;
      }

      if($.isNumeric( weight ) == true){

      } else {
        alert('Weight must number');
        return false;
      }
      
      $("#load").show();
      $.ajax({
        type: "POST",
        url: "execute.php?cost=true",
        dataType: "json",
        data: {origin: $("#origin_id").val(),destination: $("#destination_id").val(),weight: $("#weight").val(),courier: $("#courier").val()},
        cache : false,
        success: function(data) {
          $("#load").hide();
          $("#show-cost").html('');
          $.each(data, function(index, item) {
            $.each(item.costs, function(index, subitem) {
              $("#show-cost").append(item.name+' - '+subitem.service+' : '+subitem.cost[0].value+' ( '+subitem.cost[0].etd+' days )'+'<br />');
            });
          });
        }
      });
    });

    $(".autocomplete").keyup(function(){
      var x = event.keyCode;
      if(x != 13){
        $('#'+$(this).attr("id")+'_id').val("");
      }
    });

  });
  </script>
  <style>
  #load { height: 100%; width: 100%; }
  #load {
    position    : fixed;
    z-index     : 99; /* or higher if necessary */
    top         : 0;
    left        : 0;
    overflow    : hidden;
    text-indent : 100%;
    font-size   : 0;
    opacity     : 0.6;
    background  : #E0E0E0  url('loading.gif') center no-repeat;
  }
  </style>
</head>
<body>
<div id="load"></div>
<div class="ui-widget">
  <table>
    <tr>
      <td>Origin</td>
      <td>:</td>
      <td><input id="origin" class="autocomplete"> <input id="origin_id" type="hidden"></td>
    </tr>
    <tr>
      <td>Destination</td>
      <td>:</td>
      <td><input id="destination" class="autocomplete"> <input id="destination_id" type="hidden"></td>
    </tr>
    <tr>
      <td>Weight (gram)</td>
      <td>:</td>
      <td><input id="weight" class="autocomplete"></td>
    </tr>
    <tr>
      <td>Courier</td>
      <td>:</td>
      <td><select id="courier" class="autocomplete">
              <option value="jne">JNE</option>
              <option value="tiki">TIKI</option>
              <option value="pos">POS</option>
          </select>
      </td>
    </tr>
    <tr>
      <td></td>
      <td></td>
      <td><input type="button" id="calculate" value="Calculate"></td>
    </tr>
  </table>
</div>
<br />
<div id="show-cost"></div>
</body>
</html>