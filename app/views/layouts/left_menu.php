<?php
$exp=date("Y-m-d");
$new_dates=substr($exp,5,2)+'1';
$expriry1 =substr($exp,0,4).'-'.$new_dates.'-'.substr($exp,8,2);

$exd_query = mysql_fetch_object(mysql_query("SELECT LAST_DAY('$expriry1') as exd"));
$expriry = $exd_query->exd;

$datefrom = "";
$dateto = "";
$sqladates = "SELECT  min(FROM_UNIXTIME(E.event_start_date,'%Y%m%d')) mindate, max(FROM_UNIXTIME(E.event_end_date,'%Y%m%d')) maxdate FROM ".EVENTS." E WHERE E.status=1 AND ( DATE(NOW())<=DATE(FROM_UNIXTIME(E.event_end_date))) ";
$dateres = $db->getRow($sqladates);
$mindate = $dateres['mindate'];
$expriry = $dateres['maxdate']; 
 
$expriry11 =substr($exp,0,4).'-'.$new_dates.'-'.'01';
	 
$sqldates = mysql_query("SELECT DATE(NOW()) today, DATE(adddate(now(), INTERVAL 5-weekday(now()) DAY)) weekend, DATE(DATE_ADD(NOW(), INTERVAL +1 DAY)) tomorrow,DATE_SUB(LAST_DAY(DATE_ADD(NOW(), INTERVAL 1 MONTH)), INTERVAL DAY(LAST_DAY(DATE_ADD(NOW(), INTERVAL 1 MONTH)))-1 DAY) AS firstOfNextMonth, LAST_DAY(DATE_ADD(NOW(),INTERVAL 1 MONTH)) AS lastOfNextMonth");
$datelist = mysql_fetch_assoc($sqldates);
$firstOfNextMonth = $datelist['firstOfNextMonth'];
$lastOfNextMonth = $datelist['lastOfNextMonth']; 

$datelist['today'] = (int)str_replace("-","",$datelist['today']);
$datelist['tomorrow'] = (int)str_replace("-","",$datelist['tomorrow']);
$datelist['weekend'] = (int)str_replace("-","",$datelist['weekend']);
$datelist['firstOfNextMonth'] = (int)str_replace("-","",$datelist['firstOfNextMonth']);
$datelist['lastOfNextMonth'] = (int)str_replace("-","",$datelist['lastOfNextMonth']);

$weekstart = date('Ymd',strtotime('last monday')); //week start
$weekend = date('Ymd',strtotime('next sunday')); //week end
$nextsaturday = date('Ymd',strtotime('next saturday')); //week end

/*--------------------------search filters start-------------------------------*/
//date search
$searchCond = "";
$search_con="";
$dateCond = "";
$typeCond = "";
$locCond = "";
$txtCond = "";
$cityCond = "";

//date array start 
if($_REQUEST['date_type_string']!=''){
	$date_type_array=explode(",",$_REQUEST['date_type_string']);
}else{
	$date_type_array=$_REQUEST['date_type'];
}
if(count($date_type_array)>0){
	$date_type_string=implode(",",$date_type_array);
	$search_con.="&date_type_string=".$date_type_string;
}
$dayfilter = (isset($_REQUEST['d']))?$_REQUEST['d']:"";

//date array end

//type array start 
if($_REQUEST['event_ty_string']!=''){
	$event_ty_array=explode(",",$_REQUEST['event_ty_string']);
}else{
	$event_ty_array=$_REQUEST['type_event_id'];
}
//type array end 

//location array start  
if($_REQUEST['location_string']!=''){
	$location_array=explode(",",$_REQUEST['location_string']);
}else{
	$location_array=$_REQUEST['location_city'];
}

//date search  start 

