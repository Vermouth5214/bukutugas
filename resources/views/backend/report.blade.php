<?php
	$breadcrumb = [];
	$breadcrumb[0]['title'] = 'Dashboard';
	$breadcrumb[0]['url'] = url('backend/dashboard');
	$breadcrumb[1]['title'] = 'General Report';
	$breadcrumb[1]['url'] = url('backend/general-report');
?>

<!-- LAYOUT -->
@extends('backend.layouts.main')

<!-- TITLE -->
@section('title', 'Set Waktu')

<!-- CONTENT -->
@section('content')
	<div class="page-title">
		<div class="title_left" style="width : 100%">
			<h3>General Report</h3>
		</div>
	</div>
	<div class="clearfix"></div>
	@include('backend.elements.breadcrumb',array('breadcrumb' => $breadcrumb))
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="x_panel">
				<div class="x_content">
                    {{ Form::open(['url' => 'backend/general-report', 'method' => 'GET','class' => 'form-horizontal']) }}
                    <div class="row">
                        <div class="col-xs-12 col-sm-2 text-right" style="margin-top:7px;">
                            User
                        </div>
                        <div class="col-xs-12 col-sm-4">
                            {{
                            Form::select(
                                'user',
                                $user,
                                $id_user,
                                array(
                                    'class' => 'form-control',
                                ))
                            }}
                        </div>
                    </div>
                    <br/>
                    <div class="row">
                        <div class="col-xs-12 col-sm-2 text-right" style="margin-top:7px;">
                            Tanggal
                        </div>
                        <div class="col-xs-12 col-sm-3 date">
                            <div class='input-group date' id='myDatepicker'>
                                <input type='text' class="form-control" name="startDate" value=<?=$startDate;?> />
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-3 date">
                            <div class='input-group date' id='myDatepicker2'>
                                <input type='text' class="form-control" name="endDate" value=<?=$endDate;?> />
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-2">
                            <input type="submit" class="btn btn-primary btn-block" value="Submit">
                        </div>
                    </div>
                    {{ Form::close() }}
				</div>
			</div>
		</div>
    </div>
    
    <?php
        if (isset($_GET['startDate'])):
    ?>
    <div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="x_panel">
				<div class="x_content">
                    <h2>User : <?=$data_user[0]->firstname.' '.$data_user[0]->lastname ?></h2>
                    <div class="row row--flex">
                        <div class="col-xs-12 col-order-1">
                            <h2>Detail</h2>
                            <?php
                                $tanggal = '';
                                $i = 1; 
                                $jam_kosong = 0;
                                $jam_akhir = 0;
                                $jam_kerja = 0;
                                foreach ($data_workbook as $workbook):
                            ?>
                                <?php
                                    $jam_kerja = $jam_kerja + strtotime($workbook->akhir) - strtotime($workbook->awal);
                                    if (($i > 1) && ($tanggal <> $workbook->tanggal)){
                                        echo "<hr/>";
                                        $jam_akhir = 0;
                                        $i = 1;
                                    }
                                    if (($i > 1) && ($tanggal = $workbook->tanggal)){
                                        if (strtotime($workbook->awal) - strtotime($jam_akhir) >= 0){
                                            $jam_kosong = $jam_kosong + strtotime($workbook->awal) - strtotime($jam_akhir);
                                        }
                                    }
                                ?>
                                <br/>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-3">
                                        <?=date('d M Y', strtotime($workbook->tanggal));?>
                                    </div>
                                    <div class="col-xs-12 col-sm-3">
                                        <?=date('H:i', strtotime($workbook->awal));?> - <?=date('H:i', strtotime($workbook->akhir));?>
                                    </div>
                                    <div class="col-xs-12 col-sm-2">
                                        <?=$workbook->requester;?>
                                    </div>
                                    <div class="col-xs-12 col-sm-4">
                                        <?=nl2br($workbook->keterangan);?>
                                    </div>
                                </div>
                            <?php
                                    $tanggal = $workbook->tanggal;
                                    $jam_akhir = $workbook->akhir;
                                    $i++;
                                endforeach;
                            ?>
                        </div>
                        <div class="col-xs-12">
                            <div class="row">
                                <div class="col-xs-12">
                                    <h4>Durasi Kerja (Jam) : <?=number_format($jam_kerja / 3600, 2,',','.');?></h4>
                                    <h4>Durasi Kosong (Jam) : <?=number_format($jam_kosong / 3600, 2,',','.');?></h4>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-xs-12 col-sm-4">
                                    <canvas id="pieChartR"></canvas>
                                </div>
                            </div>
                            <hr>
                        </div>
                    </div>
				</div>
			</div>
		</div>
    </div>
    <?php
        endif;
    ?>
@endsection

<!-- CSS -->
@section('css')
    <style>
        .row--flex {
            display: flex;
            flex-wrap: wrap;
        }
        .col-order-1 {
            order: 1;
        }
    </style>
@endsection

<!-- JAVASCRIPT -->
@section('script')
    <!-- Chart.js -->
    <script src="<?=url('vendors/Chart.js/dist/Chart.min.js');?>"></script>
    <script>
        $('.date').datetimepicker({
            format: 'DD-MM-YYYY',
        });
    </script>
    <?php
        if (isset($_GET['startDate'])):
    ?>
    <script>
        var ctx = document.getElementById("pieChartR").getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'doughnut',
                data: {
                    datasets: [{
                        data: [<?=$jam_kerja / 3600;?>, <?=$jam_kosong / 3600;?>],
                        backgroundColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)'
                        ],
                        label: 'Dataset 1'
                    }],
                    labels: [
                        'Durasi Kerja',
                        'Durasi Kosong'
                    ]
                },
                options: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: false,
                        text: 'Chart.js Doughnut Chart'
                    },
                    animation: {
                        animateScale: true,
                        animateRotate: true
                    }
                }
        });
    </script>
    <?php
        endif;
    ?>
@endsection