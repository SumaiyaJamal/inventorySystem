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

	<title>{{ isset($inventoryRequest) ? 'Edit' : 'Create' }} Inventory Request | AdminKit Demo</title>

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
						<li class="sidebar-item active">
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
						<h1 class="h3 mb-0"><strong>{{ isset($inventoryRequest) ? 'Edit' : 'Create' }}</strong> Inventory Request</h1>
						<a href="{{ route('inventory-requests.index') }}" class="btn btn-secondary">
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
									<h5 class="card-title mb-0">Request Information</h5>
								</div>
								<div class="card-body">
									<form action="{{ isset($inventoryRequest) ? route('inventory-requests.update', $inventoryRequest) : route('inventory-requests.store') }}" method="POST" id="requestForm">
										@csrf
										@if(isset($inventoryRequest))
											@method('PUT')
										@endif

										<div class="mb-4">
											<label for="notes" class="form-label">Notes (Optional)</label>
											<textarea class="form-control @error('notes') is-invalid @enderror" 
												id="notes" name="notes" rows="3" 
												placeholder="Add any additional notes...">{{ old('notes', isset($inventoryRequest) ? $inventoryRequest->notes : '') }}</textarea>
											@error('notes')
												<div class="invalid-feedback">{{ $message }}</div>
											@enderror
										</div>

										<div class="mb-3">
											<label class="form-label">Inventory Items <span class="text-danger">*</span></label>
											<div id="inventoryItemsContainer">
												@if(isset($inventoryRequest) && $inventoryRequest->items->count() > 0)
													@foreach($inventoryRequest->items as $index => $item)
														<div class="row mb-3 inventory-item-row" data-index="{{ $index }}">
															<div class="col-md-5">
																<select class="form-select inventory-select" name="inventory_items[{{ $index }}][inventory_id]" required>
																	<option value="">Select Inventory Item</option>
																	@foreach($inventories as $inventory)
																		<option value="{{ $inventory->id }}" 
																			data-quantity="{{ $inventory->quantity }}"
																			data-type="{{ $inventory->type }}"
																			{{ old("inventory_items.{$index}.inventory_id", $item->inventory_id) == $inventory->id ? 'selected' : '' }}>
																			[{{ ucfirst($inventory->type) }}] {{ $inventory->product_name ?? $inventory->spice_name }} 
																			({{ $inventory->brand ?? $inventory->category }})
																			- Available: {{ $inventory->quantity }}
																		</option>
																	@endforeach
																</select>
															</div>
															<div class="col-md-4">
																<div class="input-group">
																	<button class="btn btn-outline-secondary" type="button" onclick="decreaseQuantity({{ $index }})">-</button>
																	<input type="number" class="form-control quantity-input" 
																		name="inventory_items[{{ $index }}][quantity]" 
																		value="{{ old("inventory_items.{$index}.quantity", $item->requested_quantity) }}" 
																		min="1" required>
																	<button class="btn btn-outline-secondary" type="button" onclick="increaseQuantity({{ $index }})">+</button>
																</div>
																<small class="text-muted">Available: <span class="available-quantity">{{ $item->inventory->quantity }}</span></small>
															</div>
															<div class="col-md-3">
																<button type="button" class="btn btn-danger w-100" onclick="removeItem({{ $index }})">
																	<i class="align-middle" data-feather="trash-2"></i> Remove
																</button>
															</div>
														</div>
													@endforeach
												@else
													<div class="row mb-3 inventory-item-row" data-index="0">
														<div class="col-md-5">
															<select class="form-select inventory-select" name="inventory_items[0][inventory_id]" required>
																<option value="">Select Inventory Item</option>
																@foreach($inventories as $inventory)
																	<option value="{{ $inventory->id }}" 
																		data-quantity="{{ $inventory->quantity }}"
																		data-type="{{ $inventory->type }}">
																		[{{ ucfirst($inventory->type) }}] {{ $inventory->product_name ?? $inventory->spice_name }} 
																		({{ $inventory->brand ?? $inventory->category }})
																		- Available: {{ $inventory->quantity }}
																	</option>
																@endforeach
															</select>
														</div>
														<div class="col-md-4">
															<div class="input-group">
																<button class="btn btn-outline-secondary" type="button" onclick="decreaseQuantity(0)">-</button>
																<input type="number" class="form-control quantity-input" 
																	name="inventory_items[0][quantity]" 
																	value="1" min="1" required>
																<button class="btn btn-outline-secondary" type="button" onclick="increaseQuantity(0)">+</button>
															</div>
															<small class="text-muted">Available: <span class="available-quantity">0</span></small>
														</div>
														<div class="col-md-3">
															<button type="button" class="btn btn-danger w-100" onclick="removeItem(0)">
																<i class="align-middle" data-feather="trash-2"></i> Remove
															</button>
														</div>
													</div>
												@endif
											</div>
											<button type="button" class="btn btn-success mt-2" onclick="addItem()">
												<i class="align-middle me-1" data-feather="plus"></i> Add Item
											</button>
										</div>

										<div class="card bg-light mb-3">
											<div class="card-body">
												<h6 class="mb-0">Total Quantity: <strong id="totalQuantity">0</strong></h6>
											</div>
										</div>

										<div class="d-flex justify-content-end gap-2">
											<a href="{{ route('inventory-requests.index') }}" class="btn btn-secondary">Cancel</a>
											<button type="submit" class="btn btn-primary">
												<i class="align-middle me-1" data-feather="save"></i> {{ isset($inventoryRequest) ? 'Update' : 'Create' }} Request
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
	<script>
		let itemIndex = {{ isset($inventoryRequest) && $inventoryRequest->items->count() > 0 ? $inventoryRequest->items->count() : 1 }};

		function addItem() {
			const container = document.getElementById('inventoryItemsContainer');
			const newRow = document.createElement('div');
			newRow.className = 'row mb-3 inventory-item-row';
			newRow.setAttribute('data-index', itemIndex);
			
			newRow.innerHTML = `
				<div class="col-md-5">
					<select class="form-select inventory-select" name="inventory_items[${itemIndex}][inventory_id]" required>
						<option value="">Select Inventory Item</option>
						@foreach($inventories as $inventory)
							<option value="{{ $inventory->id }}" 
								data-quantity="{{ $inventory->quantity }}"
								data-type="{{ $inventory->type }}">
								[{{ ucfirst($inventory->type) }}] {{ $inventory->product_name ?? $inventory->spice_name }} 
								({{ $inventory->brand ?? $inventory->category }})
								- Available: {{ $inventory->quantity }}
							</option>
						@endforeach
					</select>
				</div>
				<div class="col-md-4">
					<div class="input-group">
						<button class="btn btn-outline-secondary" type="button" onclick="decreaseQuantity(${itemIndex})">-</button>
						<input type="number" class="form-control quantity-input" 
							name="inventory_items[${itemIndex}][quantity]" 
							value="1" min="1" required>
						<button class="btn btn-outline-secondary" type="button" onclick="increaseQuantity(${itemIndex})">+</button>
					</div>
					<small class="text-muted">Available: <span class="available-quantity">0</span></small>
				</div>
				<div class="col-md-3">
					<button type="button" class="btn btn-danger w-100" onclick="removeItem(${itemIndex})">
						<i class="align-middle" data-feather="trash-2"></i> Remove
					</button>
				</div>
			`;
			
			container.appendChild(newRow);
			
			// Initialize feather icons for new row
			if (typeof feather !== 'undefined') {
				feather.replace();
			}
			
			// Add event listener for select change
			const select = newRow.querySelector('.inventory-select');
			select.addEventListener('change', function() {
				updateAvailableQuantity(this);
			});
			
			// Add event listener for quantity change
			const quantityInput = newRow.querySelector('.quantity-input');
			quantityInput.addEventListener('input', updateTotalQuantity);
			
			itemIndex++;
		}

		function removeItem(index) {
			const row = document.querySelector(`.inventory-item-row[data-index="${index}"]`);
			if (row) {
				row.remove();
				updateTotalQuantity();
				reindexItems();
			}
		}

		function reindexItems() {
			const rows = document.querySelectorAll('.inventory-item-row');
			rows.forEach((row, newIndex) => {
				row.setAttribute('data-index', newIndex);
				const select = row.querySelector('.inventory-select');
				const quantityInput = row.querySelector('.quantity-input');
				const removeBtn = row.querySelector('button[onclick*="removeItem"]');
				
				if (select) {
					select.name = `inventory_items[${newIndex}][inventory_id]`;
					select.setAttribute('onchange', 'updateAvailableQuantity(this)');
				}
				if (quantityInput) {
					quantityInput.name = `inventory_items[${newIndex}][quantity]`;
					quantityInput.setAttribute('oninput', 'updateTotalQuantity()');
				}
				if (removeBtn) {
					removeBtn.setAttribute('onclick', `removeItem(${newIndex})`);
				}
				
				const decreaseBtn = row.querySelector('button[onclick*="decreaseQuantity"]');
				const increaseBtn = row.querySelector('button[onclick*="increaseQuantity"]');
				if (decreaseBtn) decreaseBtn.setAttribute('onclick', `decreaseQuantity(${newIndex})`);
				if (increaseBtn) increaseBtn.setAttribute('onclick', `increaseQuantity(${newIndex})`);
			});
		}

		function increaseQuantity(index) {
			const row = document.querySelector(`.inventory-item-row[data-index="${index}"]`);
			const input = row.querySelector('.quantity-input');
			const max = parseInt(row.querySelector('.available-quantity').textContent);
			const current = parseInt(input.value) || 1;
			if (current < max) {
				input.value = current + 1;
				updateTotalQuantity();
			}
		}

		function decreaseQuantity(index) {
			const row = document.querySelector(`.inventory-item-row[data-index="${index}"]`);
			const input = row.querySelector('.quantity-input');
			const current = parseInt(input.value) || 1;
			if (current > 1) {
				input.value = current - 1;
				updateTotalQuantity();
			}
		}

		function updateAvailableQuantity(select) {
			const row = select.closest('.inventory-item-row');
			const availableSpan = row.querySelector('.available-quantity');
			const quantityInput = row.querySelector('.quantity-input');
			
			if (select.value) {
				const selectedOption = select.options[select.selectedIndex];
				const available = parseInt(selectedOption.getAttribute('data-quantity')) || 0;
				availableSpan.textContent = available;
				quantityInput.max = available;
				if (parseInt(quantityInput.value) > available) {
					quantityInput.value = available;
				}
			} else {
				availableSpan.textContent = '0';
				quantityInput.max = '';
			}
			updateTotalQuantity();
		}

		function updateTotalQuantity() {
			const inputs = document.querySelectorAll('.quantity-input');
			let total = 0;
			inputs.forEach(input => {
				const value = parseInt(input.value) || 0;
				total += value;
			});
			document.getElementById('totalQuantity').textContent = total;
		}

		// Initialize event listeners on page load
		document.addEventListener('DOMContentLoaded', function() {
			// Add change listeners to existing selects
			document.querySelectorAll('.inventory-select').forEach(select => {
				select.addEventListener('change', function() {
					updateAvailableQuantity(this);
				});
				// Initialize available quantity for pre-selected items
				if (select.value) {
					updateAvailableQuantity(select);
				}
			});
			
			// Add input listeners to existing quantity inputs
			document.querySelectorAll('.quantity-input').forEach(input => {
				input.addEventListener('input', updateTotalQuantity);
			});
			
			// Calculate initial total
			updateTotalQuantity();
		});
	</script>

</body>

</html>