if(count($date_type_array)>0)
{
	$dateCond.= " (";
	
	$date_i=0;
	
	foreach($date_type_array as $date_type_value){
	
	$date_i++;
	
	if($date_i=='1'){
		$searchOR= "";
	}else{
		$searchOR= " OR ";
	}
	
	if($date_type_value=='today'){
	
	 $dateCond.= $searchOR."(DATE(FROM_UNIXTIME(event_start_date))<=DATE(NOW()) AND DATE(NOW())<=DATE(FROM_UNIXTIME(event_end_date)))";
	}
	if($date_type_value=='tomorrow'){
	 $dateCond.= $searchOR."(DATE(FROM_UNIXTIME(event_start_date))<=DATE(DATE_ADD(NOW(), INTERVAL +1 DAY)) AND DATE(DATE_ADD(NOW(), INTERVAL +1 DAY))<=DATE(FROM_UNIXTIME(event_end_date)) )";
	}
	
	if($date_type_value=='thisweek'){
	
	$strtotime = date("o-\WW");
	
	// The $start timestamp contains the timestamp at 0:00 on the
	// Monday at the beginning of the week
	$starts = strtotime($strtotime);
	
	// and the end timestamp is six days later just before midnight
	$end = strtotime("+6 days 23:59:59", $starts);
    $starts=date("Y-m-d", $starts);
	$exp = $starts;
	$st_d=substr($exp,8,2)-1;
	
	$array_va=array("1","2","3","4","5","6","7","8","9");
	if(in_array($st_d,$array_va)){
	$st_d='0'.$st_d;
	}
	
    $starts =substr($exp,0,4).'-'.substr($exp,5,2).'-'.$st_d;	
    $end = date("Y-m-d", $end);	
	$en_d=str_replace("-","",substr($end,8,2)-1);
	
	if(in_array($en_d,$array_va)){
	$en_d='0'.$en_d;
	}
     $end =substr($end,0,4).'-'.substr($end,5,2).'-'.$en_d;	
	 
	  	 $dateCond.= $searchOR."( ((FROM_UNIXTIME(event_start_date,'%Y-%m-%d')<='".$starts."' ) AND  FROM_UNIXTIME(event_start_date,'%Y-%m-%d')<='".$end."' )   OR  ((FROM_UNIXTIME(event_start_date,'%Y-%m-%d')>'".$starts."' ) AND  FROM_UNIXTIME(event_start_date,'%Y-%m-%d')<='".$end."' )  )";
	
	}
	
	if($date_type_value=='weekend'){
	
	 $dateCond.= $searchOR."( DATE(FROM_UNIXTIME(event_start_date))<=DATE(adddate(now(), INTERVAL 5-weekday(now()) DAY)) AND  DATE(adddate(now(), INTERVAL 5-weekday(now()) DAY))<=DATE(FROM_UNIXTIME(event_end_date)))";
	}
	
	if($date_type_value=='nextmonth'){
	
	/*	$date = date("Y-m-d"); //current date
		$next_date = date("Y-m-01",strtotime(date("Y-m-d", strtotime($date)) . " +1 month"));
		
		$sqladates = "SELECT  min(FROM_UNIXTIME(E.event_start_date,'%Y%m%d')) mindate, max(FROM_UNIXTIME(E.event_end_date,'%Y%m%d')) maxdate FROM ".EVENTS." E WHERE E.status=1 AND ( DATE(NOW())<=DATE(FROM_UNIXTIME(E.event_end_date))) ";
		$dateres = $db->getRow($sqladates);
		
		$maxdate = $dateres['maxdate']; 
		
		$exd_query = mysql_fetch_object(mysql_query("SELECT LAST_DAY('".$maxdate."') as exd"));
		$exprirysss = $exd_query->exd; 
		
		$dateCond.= $searchOR."(DATE(FROM_UNIXTIME(event_end_date)) BETWEEN '" .$next_date."' AND '".$exprirysss."')";*/
		
		//$dateCond.= $searchOR."((DATE(FROM_UNIXTIME(event_start_date))<=".$lastOfNextMonth." ) && (DATE(FROM_UNIXTIME(event_end_date))>=".$lastOfNextMonth." ) )";
		
		$dateCond.= $searchOR."((DATE(FROM_UNIXTIME(event_end_date))>=".$datelist['lastOfNextMonth']." || (".$datelist['firstOfNextMonth']."<=DATE(FROM_UNIXTIME(event_end_date)) && DATE(FROM_UNIXTIME(event_end_date))<=".$datelist['lastOfNextMonth'].")) && (DATE(FROM_UNIXTIME(event_start_date))<=".$datelist['lastOfNextMonth'].") ) ";
	}
	
	}
	$dateCond.= " )";
	
}



