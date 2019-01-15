<?php
	$breadcrumb = [];
	$breadcrumb[0]['title'] = 'Dashboard';
	$breadcrumb[0]['url'] = url('backend/dashboard');
	$breadcrumb[1]['title'] = 'Notes';
	$breadcrumb[1]['url'] = url('backend/notes');
?>

<!-- LAYOUT -->
@extends('backend.layouts.main')

<!-- TITLE -->
@section('title', 'Notes')

<!-- CONTENT -->
@section('content')
	<div class="page-title">
		<div class="title_left">
			<h3>Notes</h3>
		</div>
		<div class="title_right">
			<div class="col-md-4 col-sm-4 col-xs-8 form-group pull-right top_search">
                <a href="<?=url('/backend/notes/create');?>" class="btn-index btn btn-primary btn-block" title="Add"><i class="fa fa-plus"></i>&nbsp; Add</a>
			</div>
		</div>
	</div>
	<div class="clearfix"></div>
	@include('backend.elements.breadcrumb',array('breadcrumb' => $breadcrumb))	
	<div class="row">
		<div class="col-xs-12">
			<div class="x_panel">
				<div class="x_content">
					@include('backend.elements.notification')
                    <table class="table table-striped table-hover table-bordered dt-responsive nowrap dataTable" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th>ID</th>
								<th>Keterangan</th>
								<th>Actions</th>
							</tr>
						</thead>
					</table>
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
		$('.dataTable').dataTable({
			processing: true,
			serverSide: true,
			ajax: "<?=url('backend/notes/datatable');?>",
			columns: [
				{data: 'id', name: 'id'},
				{data: 'note', name: 'note'},
				{data: 'action', name: 'action', orderable: false, searchable: false}
			],
			responsive: true
		});
	</script>
@endsection