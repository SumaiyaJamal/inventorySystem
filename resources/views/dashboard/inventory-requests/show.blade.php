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

	<title>View Inventory Request | AdminKit Demo</title>

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
						<li class="sidebar-item">
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

						<li class="sidebar-item">
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
						<h1 class="h3 mb-0"><strong>Inventory Request</strong> #{{ $inventoryRequest->id }}</h1>
						<div class="d-flex gap-2">
							@if($isSalesman)
								<a href="{{ route('inventory-requests.index') }}" class="btn btn-secondary">
									<i class="align-middle me-1" data-feather="arrow-left"></i> Back to List
								</a>
							@else
								<a href="{{ route('inventory-requests.all') }}" class="btn btn-secondary">
									<i class="align-middle me-1" data-feather="arrow-left"></i> Back to List
								</a>
							@endif
						</div>
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
						<div class="col-md-4">
							<div class="card">
								<div class="card-header">
									<h5 class="card-title mb-0">Request Information</h5>
								</div>
								<div class="card-body">
									<p><strong>Request ID:</strong> #{{ $inventoryRequest->id }}</p>
									<p><strong>Status:</strong> 
										@if($inventoryRequest->status == 'pending')
											<span class="badge bg-warning">Pending</span>
										@elseif($inventoryRequest->status == 'approved')
											<span class="badge bg-success">Approved</span>
										@else
											<span class="badge bg-danger">Rejected</span>
										@endif
									</p>
									<p><strong>Requested By:</strong> {{ $inventoryRequest->user->name }}</p>
									<p><strong>Email:</strong> {{ $inventoryRequest->user->email }}</p>
									<p><strong>Location:</strong> {{ $inventoryRequest->user->location ?? 'N/A' }}</p>
									<p><strong>Created At:</strong> {{ $inventoryRequest->created_at->format('M d, Y H:i') }}</p>
									@if($inventoryRequest->approved_at)
										<p><strong>Processed At:</strong> {{ $inventoryRequest->approved_at->format('M d, Y H:i') }}</p>
										@if($inventoryRequest->approvedBy)
											<p><strong>Processed By:</strong> {{ $inventoryRequest->approvedBy->name }}</p>
										@endif
									@endif
									@if($inventoryRequest->notes)
										<p><strong>Notes:</strong><br>{{ $inventoryRequest->notes }}</p>
									@endif
								</div>
							</div>

							@if($inventoryRequest->status == 'pending' && ($isAdmin || $isWarehouseManager))
								<div class="card mt-3">
									<div class="card-body">
										<h6 class="card-title">Actions</h6>
										<div class="d-grid gap-2">
											<form action="{{ route('inventory-requests.approve', $inventoryRequest) }}" method="POST" onsubmit="return confirm('Are you sure you want to approve this request? This will deduct quantities from inventory.');">
												@csrf
												<button type="submit" class="btn btn-success w-100">
													<i class="align-middle me-1" data-feather="check"></i> Approve Request
												</button>
											</form>
											<form action="{{ route('inventory-requests.reject', $inventoryRequest) }}" method="POST" onsubmit="return confirm('Are you sure you want to reject this request?');">
												@csrf
												<button type="submit" class="btn btn-danger w-100">
													<i class="align-middle me-1" data-feather="x"></i> Reject Request
												</button>
											</form>
										</div>
									</div>
								</div>
							@endif
						</div>

						<div class="col-md-8">
							<div class="card">
								<div class="card-header">
									<h5 class="card-title mb-0">Requested Items</h5>
								</div>
								<div class="card-body">
									@if($inventoryRequest->items->count() > 0)
										<div class="table-responsive">
											<table class="table table-hover">
												<thead>
													<tr>
														<th>Item</th>
														<th>Type</th>
														<th>Available</th>
														<th>Requested</th>
														<th>Status</th>
													</tr>
												</thead>
												<tbody>
													@foreach($inventoryRequest->items as $item)
														<tr>
															<td>
																<strong>{{ $item->inventory->product_name ?? $item->inventory->spice_name }}</strong>
																@if($item->inventory->brand)
																	<br><small class="text-muted">Brand: {{ $item->inventory->brand }}</small>
																@endif
																@if($item->inventory->category)
																	<br><small class="text-muted">Category: {{ $item->inventory->category }}</small>
																@endif
															</td>
															<td>
																<span class="badge bg-info">{{ ucfirst($item->inventory->type) }}</span>
															</td>
															<td>{{ $item->inventory->quantity }}</td>
															<td><strong>{{ $item->requested_quantity }}</strong></td>
															<td>
																@if($item->requested_quantity > $item->inventory->quantity)
																	<span class="badge bg-danger">Insufficient</span>
																@else
																	<span class="badge bg-success">Available</span>
																@endif
															</td>
														</tr>
													@endforeach
												</tbody>
												<tfoot>
													<tr>
														<th colspan="3">Total Quantity</th>
														<th>{{ $inventoryRequest->total_quantity }}</th>
														<td></td>
													</tr>
												</tfoot>
											</table>
										</div>
									@else
										<div class="alert alert-info">No items in this request.</div>
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