//date search  end 


//type search start 

if(count($event_ty_array)>0){
	$event_ty_string=implode(",",$event_ty_array);
	$search_con.="&event_ty_string=".$event_ty_string;
	$ty_count=count($event_ty_array);
	$ab_ty=$ty_count-1;
}
if(count($event_ty_array)>1)
{
	$typeCond.=" (";
	$i=0;
	foreach($event_ty_array as $event_ty_value)
	{
		$typeCond.=" e.type_id =".$event_ty_value;
		if($i<$ab_ty){
		 $typeCond.=" OR ";
		}
		$i++;
	}
	$typeCond.=")";
}
else
{
	if(count($event_ty_array)=='1'){
		foreach($event_ty_array as $event_ty_value){
			$typeCond.=" e.type_id=".$event_ty_value;
		}
	}
}
//type search end 


//Location Search array start
if(count($location_array)>0){
	
	$location_string=implode(",",$location_array);
	$search_con.="&location_string=".$location_string;
	
	unset($_REQUEST['searchloc']);
	$count=count($location_array);
	$ab=$count-1;
}
if(count($location_array)>1){
	$locCond.=" (";
	$i=0;
	foreach($location_array as $location_value){
	$locCond.=" e.city LIKE '".$location_value."%'";
	if($i<$ab){
	 $locCond.=" OR ";
	}
	$i++;
	}
	$locCond.=")";
}
else
{
	if(count($location_array)=='1'){
		foreach($location_array as $location_value){
			$locCond.="  e.city LIKE '".$location_value."%'";
		}
	}
}
//location search array end

if($_REQUEST['searchtext']!='' && $_REQUEST['searchtext']!="Enter keyword"){
	$txtCond.="  ( e.title LIKE '%".$_REQUEST['searchtext']."%' OR  e.description LIKE '%".$_REQUEST['searchtext']."%' OR  e.address LIKE '%".$_REQUEST['searchtext']."%' OR  e.city LIKE '%".$_REQUEST['searchtext']."%' OR  e.country LIKE '%".$_REQUEST['searchtext']."%' OR  e.location LIKE '%".$_REQUEST['searchtext']."%' OR  t.name LIKE '%".$_REQUEST['searchtext']."%')" ;
	
	$search_con.="&searchtext=".$_REQUEST['searchtext'];
}

if(count($location_array)==0 && $_REQUEST['searchloc']==""){

//$IP = $_SERVER['REMOTE_ADDR'];
//$IP = '119.82.117.227';
//$APIKEY = '1ddf92576aa8e687510b8c88ef924fe7339adf465d3246264345f5a16b6c0f44';
//$default_location = json_decode(file_get_contents('http://api.ipinfodb.com/v3/ip-city/?key=' .$APIKEY. '&ip=' .$IP. '&format=json'),TRUE);

//$latitude=$default_location['latitude'];
$longitude=$default_location['longitude'];

//$unit_array = array("miles"=>"3963","nautical-miles"=>"3444","kilometers"=>"6371");
//$unit = $unit_array['kilometers'];
 
//$searchCond.=" AND ".$unit." * ACOS( SIN(RADIANS(".$latitude.")) * SIN(RADIANS(e.latitude)) + COS(RADIANS(".$latitude."))  * COS(RADIANS(latitude)) * COS(RADIANS(".$longitude.") - RADIANS(e.longitude))) <250000000";

//$_REQUEST['searchloc']=strtolower($default_location['cityName'].",".$default_location['regionName'].",".$default_location['countryName']);

}


