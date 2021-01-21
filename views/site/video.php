<?php
use kartik\date\DatePicker;
?>

<div class="container-fluid">
	<div class="row">
		<div class="col-md-6">
			<? if($_GET['n'] != 2){ ?>
			<div class="row">
				<div class="col-md-4">
					<iframe width="180" height="180" src="https://rtsp.me/embed/edRZF3tf/" frameborder="0" allowfullscreen></iframe>
				</div>
				<div class="col-md-4">
					<iframe width="180" height="180" src="https://rtsp.me/embed/aZ5iRkF9/" frameborder="0" allowfullscreen></iframe>
				</div>
				<div class="col-md-4">
					<iframe width="180" height="180" src="https://rtsp.me/embed/aZ5iRkF9/" frameborder="0" allowfullscreen></iframe>
				</div>
			</div>
			<div class="row">
				<div class="col-md-4">
					<iframe width="180" height="180" src="https://rtsp.me/embed/aZ5iRkF9/" frameborder="0" allowfullscreen></iframe>
				</div>
				<div class="col-md-4">
					<iframe width="180" height="180" src="https://rtsp.me/embed/aZ5iRkF9/" frameborder="0" allowfullscreen></iframe>
				</div>
				<div class="col-md-4">
					<iframe width="180" height="180" src="https://rtsp.me/embed/aZ5iRkF9/" frameborder="0" allowfullscreen></iframe>
				</div>
			</div>
			<div class="row">
				<div class="col-md-4">
					<iframe width="180" height="180" src="https://rtsp.me/embed/aZ5iRkF9/" frameborder="0" allowfullscreen></iframe>
				</div>
				<div class="col-md-4">
					<iframe width="180" height="180" src="https://rtsp.me/embed/aZ5iRkF9/" frameborder="0" allowfullscreen></iframe>
				</div>
				<div class="col-md-4">
					<iframe width="180" height="180" src="https://rtsp.me/embed/aZ5iRkF9/" frameborder="0" allowfullscreen></iframe>
				</div>
			</div>
			<? }else{ ?>
				<div class="row">
					<div class="col-md-6">
						<iframe width="250" height="250" src="https://rtsp.me/embed/edRZF3tf/" frameborder="0" allowfullscreen></iframe>
					</div>
					<div class="col-md-6">
						<iframe width="250" height="250" src="https://rtsp.me/embed/aZ5iRkF9/" frameborder="0" allowfullscreen></iframe>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<iframe width="250" height="250" src="https://rtsp.me/embed/aZ5iRkF9/" frameborder="0" allowfullscreen></iframe>
					</div>
					<div class="col-md-6">
						<iframe width="250" height="250" src="https://rtsp.me/embed/aZ5iRkF9/" frameborder="0" allowfullscreen></iframe>
					</div>
				</div>			
			<? } ?>
			
		</div>
		<div class="col-md-6">
			<div style="font-size: 30px;color: #3d3db9;">
				<a href="/web/site/video"><span class="glyphicon glyphicon-th"></span></a>
				<a href="/web/site/video?n=2"><span class="glyphicon glyphicon-th-large"></span></a>
			</div>
			<?
			
				$today = date("d-M-Y"); 
			
			    echo '<div class="border border-secondary rounded p-1 datepicker datepicker-inline">';
			    echo DatePicker::widget([
			        'name' => 'dp_5',
			        'value' => "$today",
			        'type' => DatePicker::TYPE_INLINE,
			    ]);
			    echo '</div>';
			?>
			
			
		</div>
	</div>
</div>