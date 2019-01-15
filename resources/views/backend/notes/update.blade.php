<?php
	$breadcrumb = [];
	$breadcrumb[0]['title'] = 'Dashboard';
	$breadcrumb[0]['url'] = url('backend/dashboard');
	$breadcrumb[1]['title'] = 'Notes';
	$breadcrumb[1]['url'] = url('backend/notes');	
	$breadcrumb[2]['title'] = 'Add';
	$breadcrumb[2]['url'] = url('backend/notes/create');
	if (isset($data)){
		$breadcrumb[2]['title'] = 'Edit';
		$breadcrumb[2]['url'] = url('backend/notes/'.$data[0]->id.'/edit');
	}
?>

<!-- LAYOUT -->
@extends('backend.layouts.main')

<!-- TITLE -->
@section('title')
	<?php
		$mode = "Create";
		if (isset($data)){
			$mode = "Edit";
		}
	?>
    Notes - <?=$mode;?>
@endsection

<!-- CONTENT -->
@section('content')
	<?php
		$note = old('note');
		$active = 1;
		$method = "POST";
		$mode = "Create";
		$url = "backend/notes/";
		if (isset($data)){
			$note = $data[0]->note;
			$active = $data[0]->active;
			$method = "PUT";
			$mode = "Edit";
			$url = "backend/notes/".$data[0]->id;
		}
	?>
	<div class="page-title">
		<div class="title_left">
			<h3>Notes - <?=$mode;?></h3>
		</div>
		<div class="title_right">
			<div class="col-md-4 col-sm-4 col-xs-8 form-group pull-right top_search">
                @include('backend.elements.back_button',array('url' => '/backend/notes'))
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
					{{ Form::open(['url' => $url, 'method' => $method,'class' => 'form-horizontal form-label-left']) }}
						{!! csrf_field() !!}
						<div class="form-group">
							<label class="control-label col-sm-3 col-xs-12">Keterangan <span class="required">*</span></label>
							<div class="col-sm-7 col-xs-12">
                                <textarea required="required" rows=5 name="note"  class="form-control"><?=$note;?></textarea>								
							</div>
						</div>
						<div class="ln_solid"></div>
						<div class="form-group">
							<div class="col-sm-6 col-xs-12 col-sm-offset-3">
								<a href="<?=url('/backend/notes')?>" class="btn btn-warning">Cancel</a>
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

@endsection