if($_REQUEST['searchloc']!='' && $_REQUEST['searchloc']!='Enter City (or) Zip Code'){
	// $unit_array = array("miles"=>"3963","nautical-miles"=>"3444","kilometers"=>"6371");
	// $unit = $unit_array['kilometers'];
	// $location_google=google_latlan($_REQUEST['searchloc']);
	// 
	// $latitude=$location_google['latitude'];
	// $longitude=$location_google['longitude'];
	// 
	//$searchCond.=" AND ".$unit." * ACOS( SIN(RADIANS(".$latitude.")) * SIN(RADIANS(e.latitude)) + COS(RADIANS(".$latitude."))  * COS(RADIANS(latitude)) * COS(RADIANS(".$longitude.") - RADIANS(e.longitude))) <25";
	$sech=explode(",",$_REQUEST['searchloc']);
	$cityCond.=" (";
	$my_new="";
	foreach($sech as $value){
	
	if($my_new!=''){
	$cityCond.=" OR ";
	}
	$cityCond.="e.title LIKE '%".$value."%' OR  e.description LIKE '%".$value."%' OR  e.address LIKE '%".$value."%' OR  e.city LIKE '%".$value."%' OR  e.country LIKE '%".$value."%' OR  e.location LIKE '%".$value."%' OR  t.name LIKE '%".$value."%' OR  e.zip LIKE '%".$value."%'";
	$my_new=$value;
	}
	$search_con.="&searchloc=".$_REQUEST['searchloc'];
	$cityCond.=" )";
}


//final search Condition
if($dateCond!=""){
	$searchCond.= $dateCond	;
}
if($typeCond!=""){
	if($searchCond!=""){	
		$searchCond.= " AND ".$typeCond	;
	}
	else{
		$searchCond.= $typeCond	;
	}
}
if($locCond!=""){
	if($searchCond!=""){	
		$searchCond.= " AND ".$locCond	;
	}
	else{
		$searchCond.= $locCond	;
	}
}
if($txtCond!=""){
	if($searchCond!=""){	
		$searchCond.= " AND ".$txtCond	;
	}
	else{
		$searchCond.= $txtCond	;
	}
}
if($cityCond!=""){
	if($searchCond!=""){	
		$searchCond.= " AND ".$cityCond	;
	}
	else{
		$searchCond.= $cityCond	;
	}
}

//limit
if($_REQUEST['start']>0){
	$start=$_REQUEST['start'];
}else{
	$start=0;
}
$q_limit='5';
/*--------------------------search filters end-------------------------------*/

//main query
 $sqlqry = "SELECT  e.id,e.type_id,e.createdby,e.title,e.logo,e.address,e.event_start_date,e.event_end_date,DATE(FROM_UNIXTIME(event_start_date)) as start_date,DATE(FROM_UNIXTIME(event_end_date)) as end_date, DATE_FORMAT(FROM_UNIXTIME(event_start_date),'%M %d, %Y %h:%i') as startdate, DATE_FORMAT(FROM_UNIXTIME(event_end_date),'%M %d, %Y %h:%i') as enddate, DATE_FORMAT(FROM_UNIXTIME(event_start_date),'%a/%d/%b') as startmonth,t.name as eventtype  FROM events e, event_types t , users u WHERE e.type_id=t.id AND e.createdby=u.id AND u.status=1 AND  e.status='1' AND t.status=1 AND (DATE(NOW())<=DATE(FROM_UNIXTIME(e.event_end_date)))";
if($searchCond!=""){
	$sqlqry.= " AND (".$searchCond.")";
}
   $sqlqry.="  GROUP BY e.id ORDER BY title,start_date DESC LIMIT ".$start.", ".$q_limit;

$sql = mysql_query($sqlqry);
$rescount = mysql_num_rows($sql);

$result = array();
while($erow=mysql_fetch_assoc($sql)){
	$result[] = $erow; 
}


