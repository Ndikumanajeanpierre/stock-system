
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
        <form method="POST" action="{{ route('employee.requisitions.store') }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-12 mb-2">
                    <label class="form-label fw-bold">Item Name</label>
                    <input type="text" name="item_name"
                        class="form-control @error('item_name') is-invalid @enderror"
                        value="{{ old('item_name') }}"
                        placeholder="Enter item name" required>
                    @error('item_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">Quantity</label>
                    <input type="number" name="quantity" min="1"
                        class="form-control @error('quantity') is-invalid @enderror"
                        value="{{ old('quantity', 1) }}" required>
                    @error('quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">Unit</label>
                    <select name="unit" class="form-select @error('unit') is-invalid @enderror" required>
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

                <div class="col-md-4">
                    <label class="form-label fw-bold">Estimated Cost (optional)</label>
                    <input type="number" name="estimated_cost" min="0" step="0.01"
                        class="form-control @error('estimated_cost') is-invalid @enderror"
                        value="{{ old('estimated_cost') }}"
                        placeholder="0.00">
                    @error('estimated_cost')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

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

                <div class="col-md-6">
                    <label class="form-label fw-bold">Priority</label>
                    <select name="priority" class="form-select @error('priority') is-invalid @enderror" required>
                        <option value="">Select Priority</option>
                        <option value="low"    {{ old('priority') == 'low'    ? 'selected' : '' }}>🟢 Low</option>
                        <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>🔵 Medium</option>
                        <option value="high"   {{ old('priority') == 'high'   ? 'selected' : '' }}>🟠 High</option>
                        <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>🔴 Urgent</option>
                    </select>
                    @error('priority')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-12">
                    <label class="form-label fw-bold">Description (optional)</label>
                    <textarea name="description" class="form-control" rows="3"
                        placeholder="Describe why you need this item...">{{ old('description') }}</textarea>
                </div>

                <div class="col-md-12 mt-2">
                    <button type="submit" class="btn btn-primary px-5">
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
@endsection