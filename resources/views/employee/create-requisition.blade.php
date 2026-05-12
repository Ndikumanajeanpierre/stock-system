@extends('layouts.app')

@section('title', 'New Requisition')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Submit New Requisition</h5>
        <a href="{{ route('employee.requisitions') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('employee.requisitions.store') }}" id="requisitionForm">
            @csrf
            <div class="row g-3">

                {{-- Stock selector --}}
                <div class="col-md-12">
                    <label class="form-label fw-bold">Select from Stock (optional)</label>
                    <select id="stockSelect" class="form-select" onchange="fillFromStock(this)">
                        <option value="">-- Pick an item from stock or type manually below --</option>
                        @foreach(\App\Models\StockItem::available()->get() as $item)
                            <option
                                value="{{ $item->name }}"
                                data-price="{{ $item->unit_price }}"
                                data-unit="{{ $item->unit }}"
                                data-available="{{ $item->quantity_available }}">
                                {{ $item->name }} — RWF {{ number_format($item->unit_price, 2) }} per {{ $item->unit }}
                                ({{ $item->quantity_available }} available)
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted">Selecting an item will auto-fill and lock the fields below.</small>
                </div>

                {{-- Availability info bar --}}
                <div class="col-md-12" id="stockInfoBar" style="display:none;">
                    <div class="alert alert-info d-flex align-items-center gap-2 py-2 mb-0">
                        <i class="fas fa-boxes"></i>
                        <span>Available in stock: <strong id="stockAvailableCount">0</strong> unit(s)</span>
                    </div>
                </div>

                {{-- Over-limit JS alert --}}
                <div class="col-md-12" id="stockAlertWrapper" style="display:none;">
                    <div class="alert alert-danger d-flex align-items-center gap-2 py-2 mb-0" role="alert">
                        <i class="fas fa-exclamation-triangle fa-lg"></i>
                        <span id="stockAlertMsg"></span>
                    </div>
                </div>

                {{-- Server-side quantity error (from controller) --}}
                @error('quantity')
                    <div class="col-md-12">
                        <div class="alert alert-danger py-2">
                            <i class="fas fa-exclamation-triangle me-2"></i>{{ $message }}
                        </div>
                    </div>
                @enderror

                {{-- Item name --}}
                <div class="col-md-12">
                    <label class="form-label fw-bold">Item Name</label>
                    <input type="text" name="item_name" id="item_name"
                        class="form-control @error('item_name') is-invalid @enderror"
                        value="{{ old('item_name') }}"
                        placeholder="Enter item name" required>
                    @error('item_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Quantity --}}
                <div class="col-md-4">
                    <label class="form-label fw-bold">Quantity</label>
                    <input type="number" name="quantity" id="quantityInput" min="1"
                        class="form-control @error('quantity') is-invalid @enderror"
                        value="{{ old('quantity', 1) }}"
                        oninput="checkStockLimit()"
                        required>
                </div>

                {{-- Unit --}}
                <div class="col-md-4">
                    <label class="form-label fw-bold">Unit</label>
                    <input type="hidden" name="unit" id="unit_hidden" value="{{ old('unit') }}">
                    <select id="unit_select" class="form-select @error('unit') is-invalid @enderror"
                        onchange="document.getElementById('unit_hidden').value = this.value">
                        <option value="">Select Unit</option>
                        <option value="piece"  {{ old('unit') == 'piece'  ? 'selected' : '' }}>Piece</option>
                        <option value="box"    {{ old('unit') == 'box'    ? 'selected' : '' }}>Box</option>
                        <option value="kg"     {{ old('unit') == 'kg'     ? 'selected' : '' }}>KG</option>
                        <option value="liter"  {{ old('unit') == 'liter'  ? 'selected' : '' }}>Liter</option>
                        <option value="ream"   {{ old('unit') == 'ream'   ? 'selected' : '' }}>Ream</option>
                        <option value="set"    {{ old('unit') == 'set'    ? 'selected' : '' }}>Set</option>
                        <option value="pair"   {{ old('unit') == 'pair'   ? 'selected' : '' }}>Pair</option>
                    </select>
                    @error('unit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Estimated cost --}}
                <div class="col-md-4">
                    <label class="form-label fw-bold">Estimated Cost (optional)</label>
                    <input type="number" name="estimated_cost" id="estimated_cost" min="0" step="0.01"
                        class="form-control @error('estimated_cost') is-invalid @enderror"
                        value="{{ old('estimated_cost') }}" placeholder="0.00">
                    @error('estimated_cost')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Department --}}
                <div class="col-md-6">
                    <label class="form-label fw-bold">Department</label>
                    <select name="department_id" class="form-select @error('department_id') is-invalid @enderror" required>
                        <option value="">Select Department</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('department_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Priority --}}
                <div class="col-md-6">
                    <label class="form-label fw-bold">Priority</label>
                    <select name="priority" class="form-select @error('priority') is-invalid @enderror" required>
                        <option value="">Select Priority</option>
                        <option value="low"    {{ old('priority') == 'low'    ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high"   {{ old('priority') == 'high'   ? 'selected' : '' }}>High</option>
                        <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                    </select>
                    @error('priority')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Description --}}
                <div class="col-md-12">
                    <label class="form-label fw-bold">Description (optional)</label>
                    <textarea name="description" class="form-control" rows="3"
                        placeholder="Describe why you need this item...">{{ old('description') }}</textarea>
                </div>

                {{-- Submit --}}
                <div class="col-md-12 mt-2">
                    <button type="submit" class="btn btn-primary px-5" id="submitBtn">
                        <i class="fas fa-paper-plane me-2"></i> Submit Requisition
                    </button>
                    <a href="{{ route('employee.requisitions') }}" class="btn btn-secondary ms-2">
                        Cancel
                    </a>
                </div>

            </div>
        </form>
    </div>
</div>

<script>
    var maxAvailable  = 0;
    var stockSelected = false;

    function fillFromStock(select) {
        var option = select.options[select.selectedIndex];

        if (option.value) {
            var available = parseInt(option.getAttribute('data-available')) || 0;
            maxAvailable  = available;
            stockSelected = true;

            document.getElementById('item_name').value      = option.value;
            document.getElementById('estimated_cost').value = option.getAttribute('data-price');
            document.getElementById('unit_select').value    = option.getAttribute('data-unit');
            document.getElementById('unit_hidden').value    = option.getAttribute('data-unit');

            document.getElementById('item_name').setAttribute('readonly', true);
            document.getElementById('estimated_cost').setAttribute('readonly', true);
            document.getElementById('unit_select').setAttribute('disabled', true);
            document.getElementById('item_name').style.backgroundColor      = '#e9ecef';
            document.getElementById('estimated_cost').style.backgroundColor = '#e9ecef';
            document.getElementById('unit_select').style.backgroundColor    = '#e9ecef';

            document.getElementById('stockAvailableCount').textContent = available;
            document.getElementById('stockInfoBar').style.display      = '';

            checkStockLimit();

        } else {
            maxAvailable  = 0;
            stockSelected = false;

            document.getElementById('item_name').value      = '';
            document.getElementById('estimated_cost').value = '';
            document.getElementById('unit_select').value    = '';
            document.getElementById('unit_hidden').value    = '';

            document.getElementById('item_name').removeAttribute('readonly');
            document.getElementById('estimated_cost').removeAttribute('readonly');
            document.getElementById('unit_select').removeAttribute('disabled');
            document.getElementById('item_name').style.backgroundColor      = '';
            document.getElementById('estimated_cost').style.backgroundColor = '';
            document.getElementById('unit_select').style.backgroundColor    = '';

            document.getElementById('stockInfoBar').style.display      = 'none';
            document.getElementById('stockAlertWrapper').style.display = 'none';
            document.getElementById('submitBtn').disabled = false;
            document.getElementById('quantityInput').classList.remove('is-invalid');
        }
    }

    function checkStockLimit() {
        if (!stockSelected) return;

        var requested = parseInt(document.getElementById('quantityInput').value) || 0;
        var alertWrap = document.getElementById('stockAlertWrapper');
        var alertMsg  = document.getElementById('stockAlertMsg');
        var submitBtn = document.getElementById('submitBtn');
        var qtyInput  = document.getElementById('quantityInput');

        if (requested > maxAvailable) {
            alertMsg.innerHTML =
                '<strong>Insufficient stock!</strong> You requested <strong>' + requested +
                '</strong> but only <strong>' + maxAvailable +
                '</strong> unit(s) are available. Please reduce your quantity.';
            alertWrap.style.display = '';
            qtyInput.classList.add('is-invalid');
            submitBtn.disabled = true;
        } else {
            alertWrap.style.display = 'none';
            qtyInput.classList.remove('is-invalid');
            submitBtn.disabled = false;
        }
    }

    // Hard block on submit
    document.getElementById('requisitionForm').addEventListener('submit', function (e) {
        if (stockSelected) {
            var requested = parseInt(document.getElementById('quantityInput').value) || 0;
            if (requested > maxAvailable) {
                e.preventDefault();
                checkStockLimit();
                document.getElementById('stockAlertWrapper').scrollIntoView({ behavior: 'smooth', block: 'center' });
                return false;
            }
        }
    });

    window.onload = function () {
        var urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('item')) {
            document.getElementById('item_name').value = urlParams.get('item');
            document.getElementById('item_name').setAttribute('readonly', true);
            document.getElementById('item_name').style.backgroundColor = '#e9ecef';
        }
        if (urlParams.get('price')) {
            document.getElementById('estimated_cost').value = urlParams.get('price');
            document.getElementById('estimated_cost').setAttribute('readonly', true);
            document.getElementById('estimated_cost').style.backgroundColor = '#e9ecef';
        }
        if (urlParams.get('unit')) {
            document.getElementById('unit_select').value = urlParams.get('unit');
            document.getElementById('unit_hidden').value = urlParams.get('unit');
            document.getElementById('unit_select').setAttribute('disabled', true);
            document.getElementById('unit_select').style.backgroundColor = '#e9ecef';
        }
    }
</script>
@endsection