//count query
$search_sql = "SELECT e.id,e.type_id,e.createdby,e.title,e.logo,e.address,DATE(FROM_UNIXTIME(event_start_date)) as start_date,DATE(FROM_UNIXTIME(event_end_date)) as end_date, DATE_FORMAT(FROM_UNIXTIME(event_start_date),'%M %d, %Y %h:%i') as startdate, DATE_FORMAT(FROM_UNIXTIME(event_end_date),'%M %d, %Y %h:%i') as enddate, DATE_FORMAT(FROM_UNIXTIME(event_start_date),'%a/%d/%b') as startmonth,t.name as eventtype  FROM events e, event_types t, users u WHERE e.type_id=t.id AND e.createdby=u.id AND u.status=1 AND  e.status=1 AND t.status=1  AND (DATE(NOW())<=DATE(FROM_UNIXTIME(e.event_end_date)))";
if($searchCond!=""){
	$search_sql.= " AND (".$searchCond.")";
}
 $search_sql.=" GROUP BY e.id";

$search_sql_count = mysql_query($search_sql);

$count_search = mysql_num_rows($search_sql_count);

$sql_count = mysql_query("SELECT e.id,e.type_id,e.createdby,e.title,e.logo,e.address,DATE(FROM_UNIXTIME(event_start_date)) as start_date,DATE(FROM_UNIXTIME(event_end_date)) as end_date, DATE_FORMAT(FROM_UNIXTIME(event_start_date),'%M %d, %Y %h:%i') as startdate, DATE_FORMAT(FROM_UNIXTIME(event_end_date),'%M %d, %Y %h:%i') as enddate, DATE_FORMAT(FROM_UNIXTIME(event_start_date),'%a/%d/%b') as startmonth,t.name as eventtype  FROM events e, event_types t, users u WHERE e.type_id=t.id AND e.createdby=u.id AND u.status=1 AND  e.status=1 AND t.status=1  AND (DATE(NOW())<=DATE(FROM_UNIXTIME(e.event_end_date))) ");


$count = mysql_num_rows($sql_count);

$dateresult = array(); 

// OR  (DATE(FROM_UNIXTIME(event_end_date)) BETWEEN '" .$expriry11."' AND '".$expriry."')"

     $strtotime = date("Y-m-d");	
	// The $start timestamp contains the timestamp at 0:00 on the
	// Monday at the beginning of the week
	 $starts = strtotime($strtotime);
	
	// and the end timestamp is six days later just before midnight
	$end = strtotime("+6 days 23:59:59", $starts);
    $starts=date("Y-m-d", $starts);
	$exp = $starts;
	$st_d=substr($exp,8,2)-1;
	$array_va=array("1","2","3","4","5","6","7","8","9");
	if(in_array($st_d,$array_va)){
	$st_d='0'.$st_d;
	}
	
     $starts =substr($exp,0,4).substr($exp,5,2).$st_d;	
   
     $end = date("Y-m-d", $end);

	 $en_d=str_replace("-","",substr($end,8,2)-1);
	    
     $end =substr($end,0,4).substr($end,5,2).$en_d;	
	
	if(in_array($end,$array_va)){
	$end='0'.$end;
	}
	$date = date("Y-m-d");// current date
	$next_date = date("Y-m-01",strtotime(date("Y-m-d", strtotime($date)) . " +1 month"));

//October 29, 2009 is my birthday
$week_end= date('W', strtotime( date("Y-m-d")));
$startend=getWeekDates('2012',$week_end,'');


function getWeekDates($year, $week, $start=true)
{
    $from = date("Ymd", strtotime("{$year}-W{$week}-0")); //Returns the date of monday in week
    $to = date("Ymd", strtotime("{$year}-W{$week}-6"));   //Returns the date of sunday in week
 
   $startend[]= $from;
   $startend[]= $to;   
    return  $startend;
}

//-------------------next month---------
$query_date = $next_date;
$nextmonth_start =  date('Ym01', strtotime($query_date));
$nextmonth_end = date('Ymt', strtotime($query_date));
 	
