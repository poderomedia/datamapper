<?php
	the_post();
?>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		
		<title>Mapa de Conexiones</title>
		
		<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/vendors/nodemapper/css/pty.css">
		<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/vendors/nodemapper/css/font-awesome.min.css">
		
		<script src="<?php echo get_stylesheet_directory_uri(); ?>/vendors/nodemapper/js/lib/d3.min.js"></script>
		<script src="<?php echo get_stylesheet_directory_uri(); ?>/vendors/nodemapper/src/pty.js"></script>
		<script src="<?php echo get_stylesheet_directory_uri(); ?>/vendors/nodemapper/js/lib/underscore.js"></script>
	</head>
	
	<body>
		<div id="canvas"></div>
		
		<script>
			<?php if(isset($_GET['profile_id'])) : ?>
			d3.json('<?php bloginfo('url'); ?>/get-viz/?profile_id=<?php echo $_GET['profile_id']; ?>&profile_type=<?php echo $_GET['profile_type']; ?>', function(error, data) {
				if (error) { return error; }
				
				var width = 700,
				height = 350;
				
				var legend = [
					{name: 'Persona',     type: 'person'},
					{name: 'Organización', type: 'organization'},
					{name: 'Conflicto', type: 'conflict'}
				];
				
				/*var chart01 = pty.chart.network()
					.width(width)
					.height(height)
					.nodeRadius(15)
					//.nodeBaseURL(function(d) { return 'test2.json'; })
					.nodeLabel(function(d) { return d.name; })
					.nodeClass(function(d) { return d.type; })
					.legendItems(legend);
				
				d3.select('div#canvas').data([data]).call(chart01);*/

				var chart01 = pty.chart.network()
					.width(width)
					.height(height)
					.nodeRadius(15)
					.nodeLabel(function(d) { return d.name; })
					.nodeClass(function(d) { return d.type; })
					.nodeBaseURL(function(d) { return '<?php echo bloginfo('url'); ?>/get-viz/?profile_id=' + d.profile_id; })
					.nodeURL(function(d) { return '<?php echo bloginfo('url'); ?>/?p=' + d.profile_id; })
					.nodeDescription(function(d) { return d.description; })
					.legendItems(legend)
					.textBox({x: 10, y: 220, width: 220, height: 300});

				d3.select('div#canvas').data([data]).call(chart01);
			});
			<?php endif; ?>
		</script>
	</body>
</html>