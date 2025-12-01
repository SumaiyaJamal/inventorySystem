<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="Responsive Admin &amp; Dashboard Template based on Bootstrap 5">
	<meta name="author" content="AdminKit">
	<meta name="keywords" content="adminkit, bootstrap, bootstrap 5, admin, dashboard, template, responsive, css, sass, html, theme, front-end, ui kit, web">

	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link rel="shortcut icon" href="{{ asset('assets/img/icons/icon-48x48.png') }}" />

	<link rel="canonical" href="https://demo-basic.adminkit.io/" />

	<title>Vendors | AdminKit Demo</title>

	<link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
</head>

<body>
	<div class="wrapper">
		<nav id="sidebar" class="sidebar js-sidebar">
			<div class="sidebar-content js-simplebar">
				<a class="sidebar-brand" href="{{ route('dashboard') }}">
          <span class="align-middle">AdminKit</span>
        </a>

				<ul class="sidebar-nav">
					<li class="sidebar-header">
						Pages
					</li>

					<li class="sidebar-item">
						<a class="sidebar-link" href="{{ route('dashboard') }}">
              <i class="align-middle" data-feather="sliders"></i> <span class="align-middle">Dashboard</span>
            </a>
					</li>

					@php
						$currentUser = auth()->user();
						$isAdmin = $currentUser && $currentUser->hasRole('admin');
						$isWarehouseManager = $currentUser && $currentUser->hasRole('warehouse_manager');
						$showLedLight = $isAdmin || ($isWarehouseManager && $currentUser->inventory_type == 'led_light');
						$showSpices = $isAdmin || ($isWarehouseManager && $currentUser->inventory_type == 'spices');
					@endphp

					@if($showLedLight)
						<li class="sidebar-item">
							<a class="sidebar-link" href="{{ route('inventory.led-lights') }}">
	              <i class="align-middle" data-feather="zap"></i> <span class="align-middle">Tube Light & Bulbs</span>
	            </a>
						</li>
					@endif

					@if($showSpices)
						<li class="sidebar-item">
							<a class="sidebar-link" href="{{ route('inventory.spices') }}">
	              <i class="align-middle" data-feather="package"></i> <span class="align-middle">Spices</span>
	            </a>
						</li>
					@endif

					@if($isAdmin)
						<li class="sidebar-header">
							User Management
						</li>

						<li class="sidebar-item">
							<a class="sidebar-link" href="{{ route('salesmen.index') }}">
	              <i class="align-middle" data-feather="users"></i> <span class="align-middle">Salesmen</span>
	            </a>
						</li>

						<li class="sidebar-item">
							<a class="sidebar-link" href="{{ route('warehouse-managers.index') }}">
	              <i class="align-middle" data-feather="box"></i> <span class="align-middle">Warehouse Manager</span>
	            </a>
						</li>

						<li class="sidebar-item active">
							<a class="sidebar-link" href="{{ route('vendors.index') }}">
	              <i class="align-middle" data-feather="truck"></i> <span class="align-middle">Vendors</span>
	            </a>
						</li>
					@endif
				</ul>
			</div>
		</nav>

		<div class="main">
			<nav class="navbar navbar-expand navbar-light navbar-bg">
				<a class="sidebar-toggle js-sidebar-toggle">
          <i class="hamburger align-self-center"></i>
        </a>

				<div class="navbar-collapse collapse">
					<ul class="navbar-nav navbar-align">
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#" data-bs-toggle="dropdown">
                <img src="{{ asset('assets/img/avatars/avatar.jpg') }}" class="avatar img-fluid rounded me-1" alt="Charles Hall" /> <span class="text-dark">{{ auth()->user()->name ?? 'Admin'}}</span>
              </a>
							<div class="dropdown-menu dropdown-menu-end">
								<a class="dropdown-item" href="#"><i class="align-middle me-1" data-feather="user"></i> Profile</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="{{ route('logout') }}">Log out</a>
							</div>
						</li>
					</ul>
				</div>
			</nav>

			<main class="content">
				<div class="container-fluid p-0">

					<div class="d-flex justify-content-between align-items-center mb-3">
						<h1 class="h3 mb-0"><strong>Vendors</strong> Management</h1>
						<div class="d-flex gap-2">
							<a href="{{ route('dashboard') }}" class="btn btn-secondary">
								<i class="align-middle me-1" data-feather="arrow-left"></i> Back to Dashboard
							</a>
							<a href="{{ route('vendors.create') }}" class="btn btn-primary">
								<i class="align-middle me-1" data-feather="plus"></i> Add New
							</a>
						</div>
					</div>

					@if(session('success'))
						<div class="alert alert-success alert-dismissible fade show" role="alert">
							{{ session('success') }}
							<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
						</div>
					@endif

					<div class="row">
						<div class="col-12">
							<div class="card">
								<div class="card-header">
									<h5 class="card-title mb-0">Vendors List</h5>
								</div>
								<div class="card-body">
									@if($vendors->count() > 0)
										<div class="table-responsive">
											<table class="table table-hover my-0">
												<thead>
													<tr>
														<th>Name</th>
														<th>Email</th>
														<th class="d-none d-md-table-cell">Location</th>
														<th class="d-none d-xl-table-cell">Created At</th>
														<th>Actions</th>
													</tr>
												</thead>
												<tbody>
													@foreach($vendors as $vendor)
														<tr>
															<td>{{ $vendor->name }}</td>
															<td>{{ $vendor->email }}</td>
															<td class="d-none d-md-table-cell">{{ $vendor->location ?? 'N/A' }}</td>
															<td class="d-none d-xl-table-cell">{{ $vendor->created_at->format('M d, Y') }}</td>
															<td>
																<div class="d-flex gap-1">
																	<a href="{{ route('vendors.edit', $vendor) }}" class="btn btn-sm btn-primary" title="Edit">
																		<i class="align-middle" data-feather="edit-2" style="width: 14px; height: 14px;"></i>
																	</a>
																	<form action="{{ route('vendors.destroy', $vendor) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this vendor?');">
																		@csrf
																		@method('DELETE')
																		<button type="submit" class="btn btn-sm btn-danger" title="Delete">
																			<i class="align-middle" data-feather="trash-2" style="width: 14px; height: 14px;"></i>
																		</button>
																	</form>
																</div>
															</td>
														</tr>
													@endforeach
												</tbody>
											</table>
										</div>
									@else
										<div class="alert alert-info text-center">
											<i class="align-middle me-2" data-feather="info"></i>
											No vendors found. Please add some vendors to get started.
										</div>
									@endif
								</div>
							</div>
						</div>
					</div>

				</div>
			</main>

			<footer class="footer">
				<div class="container-fluid">
					<div class="row text-muted">
						<div class="col-6 text-start">
							<p class="mb-0">
								<a class="text-muted" href="https://adminkit.io/" target="_blank"><strong>AdminKit</strong></a> &copy;
							</p>
						</div>
						<div class="col-6 text-end">
							<ul class="list-inline">
								<li class="list-inline-item">
									<a class="text-muted" href="https://adminkit.io/" target="_blank">Support</a>
								</li>
								<li class="list-inline-item">
									<a class="text-muted" href="https://adminkit.io/" target="_blank">Help Center</a>
								</li>
								<li class="list-inline-item">
									<a class="text-muted" href="https://adminkit.io/" target="_blank">Privacy</a>
								</li>
								<li class="list-inline-item">
									<a class="text-muted" href="https://adminkit.io/" target="_blank">Terms</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</footer>
		</div>
	</div>

	<script src="{{ asset('assets/js/app.js') }}"></script>

</body>

</html>