$x=0;	
while($row=mysql_fetch_assoc($sql_count))
{	
	
	$startdate = (int)str_replace("-","",$row['start_date']);
  
	
 	$enddate = (int)str_replace("-","",$row['end_date']);

	if($startdate<=$datelist['today'] && $datelist['today']<=$enddate){	
		 $dateresult['today'][] = $row;		
	}
	if($startdate<=$datelist['tomorrow'] && $datelist['tomorrow']<=$enddate){
		$dateresult['tomorrow'][] = $row;
	}
	
	if( ($enddate>=$datelist['weekend'] || ($datelist['today']<=$enddate && $enddate<=$datelist['weekend'])) && ($startdate<=$datelist['weekend']) )
	{
		$dateresult['thisweek'][] = $row;
	}
	
	if($startdate<=$datelist['weekend'] && $datelist['weekend']<=$enddate){
		$dateresult['weekend'][] = $row;
	}
	
	if( ($enddate>=$datelist['lastOfNextMonth'] || ($datelist['firstOfNextMonth']<=$enddate && $enddate<=$datelist['lastOfNextMonth'])) && ($startdate<=$datelist['lastOfNextMonth']) )
	{
		$dateresult['nextmonth'][] = $row;
	}


	
	/*if(strtotime($row['end_date'])>=strtotime($next_date) && strtotime($row['end_date'])<=strtotime(str_replace("-","",$expriry))){
		$dateresult['nextmonth'][] = $row;
	}*/
	
}

//array_print($this_weeks_v);
?>


<form method="post" name="location_form"  action="search.php">

