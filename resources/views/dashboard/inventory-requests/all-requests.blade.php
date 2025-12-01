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

	<title>All Inventory Requests | AdminKit Demo</title>

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
						$user = auth()->user();
						$isAdmin = $user && $user->hasRole('admin');
						$isWarehouseManager = $user && $user->hasRole('warehouse_manager');
						$isSalesman = $user && $user->hasRole('salesman');
						$showLedLight = $isAdmin || ($isWarehouseManager && isset($user->inventory_type) && $user->inventory_type == 'led_light');
						$showSpices = $isAdmin || ($isWarehouseManager && isset($user->inventory_type) && $user->inventory_type == 'spices');
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

					@if($isSalesman)
						<li class="sidebar-item">
							<a class="sidebar-link" href="{{ route('inventory-requests.index') }}">
	              <i class="align-middle" data-feather="shopping-cart"></i> <span class="align-middle">Request Inventory</span>
	            </a>
						</li>
					@endif

					@if($isAdmin || $isWarehouseManager)
						<li class="sidebar-item active">
							<a class="sidebar-link" href="{{ route('inventory-requests.all') }}">
	              <i class="align-middle" data-feather="inbox"></i> <span class="align-middle">Inventory Requests</span>
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
						<h1 class="h3 mb-0"><strong>All Inventory</strong> Requests</h1>
						<a href="{{ route('dashboard') }}" class="btn btn-secondary">
							<i class="align-middle me-1" data-feather="arrow-left"></i> Back to Dashboard
						</a>
					</div>

					@if(session('success'))
						<div class="alert alert-success alert-dismissible fade show" role="alert">
							{{ session('success') }}
							<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
						</div>
					@endif

					@if(session('error'))
						<div class="alert alert-danger alert-dismissible fade show" role="alert">
							{{ session('error') }}
							<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
						</div>
					@endif

					<div class="row">
						<div class="col-12">
							<div class="card">
								<div class="card-header">
									<h5 class="card-title mb-0">All Requests</h5>
								</div>
								<div class="card-body">
									@if($requests->count() > 0)
										<div class="table-responsive">
											<table class="table table-hover my-0">
												<thead>
													<tr>
														<th>Request #</th>
														<th>Requested By</th>
														<th>Items</th>
														<th>Total Quantity</th>
														<th>Status</th>
														<th class="d-none d-md-table-cell">Created At</th>
														<th>Actions</th>
													</tr>
												</thead>
												<tbody>
													@foreach($requests as $request)
														<tr>
															<td>#{{ $request->id }}</td>
															<td>
																{{ $request->user->name }}
																<small class="text-muted d-block">{{ $request->user->email }}</small>
															</td>
															<td>
																{{ $request->items->count() }} item(s)
																@if($request->items->count() > 0)
																	<small class="text-muted d-block">
																		{{ $request->items->first()->inventory->product_name ?? $request->items->first()->inventory->spice_name }}
																		@if($request->items->count() > 1)
																			+ {{ $request->items->count() - 1 }} more
																		@endif
																	</small>
																@endif
															</td>
															<td>{{ $request->total_quantity }}</td>
															<td>
																@if($request->status == 'pending')
																	<span class="badge bg-warning">Pending</span>
																@elseif($request->status == 'approved')
																	<span class="badge bg-success">Approved</span>
																@else
																	<span class="badge bg-danger">Rejected</span>
																@endif
															</td>
															<td class="d-none d-md-table-cell">{{ $request->created_at->format('M d, Y H:i') }}</td>
															<td>
																<div class="d-flex gap-1">
																	<a href="{{ route('inventory-requests.show', $request) }}" class="btn btn-sm btn-info" title="View">
																		<i class="align-middle" data-feather="eye" style="width: 14px; height: 14px;"></i>
																	</a>
																	@if($request->status == 'pending')
																		<form action="{{ route('inventory-requests.approve', $request) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to approve this request? This will deduct quantities from inventory.');">
																			@csrf
																			<button type="submit" class="btn btn-sm btn-success" title="Approve">
																				<i class="align-middle" data-feather="check" style="width: 14px; height: 14px;"></i>
																			</button>
																		</form>
																		<form action="{{ route('inventory-requests.reject', $request) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to reject this request?');">
																			@csrf
																			<button type="submit" class="btn btn-sm btn-danger" title="Reject">
																				<i class="align-middle" data-feather="x" style="width: 14px; height: 14px;"></i>
																			</button>
																		</form>
																	@endif
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
											No inventory requests found.
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



