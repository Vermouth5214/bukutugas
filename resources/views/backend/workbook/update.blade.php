<?php
	$breadcrumb = [];
	$breadcrumb[0]['title'] = 'Dashboard';
	$breadcrumb[0]['url'] = url('backend/dashboard');
	$breadcrumb[1]['title'] = 'Workbook';
	$breadcrumb[1]['url'] = url('backend/workbook');	
	$breadcrumb[2]['title'] = 'Add';
	$breadcrumb[2]['url'] = url('backend/workbook/create');
?>

<!-- LAYOUT -->
@extends('backend.layouts.main')

<!-- TITLE -->
@section('title')
	<?php
		$mode = "Create";
	?>
    Workbook - <?=$mode;?>
@endsection

<!-- CONTENT -->
@section('content')
    <?php
        $hari = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];
        $bulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
		$tanggal = $hari[date('N')-1].', '.date('d').' '.$bulan[date('n')-1].' '.date('Y');
		$active = 1;
		$method = "POST";
		$mode = "Create";
		$url = "backend/workbook/";
	?>
	<div class="page-title">
		<div class="title_left">
			<h3>Workbook - <?=$mode;?></h3>
		</div>
		<div class="title_right">
			<div class="col-md-4 col-sm-4 col-xs-8 form-group pull-right top_search">
                @include('backend.elements.back_button',array('url' => '/backend/workbook'))
			</div>
        </div>
        <div class="clearfix"></div>
		@include('backend.elements.breadcrumb',array('breadcrumb' => $breadcrumb))
	</div>
	<div class="clearfix"></div>
	<br/><br/>	
	<div class="row">
		<div class="col-xs-12">
			<div class="x_panel">
				<div class="x_content">
                    @include('backend.elements.notification')
					{{ Form::open(['id' => 'form', 'url' => $url, 'method' => $method,'class' => 'form-horizontal form-label-left']) }}
						{!! csrf_field() !!}
						<div class="form-group">
							<label class="control-label col-sm-3 col-xs-12">Tanggal</label>
							<div class="col-sm-7 col-xs-12">
                                <h4><?=$tanggal;?></h4>
							</div>
                        </div>
						<div class="form-group">
							<label class="control-label col-sm-3 col-xs-12">Jam Mulai</label>
							<div class="col-sm-2 col-xs-12">
                                <div class='input-group date' id='JamMulai'>
                                    <input type='text' class="form-control" name='awal' required />
                                    <span class="input-group-addon">
                                       <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
							</div>
                        </div>
						<div class="form-group">
							<label class="control-label col-sm-3 col-xs-12">Jam Selesai</label>
							<div class="col-sm-2 col-xs-12">
                                <div class='input-group date' id='JamSelesai'>
                                    <input type='text' class="form-control" name='akhir' required />
                                    <span class="input-group-addon">
                                       <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
							</div>
                        </div>
						<div class="form-group">
							<label class="control-label col-sm-3 col-xs-12">Diminta oleh</label>
							<div class="col-sm-4 col-xs-12">
                                <input type='text' class="form-control" name='requester' required />
							</div>
                        </div>
						<div class="form-group">
							<label class="control-label col-sm-3 col-xs-12">Keterangan</label>
							<div class="col-sm-5 col-xs-12">
                                <textarea required="required" rows=5 name="keterangan"  class="form-control"></textarea>
							</div>
                        </div>
						<div class="ln_solid"></div>
						<div class="form-group">
							<div class="col-sm-6 col-xs-12 col-sm-offset-3">
								<a href="<?=url('/backend/workbook')?>" class="btn btn-warning">Cancel</a>
								<button type="submit" class="btn btn-primary">Submit </button>
							</div>
						</div>
					{{ Form::close() }}
				</div>
			</div>
		</div>
	</div>
@endsection

<!-- CSS -->
@section('css')

@endsection

<!-- JAVASCRIPT -->
@section('script')
    <script>
        $('#JamMulai').datetimepicker({
            format: 'HH:mm',
            stepping: 15,
        });
        $('#JamSelesai').datetimepicker({
            format: 'HH:mm',
            stepping: 15,
        });
    </script>
@endsection