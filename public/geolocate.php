<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<link href="../css/bootstrap.min.css" rel="stylesheet">
	    <link  rel="stylesheet" href="../css/jquery-jvectormap-2.0.3.css" type ="text/css" media ="screen">
            <script src="../js/jquery-2.2.3.min.js"></script>
            <script src="../js/bootstrap.min.js"></script>
    	    <script src="../js/jquery-jvectormap-2.0.3.min.js"></script>
		   <script src="../js/jquery-jvectormap-world-mill.js"></script>
	</head>
<?php

$ip = 'Not Submitted';
$response = '';
$info = 'Awaiting Info'; 

//Send empty data to activate an empty map(client)
$phpstr = '[]';

if (!empty($_POST)){
   $ip =  trim($_POST['ip']);
   if (!filter_var($ip, FILTER_VALIDATE_IP)){
     $ip = " Is not a valid ip address";
      }
      else{
//Collect info in JSON Format from FreeGeoip - set ip address to get info
 //Set the path
$ipaddress ="http://freegeoip.net/json/".trim($_POST['ip']);
//Reads the file at the path returns a string (no options used)
$ipaddress = file_get_contents($ipaddress);
//  Alert ip info
$info = $ipaddress;
// decode the jSON string received into an associative array in anPHP variable
$response = json_decode($ipaddress,true);
// Manipulate the array into a map marker JSON Object format
$new = "[{'latLng'  : [" .$response['latitude']. ", ".$response['longitude']. "] ,  'name':  '".$response["country_name"]."'}]";
//Send to  Javascript (client) as a JSON type Object which in PHP means an Array (No direct translation between PHP objects and JSON objects)
$phpstr = $new;
}
}

 ?>
<body>
    <div class="container">
      <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
<br>
          <div class="alert alert-info">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
  <p> <strong> IP Requested: </strong> <?php echo $ip?></p>
  <p> <strong> FreeGeoip Returned Detail: </strong> <?php echo $info?></p>
      <br>
      
<div>
  </div>
  </div>
          <h4> Enter IP Address (ie in  format XX.XX.XX.XX) </h4>
              <form method="POST" action="geolocate.php">
                   <form class="form-horizontal"  method='post'>
                  <div class="form-group <?php echo !empty($error)?'error':'';?>">
                    <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
                   <!-- <div><label>Add An IP Address</label>-->
                    <input type='text' name='ip' class="form-control input" value='<?php if(isset($error)){ echo $_POST['ip'];}?>'>
                    <?php if (!empty($error)): ?>
                    
                    <span class="help-block"><?php echo $error;?></span>
                    <?php endif; ?>
                </div>
                </div>
                <button type="submit" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-check"></span></button>
                <a class="btn btn-primary btn-sm" href="geolocate.php"><span class="glyphicon glyphicon-new-window"></span></a>
                        </form>
							              <br>
                            <div class="panel panel-default">
                            <div class="panel-heading">
                            <div class="text-center">
                            <h3 class="panel-title"></i>IP Address Country Geolocation </h3>
                             </div>
                            </div>
                             <div class="panel-body">
                              <div id="world-map" style=" height: 300px;"></div>


<script language="JavaScript" type="text/javascript">


 $(function() {
  //receive Server Response
   var jsstr = <?php echo $phpstr; ?>;
   $('#world-map').vectorMap({
    map: 'world_mill',
    normalizeFunction: 'polynomial',
    hoverOpacity: 0.7,
    hoverColor: false,
    backgroundColor: 'transparent',
    regionStyle: {
      initial: {
        fill: 'rgba(210, 214, 222, 1)',
        "fill-opacity": 1,
        stroke: 'none',
        "stroke-width": 0,
        "stroke-opacity": 1
      },
      hover: {
        "fill-opacity": 0.7,
        cursor: 'pointer'
      },
      selected: {
        fill: 'yellow'
      },
     selectedHover: {}
    },
    markerStyle: {
      initial: {
        fill: 'red',
        stroke: '#111',
        size: '2'
      }
    },

    markers: jsstr
   });
});
</script>
 </div>
                  </div>
                    </body>
           </html>