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

	<title>{{ isset($inventory) ? 'Edit' : 'Create' }} Spice | AdminKit Demo</title>

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
						<li class="sidebar-item">
							<a class="sidebar-link" href="{{ route('inventory.led-lights') }}">
	              <i class="align-middle" data-feather="zap"></i> <span class="align-middle">Tube Light & Bulbs</span>
	            </a>
						</li>
					@endif

					@if($showSpices)
						<li class="sidebar-item active">
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
						<h1 class="h3 mb-0"><strong>{{ isset($inventory) ? 'Edit' : 'Create' }}</strong> Spice</h1>
						<a href="{{ route('inventory.spices') }}" class="btn btn-secondary">
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
									<h5 class="card-title mb-0">Spice Information</h5>
								</div>
								<div class="card-body">
									<form action="{{ isset($inventory) ? route('inventory.spices.update', $inventory) : route('inventory.spices.store') }}" method="POST">
										@csrf
										@if(isset($inventory))
											@method('PUT')
										@endif

										<div class="row">
											<div class="col-md-6 mb-3">
												<label for="spice_name" class="form-label">Spice Name <span class="text-danger">*</span></label>
												<input type="text" class="form-control @error('spice_name') is-invalid @enderror"
													id="spice_name" name="spice_name"
													value="{{ old('spice_name', isset($inventory) ? $inventory->spice_name : '') }}"
													placeholder="e.g., Turmeric, Cumin" required>
												@error('spice_name')
													<div class="invalid-feedback">{{ $message }}</div>
												@enderror
											</div>

											<div class="col-md-6 mb-3">
												<label for="category" class="form-label">Category</label>
												<select class="form-select @error('category') is-invalid @enderror"
													id="category" name="category">
													<option value="">Select Category</option>
													<option value="Whole" {{ old('category', isset($inventory) ? $inventory->category : '') == 'Whole' ? 'selected' : '' }}>Whole</option>
													<option value="Powder" {{ old('category', isset($inventory) ? $inventory->category : '') == 'Powder' ? 'selected' : '' }}>Powder</option>
													<option value="Mix" {{ old('category', isset($inventory) ? $inventory->category : '') == 'Mix' ? 'selected' : '' }}>Mix</option>
												</select>
												@error('category')
													<div class="invalid-feedback">{{ $message }}</div>
												@enderror
											</div>
										</div>

										<div class="row">
											<div class="col-md-6 mb-3">
												<label for="weight" class="form-label">Weight</label>
												<input type="text" class="form-control @error('weight') is-invalid @enderror"
													id="weight" name="weight"
													value="{{ old('weight', isset($inventory) ? $inventory->weight : '') }}"
													placeholder="e.g., 250g, 1kg">
												@error('weight')
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
												<label for="purchase_price" class="form-label">Purchase Price <span class="text-danger">*</span></label>
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
												<label for="selling_price" class="form-label">Selling Price <span class="text-danger">*</span></label>
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
												<label for="manufactured_date" class="form-label">Manufacturing Date</label>
												<input type="date" class="form-control @error('manufactured_date') is-invalid @enderror"
													id="manufactured_date" name="manufactured_date"
													value="{{ old('manufactured_date', isset($inventory) && $inventory->manufactured_date ? $inventory->manufactured_date->format('Y-m-d') : '') }}">
												@error('manufactured_date')
													<div class="invalid-feedback">{{ $message }}</div>
												@enderror
											</div>
										</div>

										<div class="row">
											<div class="col-md-6 mb-3">
												<label for="expiry_date" class="form-label">Expiry Date</label>
												<input type="date" class="form-control @error('expiry_date') is-invalid @enderror"
													id="expiry_date" name="expiry_date"
													value="{{ old('expiry_date', isset($inventory) && $inventory->expiry_date ? $inventory->expiry_date->format('Y-m-d') : '') }}">
												@error('expiry_date')
													<div class="invalid-feedback">{{ $message }}</div>
												@enderror
												<small class="form-text text-muted">Must be after or equal to manufacturing date</small>
											</div>
										</div>

										<div class="mb-3">
											<label for="storage_instructions" class="form-label">Storage Instructions</label>
											<textarea class="form-control @error('storage_instructions') is-invalid @enderror"
												id="storage_instructions" name="storage_instructions" rows="3"
												placeholder="e.g., Store in a cool, dry place away from direct sunlight">{{ old('storage_instructions', isset($inventory) ? $inventory->storage_instructions : '') }}</textarea>
											@error('storage_instructions')
												<div class="invalid-feedback">{{ $message }}</div>
											@enderror
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
											<a href="{{ route('inventory.spices') }}" class="btn btn-secondary">Cancel</a>
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

