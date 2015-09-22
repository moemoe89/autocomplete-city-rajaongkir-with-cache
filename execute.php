<?php 

require 'APIClient.php';

use Momo\RajaOngkirClient\APIClient as APIClient;

$key              = "";
$rajaongkirclient = new APIClient($key);

if(@$_GET['province'] == true){
	print $rajaongkirclient->getProvince($_POST['term']);
} else if(@$_GET['city'] == true){
	print $rajaongkirclient->getCity($_POST['term']);
} else if(@$_GET['cost'] == true){
	print $rajaongkirclient->getCost($_POST['origin'],$_POST['destination'],$_POST['weight']);
}


?>