<?php

require_once 'inc/functions.php';
include_once 'inc/labels.php';

if (isset($_GET['file'])) {
	$file = $_GET['file'];
} else {
	$file = 'bonnie.csv';
}
$data = parse_bonnie_csv($file);

/*if (!isset($ttype) || empty($ttype)) {
	echo <<<EOT
<!DOCTYPE html>
<html>
<head><title>Bonnie to Google Chart</title></head>
<body>
<h1>Bonnie to Google Chart</h1>
<ul>

EOT;

	foreach ($types as $key => $type) {
		printf('<li><a href="?t=%s">%s</a></li>', $key, $type['name']);
	}

	echo <<<EOT
</ul>
</body>
</html>
EOT;
	exit;
}*/

#$ttype = $_GET['t'];
$ttypes = array_keys($types);

echo <<<EOT
<html>
  <head>
	<title>bonnie2gchart - AAAA</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
EOT;

foreach ( $ttypes as $ttype ) {
	echo <<<EOT
	    <script type="text/javascript">
	      google.load("visualization", "1", {packages:["corechart"]});
	      google.setOnLoadCallback(drawChart);
	      function drawChart() {
		var data = new google.visualization.DataTable();
		data.addColumn('string', 'Tests');

EOT;

	foreach ($data['name'] as $val) {
		printf("        data.addColumn('number', '%s')\n", $val);
	}

	foreach($types[$ttype]['types'] as $label)
		echo addRow($data[$label], $labels[$label]);

	echo <<<EOT
		var options = {
		  title: '{$types[$ttype]['title']}',
		  vAxis: {title: '{$types[$ttype]['name']}',  titleTextStyle: {color: 'red'}}
		};

		var chart = new google.visualization.BarChart(document.getElementById('chart_div_{$ttype}'));
		chart.draw(data, options);

	      }
	    </script>
EOT;
}
echo <<<EOT
  </head>
  <body>
    <a href=".">« index<a>
EOT;
foreach ( $ttypes as $ttype ) {
	echo <<<EOT
	    <div id="chart_div_{$ttype}" style="width: 900px; height: 500px;"></div>
	  </body>
	</html>
EOT;
}
