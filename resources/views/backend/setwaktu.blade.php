<?php
	$breadcrumb = [];
	$breadcrumb[0]['title'] = 'Dashboard';
	$breadcrumb[0]['url'] = url('backend/dashboard');
	$breadcrumb[1]['title'] = 'Setting';
	$breadcrumb[1]['url'] = url('backend/set-waktu');
?>

<!-- LAYOUT -->
@extends('backend.layouts.main')

<!-- TITLE -->
@section('title', 'Set Waktu')

<!-- CONTENT -->
@section('content')
	<div class="page-title">
		<div class="title_left" style="width : 100%">
			<h3>Set Waktu</h3>
		</div>
	</div>
	<div class="clearfix"></div>
	@include('backend.elements.breadcrumb',array('breadcrumb' => $breadcrumb))
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="x_panel">
				<div class="x_content">
                    @include('backend.elements.notification')
                    {{ Form::open(['url' => 'backend/set-waktu', 'method' => 'POST','class' => 'form-horizontal', 'files' => true]) }}
                        <?php
                            foreach ($data as $item):
                        ?>
                        <div class="form-group">
                            <label class="control-label col-sm-1 col-xs-12"><?=$item->hari;?></label>
                            <label class="control-label col-sm-1 col-xs-12">Jam Kerja</label>
                            <div class="col-sm-2 col-xs-12">
                                <div class='input-group date'>
                                    <input type='text' class="form-control" name='jam_masuk_<?=$item->id;?>' required value='<?=date("H:i", strtotime($item->jam_masuk));?>' />
                                    <span class="input-group-addon">
                                       <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                            <div class="col-sm-2 col-xs-12">
                                <div class='input-group date'>
                                    <input type='text' class="form-control" name='jam_pulang_<?=$item->id;?>' required value='<?=date("H:i", strtotime($item->jam_pulang));?>' />
                                    <span class="input-group-addon">
                                       <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                            <label class="control-label col-sm-2 col-xs-12">Jam Istirahat</label>
                            <div class="col-sm-2 col-xs-12">
                                <div class='input-group date'>
                                    <input type='text' class="form-control" name='istirahat_awal_<?=$item->id;?>' required value='<?=date("H:i", strtotime($item->istirahat_awal));?>' />
                                    <span class="input-group-addon">
                                       <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                            <div class="col-sm-2 col-xs-12">
                                <div class='input-group date'>
                                    <input type='text' class="form-control" name='istirahat_akhir_<?=$item->id;?>' required value='<?=date("H:i", strtotime($item->istirahat_akhir));?>' />
                                    <span class="input-group-addon">
                                       <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                       </div>
                        <?php
                            endforeach;
                        ?>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <button type="submit" class="btn btn-primary btn-block">Submit </button>
                            </div>
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
        $('.date').datetimepicker({
            format: 'HH:mm',
            stepping: 15,
        });
    </script>
@endsection