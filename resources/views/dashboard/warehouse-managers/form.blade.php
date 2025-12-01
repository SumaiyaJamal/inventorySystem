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

	<title>{{ isset($user) ? 'Edit' : 'Create' }} Warehouse Manager | AdminKit Demo</title>

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

						<li class="sidebar-item active">
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
						<h1 class="h3 mb-0"><strong>{{ isset($user) ? 'Edit' : 'Create' }}</strong> Warehouse Manager</h1>
						<a href="{{ route('warehouse-managers.index') }}" class="btn btn-secondary">
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
									<h5 class="card-title mb-0">Warehouse Manager Information</h5>
								</div>
								<div class="card-body">
									<form action="{{ isset($user) ? route('warehouse-managers.update', $user) : route('warehouse-managers.store') }}" method="POST">
										@csrf
										@if(isset($user))
											@method('PUT')
										@endif

										<div class="row">
											<div class="col-md-6 mb-3">
												<label for="name" class="form-label">Name <span class="text-danger">*</span></label>
												<input type="text" class="form-control @error('name') is-invalid @enderror" 
													id="name" name="name" 
													value="{{ old('name', isset($user) ? $user->name : '') }}" 
													required>
												@error('name')
													<div class="invalid-feedback">{{ $message }}</div>
												@enderror
											</div>

											<div class="col-md-6 mb-3">
												<label for="email" class="form-label">Email <span class="text-danger">*</span></label>
												<input type="email" class="form-control @error('email') is-invalid @enderror" 
													id="email" name="email" 
													value="{{ old('email', isset($user) ? $user->email : '') }}" 
													required>
												@error('email')
													<div class="invalid-feedback">{{ $message }}</div>
												@enderror
											</div>
										</div>

										<div class="row">
											<div class="col-md-6 mb-3">
												<label for="password" class="form-label">Password <span class="text-danger">*</span>@if(isset($user))<small class="text-muted"> (Leave blank to keep current password)</small>@endif</label>
												<input type="password" class="form-control @error('password') is-invalid @enderror" 
													id="password" name="password" 
													{{ !isset($user) ? 'required' : '' }}
													minlength="8">
												@error('password')
													<div class="invalid-feedback">{{ $message }}</div>
												@enderror
											</div>

											<div class="col-md-6 mb-3">
												<label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span>@if(isset($user))<small class="text-muted"> (Leave blank to keep current password)</small>@endif</label>
												<input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
													id="password_confirmation" name="password_confirmation" 
													{{ !isset($user) ? 'required' : '' }}
													minlength="8">
												@error('password_confirmation')
													<div class="invalid-feedback">{{ $message }}</div>
												@enderror
											</div>
										</div>

										<div class="row">
											<div class="col-md-6 mb-3">
												<label for="location" class="form-label">Location</label>
												<input type="text" class="form-control @error('location') is-invalid @enderror" 
													id="location" name="location" 
													value="{{ old('location', isset($user) ? $user->location : '') }}" 
													placeholder="e.g., New York, London">
												@error('location')
													<div class="invalid-feedback">{{ $message }}</div>
												@enderror
											</div>

											<div class="col-md-6 mb-3">
												<label for="inventory_type" class="form-label">Inventory Type <span class="text-danger">*</span></label>
												<select class="form-select @error('inventory_type') is-invalid @enderror" 
													id="inventory_type" name="inventory_type" required>
													<option value="">Select Inventory Type</option>
													<option value="spices" {{ old('inventory_type', isset($user) ? $user->inventory_type : '') == 'spices' ? 'selected' : '' }}>Spices</option>
													<option value="led_light" {{ old('inventory_type', isset($user) ? $user->inventory_type : '') == 'led_light' ? 'selected' : '' }}>LED Light & Bulbs</option>
												</select>
												@error('inventory_type')
													<div class="invalid-feedback">{{ $message }}</div>
												@enderror
												<small class="form-text text-muted">This determines which inventory type the warehouse manager can access</small>
											</div>
										</div>

										<div class="d-flex justify-content-end gap-2">
											<a href="{{ route('warehouse-managers.index') }}" class="btn btn-secondary">Cancel</a>
											<button type="submit" class="btn btn-primary">
												<i class="align-middle me-1" data-feather="save"></i> {{ isset($user) ? 'Update' : 'Create' }}
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

