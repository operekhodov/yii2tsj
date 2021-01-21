<?php
use app\models\Mkd;
use app\models\Area;
use app\models\User;

$css= <<< CSS
#head_news {
	height: 7rem;
	display: table-cell;
	vertical-align: middle;
	float: unset;
}
.content-header {
	display:none;
}
.date_column{
    width: 20%;
    text-align:center;
}
.title_chart {
	font-size: 16px!important;
}
.pdr {
	margin-right: 5px;
}
.label {
	border: 1px solid white;
}
CSS;
$this->registerCss($css, ["type" => "text/css"], "hcs-thumbnail" );

$arr_users = User::getAllAreaUsersCount();
$sum_u = $arr_users["hu"] + $arr_users["u"] + $arr_users["g"];;
$u0 = 0;
$u2 = 0;
$u1 = 0;
//{ ["hu"]=> int(1) ["u"]=> int(0) ["g"]=> int(0) ["w"]=> int(1) ["b"]=> int(0) }
?>
<div class="container-fluid workplace">
	<div class="row">
		<div class="col-md-12">
			<div class="row">
				<div class="col-md-6">
					<div class="box box-primary">
						<div class="tabbable" id="tabs">
							<div class="box-header with-border">
								<div class="box-tools pull-right">
									<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
								</div>
								<div class="box-tools pull-left">
<h4>Данные системы</h4>
								</div>
							</div>
						</div>
						<div class="box-body">
							<div class="tab-content">
<div class='col-md-12'>	
		<div class='row'>
		    <div class='col-sm-6 col-xs-12'>
		        <div class='info-box bg-aqua'>
		            <span class='info-box-icon'><i class='fa fa-home'></i></span>
		            <div class='info-box-content'>
		                <span class='info-box-text'>МКД</span>
		                <span class='info-box-number'><?= Mkd::getMkdCount(0)+Mkd::getMkdCount(1) ?> </span>
		            </div>
					<span class='pull-right-container'>
						<small class='label pull-right bg-red pdr'> <?= Mkd::getMkdCount(0) ?></small> 
						<small class='label pull-right bg-green pdr'> <?= Mkd::getMkdCount(1) ?></small>
					</span>
		        </div>
		    </div>
		    <div class='col-sm-6 col-xs-12'>
		        <div class='info-box bg-green'>
		            <span class='info-box-icon'><i class='fa fa-user-plus'></i></span>
		            <div class='info-box-content'>
		                <span class='info-box-text'>Организации</span>
		                <span class='info-box-number'><?= Area::getAreaCount(0)+Area::getAreaCount(1) ?> </span>
		            </div>
					<span class='pull-right-container'>
						<small class='label pull-right bg-red pdr'> <?= Area::getAreaCount(0) ?></small> 
						<small class='label pull-right bg-green pdr'> <?= Area::getAreaCount(1) ?></small>
					</span>		            
		        </div>
		    </div>
		    <div class='col-sm-6 col-xs-12'>
		        <div class='info-box bg-yellow'>
		            <span class='info-box-icon'><i class='fa fa-users'></i></span>
		            <div class='info-box-content'>
		                <span class='info-box-text'>Количество жителей</span>
		                <span class='info-box-number'><?= $sum_u ?></span>
		            </div>
					<span class='pull-right-container'>
						<small class='label pull-right bg-red pdr'><?= $u0 ?></small> 
						<small class='label pull-right bg-gray pdr'><?= $u2 ?></small> 
						<small class='label pull-right bg-green pdr'><?= $u1 ?></small>
					</span>                    
		        </div>
		    </div>
		    <div class='col-sm-6 col-xs-12'>
		        <div class='info-box bg-red'>
		            <span class='info-box-icon'><i class='fa fa-pie-chart'></i></span>
		            <div class='info-box-content'>
		                <span class='info-box-text'>Задолжность по МКД</span>
		                <span class='info-box-number'>41,410</span>
		            </div>
		        </div>
		    </div>
		</div>
	</div>
</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>