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

	<title>{{ isset($inventory) ? 'Edit' : 'Create' }} LED Light & Bulbs | AdminKit Demo</title>

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
						$showLedLight = $isAdmin || ($isWarehouseManager && $user->inventory_type == 'led_light');
						$showSpices = $isAdmin || ($isWarehouseManager && $user->inventory_type == 'spices');
					@endphp

					@if($showLedLight)
						<li class="sidebar-item active">
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
						<h1 class="h3 mb-0"><strong>{{ isset($inventory) ? 'Edit' : 'Create' }}</strong> LED Light & Bulbs</h1>
						<a href="{{ route('inventory.led-lights') }}" class="btn btn-secondary">
							<i class="align-middle me-1" data-feather="arrow-left"></i> Back to List
						</a>
					</div>

					@if(session('success'))
						<div class="alert alert-success alert-dismissible fade show" role="alert">
							{{ session('success') }}
							<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
						</div>
					@endif

					@if ($errors->any())
						<div class="alert alert-danger alert-dismissible fade show" role="alert">
							<ul class="mb-0">
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
							<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
						</div>
					@endif

					<div class="row">
						<div class="col-12">
							<div class="card">
								<div class="card-header">
									<h5 class="card-title mb-0">LED Light & Bulbs Information</h5>
								</div>
								<div class="card-body">
									<form action="{{ isset($inventory) ? route('inventory.led-lights.update', $inventory) : route('inventory.led-lights.store') }}" method="POST">
										@csrf
										@if(isset($inventory))
											@method('PUT')
										@endif

										<div class="row">
											<div class="col-md-6 mb-3">
												<label for="product_name" class="form-label">Product Name <span class="text-danger">*</span></label>
												<input type="text" class="form-control @error('product_name') is-invalid @enderror"
													id="product_name" name="product_name"
													value="{{ old('product_name', isset($inventory) ? $inventory->product_name : '') }}"
													placeholder="e.g., Tube Light, Bulb" required>
												@error('product_name')
													<div class="invalid-feedback">{{ $message }}</div>
												@enderror
											</div>

											<div class="col-md-6 mb-3">
												<label for="brand" class="form-label">Brand</label>
												<input type="text" class="form-control @error('brand') is-invalid @enderror"
													id="brand" name="brand"
													value="{{ old('brand', isset($inventory) ? $inventory->brand : '') }}"
													placeholder="e.g., Philips, Osram">
												@error('brand')
													<div class="invalid-feedback">{{ $message }}</div>
												@enderror
											</div>
										</div>

										<div class="row">
											<div class="col-md-6 mb-3">
												<label for="wattage" class="form-label">Wattage</label>
												<input type="text" class="form-control @error('wattage') is-invalid @enderror"
													id="wattage" name="wattage"
													value="{{ old('wattage', isset($inventory) ? $inventory->wattage : '') }}"
													placeholder="e.g., 18W, 36W">
												@error('wattage')
													<div class="invalid-feedback">{{ $message }}</div>
												@enderror
											</div>

											<div class="col-md-6 mb-3">
												<label for="light_type" class="form-label">Type</label>
												<select class="form-select @error('light_type') is-invalid @enderror"
													id="light_type" name="light_type">
													<option value="">Select Type</option>
													<option value="LED" {{ old('light_type', isset($inventory) ? $inventory->light_type : '') == 'LED' ? 'selected' : '' }}>LED</option>
													<option value="CFL" {{ old('light_type', isset($inventory) ? $inventory->light_type : '') == 'CFL' ? 'selected' : '' }}>CFL</option>
													<option value="Halogen" {{ old('light_type', isset($inventory) ? $inventory->light_type : '') == 'Halogen' ? 'selected' : '' }}>Halogen</option>
													<option value="Tube" {{ old('light_type', isset($inventory) ? $inventory->light_type : '') == 'Tube' ? 'selected' : '' }}>Tube</option>
												</select>
												@error('light_type')
													<div class="invalid-feedback">{{ $message }}</div>
												@enderror
											</div>
										</div>

										<div class="row">
											<div class="col-md-6 mb-3">
												<label for="color_temperature" class="form-label">Color Temperature</label>
												<select class="form-select @error('color_temperature') is-invalid @enderror"
													id="color_temperature" name="color_temperature">
													<option value="">Select Color Temperature</option>
													<option value="Warm" {{ old('color_temperature', isset($inventory) ? $inventory->color_temperature : '') == 'Warm' ? 'selected' : '' }}>Warm</option>
													<option value="Cool" {{ old('color_temperature', isset($inventory) ? $inventory->color_temperature : '') == 'Cool' ? 'selected' : '' }}>Cool</option>
													<option value="Daylight" {{ old('color_temperature', isset($inventory) ? $inventory->color_temperature : '') == 'Daylight' ? 'selected' : '' }}>Daylight</option>
												</select>
												@error('color_temperature')
													<div class="invalid-feedback">{{ $message }}</div>
												@enderror
											</div>

											<div class="col-md-6 mb-3">
												<label for="quantity" class="form-label">Quantity <span class="text-danger">*</span></label>
												<input type="number" class="form-control @error('quantity') is-invalid @enderror"
													id="quantity" name="quantity"
													value="{{ old('quantity', isset($inventory) ? $inventory->quantity : '') }}"
													min="0" required>
												@error('quantity')
													<div class="invalid-feedback">{{ $message }}</div>
												@enderror
											</div>
										</div>

										<div class="row">
											<div class="col-md-6 mb-3">
												<label for="purchase_price" class="form-label">Purchase Price (per unit) <span class="text-danger">*</span></label>
												<div class="input-group">
													<span class="input-group-text">$</span>
													<input type="number" step="0.01" class="form-control @error('purchase_price') is-invalid @enderror"
														id="purchase_price" name="purchase_price"
														value="{{ old('purchase_price', isset($inventory) ? $inventory->purchase_price : '') }}"
														min="0" required>
													@error('purchase_price')
														<div class="invalid-feedback">{{ $message }}</div>
													@enderror
												</div>
											</div>

											<div class="col-md-6 mb-3">
												<label for="selling_price" class="form-label">Selling Price (per unit) <span class="text-danger">*</span></label>
												<div class="input-group">
													<span class="input-group-text">$</span>
													<input type="number" step="0.01" class="form-control @error('selling_price') is-invalid @enderror"
														id="selling_price" name="selling_price"
														value="{{ old('selling_price', isset($inventory) ? $inventory->selling_price : '') }}"
														min="0" required>
													@error('selling_price')
														<div class="invalid-feedback">{{ $message }}</div>
													@enderror
												</div>
											</div>
										</div>

										<div class="row">
											<div class="col-md-6 mb-3">
												<label for="supplier_name" class="form-label">Supplier Name <span class="text-danger">*</span></label>
												<input type="text" class="form-control @error('supplier_name') is-invalid @enderror"
													id="supplier_name" name="supplier_name"
													value="{{ old('supplier_name', isset($inventory) ? $inventory->supplier_name : '') }}" required>
												@error('supplier_name')
													<div class="invalid-feedback">{{ $message }}</div>
												@enderror
											</div>

											<div class="col-md-6 mb-3">
												<label for="purchase_date" class="form-label">Purchase Date</label>
												<input type="date" class="form-control @error('purchase_date') is-invalid @enderror"
													id="purchase_date" name="purchase_date"
													value="{{ old('purchase_date', isset($inventory) && $inventory->purchase_date ? $inventory->purchase_date->format('Y-m-d') : '') }}">
												@error('purchase_date')
													<div class="invalid-feedback">{{ $message }}</div>
												@enderror
											</div>
										</div>

										<div class="row">
											<div class="col-md-6 mb-3">
												<label for="warranty_months" class="form-label">Warranty Period (months)</label>
												<input type="number" class="form-control @error('warranty_months') is-invalid @enderror"
													id="warranty_months" name="warranty_months"
													value="{{ old('warranty_months', isset($inventory) ? $inventory->warranty_months : '') }}"
													min="0">
												@error('warranty_months')
													<div class="invalid-feedback">{{ $message }}</div>
												@enderror
											</div>
										</div>

										<div class="mb-3">
											<label for="notes" class="form-label">Notes</label>
											<textarea class="form-control @error('notes') is-invalid @enderror"
												id="notes" name="notes" rows="3"
												placeholder="Additional notes...">{{ old('notes', isset($inventory) ? $inventory->notes : '') }}</textarea>
											@error('notes')
												<div class="invalid-feedback">{{ $message }}</div>
											@enderror
										</div>

										<div class="d-flex justify-content-end gap-2">
											<a href="{{ route('inventory.led-lights') }}" class="btn btn-secondary">Cancel</a>
											<button type="submit" class="btn btn-primary">
												<i class="align-middle me-1" data-feather="save"></i> {{ isset($inventory) ? 'Update' : 'Create' }}
											</button>
										</div>
									</form>
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

