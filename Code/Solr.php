<?php
// make sure browsers see this page as utf-8 encoded HTML
header('Content-Type: text/html; charset=utf-8');

$limit = 10;
$query = isset($_REQUEST['q']) ? $_REQUEST['q'] : false;
$results = false;
if ($query)
{
 // The Apache Solr Client library should be on the include path
 // which is usually most easily accomplished by placing in the
 // same directory as this script ( . or current directory is a default
 // php include path entry in the php.ini)
 require_once('solr-php-client/Apache/Solr/Service.php');
 // create a new solr service instance - host, port, and corename
 // path (all defaults in this example)
 $solr = new Apache_Solr_Service('localhost', 8983, '/solr/myexample/');
 // if magic quotes is enabled then stripslashes will be needed
 if (get_magic_quotes_gpc() == 1)
 {
 $query = stripslashes($query);
 }
 // in production code you'll always want to use a try /catch for any
 // possible exceptions emitted by searching (i.e. connection
 // problems or a query parsing error)
 $param = [];
    if (array_key_exists("pagerank", $_REQUEST)) {
        $param['sort'] ="pageRankFile desc";
    }
 try
 {
// $results = $solr->search($query, 0, $limit);
 $results = $solr->search($query, 0, $limit, $param);
 }
 catch (Exception $e)
 {
 // in production you'd probably log or email this error to an admin
 // and then show a special message to the user but for this example
 // we're going to show the full exception
 die("<html><head><title>SEARCH EXCEPTION</title><body><pre>{$e->__toString()}</pre></body></html>");
 }
}
?>
<html>
 <head>
 <title>PHP Solr Client Example</title>
 <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</head>


<body class="container">
	<div class="panel panel-default">
	<div class="panel-heading text-center">
	<h1 class="display-4">NBC News Search Engine</h1>
	<p class="h3">USC CSCI 572: Spring2017</p>
		<form accept-charset="utf-8" method="get" class="form-inline">
			<div class="form-group row">
			<label for="q">Search:</label>
			<input id="q" name="q" type="text" value="<?php echo htmlspecialchars($query, ENT_QUOTES, 'utf-8'); ?>" class="form-control"/>
			&nbsp;&nbsp;&nbsp;
			<label for="q">Algorithm:</label>
			<input type="checkbox" name="pagerank">Use Page Rank Algorhthm
			&nbsp;&nbsp;&nbsp;
			<button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-search" aria-hidden="true"></span>&nbsp;Search </button>
			</div>
		</form>
	</div>
	<div class="panel-body">
	<?php
// display results
if ($results)
{
 $total = (int) $results->response->numFound;
 $start = min(1, $total);
 $end = min($limit, $total);
?>
 <div>Results <?php echo $start; ?> - <?php echo $end;?> of <?php echo $total; ?>:</div>
 <hr/>
 <ul>
<?php
 // iterate result documents
 foreach ($results->response->docs as $doc)
 {
?>
 <li>
<?php
$a_text="";
$a_href="";
$a_description="";
$a_id="";

 // iterate document fields / values
 foreach ($doc as $field => $value)
 {
 	if($field=="title")
 		$a_text=$value;
 	if($field=="description")
 		$a_description=$value;
 	if($field=="og_url")
 		$a_href=$value;
 	if($field=="id")
 	{
 		$a_id=$value;
 		$exploded=explode('/',$a_id);
 		$a_id= end($exploded);
 	}
}
?>
<a href="<?php echo $a_href ?> " target="_blank"><?php echo htmlspecialchars($a_text, ENT_NOQUOTES, 'utf-8'); ?></a>
<br/>
<em><?php echo htmlspecialchars($a_id, ENT_NOQUOTES, 'utf-8'); ?></em><br/>
<?php echo htmlspecialchars($a_description, ENT_NOQUOTES, 'utf-8'); ?><br/>
<em><a href="<?php echo $a_href ?> " target="_blank"><?php echo htmlspecialchars($a_href, ENT_NOQUOTES, 'utf-8'); ?></a></em>
<br/>
<hr/>
 </li>
<?php
 }
?>
 </ul>
<?php
}
?>
	
	</div>
	</div>


 </body>
</html>