<table width="100%">
               <!--goolge language script-->
				<?php /*?><tr>
					<td width="23%" valign="top"><table width="100%" border="0" cellspacing="10">
					  <tr>
						<td width="12%"><img src="images/date.png" width="20" height="19"></td>
						<td width="88%" class="searchhead">Languages</td>
					  </tr>
					  <tr align="left">
						<td colspan="2"  id="">
						
						<ul>
							  <li><div id="google_translate_element"></div></li>
							  </ul>
						</td>
					</tr>
					</table></td>
				</tr><?php */?>
				<!--goolge language script-->
				<tr>
					<td width="23%" valign="top"><table width="100%" border="0" cellspacing="10">
					  <tr>
						<td width="12%"><img src="images/date.png" width="20" height="19"></td>
						<td width="88%" class="searchhead">Calendar</td>
					  </tr>
					  <tr align="left">
						<td colspan="2"  id="serachbg1">
						<?php  $page=currentpagename(); ?>
						<ul>
							  <li><a  href="eventcalender.php" <?php if($page=='eventcalender.php'){?>style="font-size:13px;color:#2272AF; font-weight:bold;"<?php } ?>>Event Calendar</a></li>
							  </ul>
						</td>
					</tr>
					</table></td>
				</tr>
				
				<tr>
					<td width="23%" valign="top"><table width="100%" border="0" cellspacing="10">
					  <tr>
						<td width="12%"><img src="images/date.png" width="20" height="19"></td>
						<td width="88%" class="searchhead">Date</td>
					  </tr>
					  <tr align="left">
						<td colspan="2"  id="serachbg1">
						<ul>
						  <li class="active">All Dates (<?php echo $count; ?>)</li>
						 <?php if(count(@$dateresult['today'])>0){?><li ><input type="checkbox" name="date_type['today']" id="date_type['today']"  value="today" onclick="document.location_form.submit();" <?php if(count($date_type_array)>0 && in_array('today',$date_type_array)){?>checked="checked"<?php } ?>/>&nbsp;<a  <?php if(count($date_type_array)>0 && in_array('today',$date_type_array)){?>style="font-size:13px;color:#2272AF; font-weight:bold;" <?php } ?>>Today (<?php echo count(@$dateresult['today']); ?>)</a></li>
						  <?php } ?>
						 <?php if(count(@$dateresult['tomorrow'])>0){?> <li> <input type="checkbox" name="date_type['tomorrow']" id="date_type['tomorrow']"  value="tomorrow" onclick="document.location_form.submit();" <?php if(count($date_type_array)>0 && in_array('tomorrow',$date_type_array)){?>checked="checked"<?php } ?>/>&nbsp;<a  <?php if(count($date_type_array)>0 && in_array('tomorrow',$date_type_array)){?>style="font-size:13px;color:#2272AF; font-weight:bold;" <?php } ?>>Tomorrow (<?php echo count(@$dateresult['tomorrow']); ?>)</a></li>
						  <?php } ?>
						 <?php if(count(@$dateresult['thisweek'])>0){?><li><input type="checkbox" name="date_type['thisweek']" id="date_type['thisweek']"  value="thisweek" onclick="document.location_form.submit();" <?php if(count($date_type_array)>0 && in_array('thisweek',$date_type_array)){?>checked="checked"<?php } ?>/>&nbsp;<a <?php if(count($date_type_array)>0 && in_array('thisweek',$date_type_array)){?>style="font-size:13px;color:#2272AF; font-weight:bold;" <?php } ?>>This Week (<?php echo count(@$dateresult['thisweek']); ?>)</a></li>
						  <?php } ?>
						 <?php if(count(@$dateresult['weekend'])>0){?>
					<li><input type="checkbox" name="date_type['weekend']" id="date_type['weekend']"  value="weekend" onclick="document.location_form.submit();" <?php if(count($date_type_array)>0 && in_array('weekend',$date_type_array)){?>checked="checked"<?php } ?>/>&nbsp;<a <?php  if(count($date_type_array)>0 && in_array('weekend',$date_type_array)){?>style="font-size:13px;color:#2272AF; font-weight:bold;" <?php } ?>>This Weekend (<?php echo count(@$dateresult['weekend']); ?>)</a></li>
						  <?php } ?>
						 <?php if(count(@$dateresult['nextmonth'])>0){?><li> <input type="checkbox" name="date_type['nextmonth']" id="date_type['nextmonth']"  value="nextmonth" onclick="document.location_form.submit();" <?php if(count($date_type_array)>0 && in_array('nextmonth',$date_type_array)){?>checked="checked"<?php } ?>/>&nbsp;<a  <?php if(count($date_type_array)>0 && in_array('nextmonth',$date_type_array)){?>style="font-size:13px;color:#2272AF; font-weight:bold;" <?php } ?>>Next Month (<?php echo count(@$dateresult['nextmonth']); ?>)</a></li>
						  <?php } ?>
						</ul>
						
						
						 
						 
						</td>
					</tr>
					</table></td>
				</tr>
				<tr>
					<td valign="top">
						<table width="100%" border="0" cellspacing="10">
						  <tr>
							<td width="12%"><img src="images/type.png" width="20" height="17"></td>
							<td width="88%" class="searchhead">Type</td>
						  </tr>
						  <tr align="left">
							<td colspan="2"  id="serachbg1">
							<?php
							$event_ty="SELECT ET.id,ET.name,E.type_id,E.createdby,U.status,E.event_end_date,E.event_start_date FROM ".EVENT_TYPES."  ET ,".EVENTS."  E ,".USERS." U WHERE ET.id=E.type_id AND E.createdby=U.id AND ET.status=1 AND E.status=1 AND U.status=1 AND (DATE(NOW())<=DATE(FROM_UNIXTIME(E.event_end_date)))";
							
					 	$event_typess="SELECT ET.id,ET.name,E.type_id,E.createdby,U.status ,count(ET.id) as  eventcount FROM ".EVENTS ."  E ,".EVENT_TYPES."  ET ,".USERS." U WHERE E.type_id=ET.id AND E.createdby=U.id AND ET.status=1 AND E.status=1 AND U.status=1  AND ( DATE(NOW())<=DATE(FROM_UNIXTIME(E.event_end_date))) GROUP BY ET.id ORDER BY ET.name";
							 
							
							$event_result1=$db->getRows($event_typess);	
										    						 
							$event_type_count=sqlnumber($event_ty);
														
							function compare_lastname($a, $b) { return strnatcmp($a['name'], $b['name']); }
							// sort alphabetically by name 
							usort($event_result1, 'compare_lastname');
							//array_print($event_result1);
							?>
							<ul>
							  <li class="active">All Types (<?php echo $event_type_count; ?>)</li>
							  <?php foreach($event_result1 as $value){							  
							  
							 
							   $event_count=sqlnumber("SELECT type_id FROM ".EVENTS." WHERE type_id=".$value['type_id']." AND status=1 AND (DATE(NOW())<=DATE(FROM_UNIXTIME(event_end_date)))" );
							   if($event_count>0){  
							  ?>
							  <li>
							  <input type="checkbox" name="type_event_id[<?php echo $value['id']; ?>]" id="type_event_id[<?php echo $value['id']; ?>]"  value="<?php echo $value['id']; ?>" onclick="document.location_form.submit();" <?php if(count($event_ty_array)>0 && in_array($value['id'],$event_ty_array)){?>checked="checked"<?php } ?>/>&nbsp;
							  
							  <a <?php if(count($event_ty_array)>0 && in_array($value['id'],$event_ty_array)){?>style="font-size:13px;color:#2272AF; font-weight:bold;" <?php } ?>><?php echo $value['name']; ?> (<?php echo $value['eventcount']; ?>)</a></li>
							  <?php }
							  unset($event_count); } ?>
							 
							</ul></td>
						  </tr>
						</table></td>				
				</tr>
				<tr>
					<td valign="top"><table width="100%" border="0" cellspacing="10">
					  <tr>
						<td width="12%"><img src="images/location.png" width="20" height="19"></td>
						<td width="88%" class="searchhead">Location</td>
					  </tr>
					  <tr align="left">
						<td colspan="2"  id="serachbg1">
						
						  <input type="hidden" name="d" id="d"  value="<?php echo $_REQUEST['d']; ?>" />
						  <input type="hidden" name="type_id" id="type_id"  value="<?php echo $_REQUEST['type_id']; ?>" />
						  <input type="hidden" name="location" id="location"  value="<?php echo $_REQUEST['location']; ?>" />						  
						  <input type="hidden" name="searchtext" id="searchtexts"  value="<?php echo $_REQUEST['searchtext']; ?>" />						  
						   <input type="hidden" name="searchloc"   value="<?php  if(count($location_array)==0){ echo $_REQUEST['searchloc']; } ?>" />
							 	  
						<?php
						
						$events="SELECT ET.id,E.city,E.type_id,E.createdby,U.status,E.event_end_date,E.event_start_date,count(E.id) as  eventcount FROM ".EVENT_TYPES."  ET ,".EVENTS."  E ,".USERS." U WHERE ET.id=E.type_id AND ET.status=1 AND E.createdby=U.id AND E.status=1 AND U.status=1  AND (DATE(NOW())<=DATE(FROM_UNIXTIME(E.event_end_date))) GROUP BY E.city";
						$event_result=$db->getRows($events);
						$event_count=sqlnumber($events);
						$locevents_count = 0;
						foreach($event_result as $eve){	
							$locevents_count+=	$eve[eventcount];
						}
						
						?>
						<ul>
						  <li class="active">All Locations (<?php echo $locevents_count; ?>)</li>
						  <?php foreach($event_result as $value){						
											
						/*   $event_count=sqlnumber("SELECT E.city,E.createdby,U.id,E.event_end_date,E.event_start_date FROM ".EVENTS." E ,".USERS." U WHERE city='".$value['city']."' AND  E.createdby=U.id AND E.status=1 AND U.status=1 AND (DATE(NOW())<=DATE(FROM_UNIXTIME(E.event_end_date)))"); 
						   if($event_count>0){ */
						  ?>
						  <li><input type="checkbox" name="location_city[<?php echo $value['city']; ?>]" id="location_city[<?php echo $value['city']; ?>]"  value="<?php echo $value['city']; ?>" onclick="document.location_form.submit();" <?php if(count($location_array)>0 && in_array($value['city'],$location_array)){?>checked="checked"<?php } ?>/>&nbsp;<a  <?php if(count($location_array)>0 && in_array($value['city'],$location_array)){?>style="font-size:13px;color:#2272AF; font-weight:bold;" <?php } ?>><?php echo $value['city']; ?> (<?php echo $value['eventcount']; ?>)</a></li>
						  
						  <?php /*}*/ } ?>
						 
						</ul>
						
						</td>
					  </tr>
					</table></td>				
				</tr>
			</table>
			


			
			</form >