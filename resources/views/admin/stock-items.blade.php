@extends('layouts.app')

@section('title', 'Stock Items')

@section('content')
<div class="row">
    <!-- Add Stock Item Form -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-plus me-2"></i>Add Stock Item</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.stock-items.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Item Name</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}" placeholder="e.g. Office Chair" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Category</label>
                        <input type="text" name="category" class="form-control"
                            value="{{ old('category') }}" placeholder="e.g. Furniture, Electronics">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Unit</label>
                        <select name="unit" class="form-select" required>
                            <option value="piece"  {{ old('unit') == 'piece'  ? 'selected' : '' }}>Piece</option>
                            <option value="box"    {{ old('unit') == 'box'    ? 'selected' : '' }}>Box</option>
                            <option value="kg"     {{ old('unit') == 'kg'     ? 'selected' : '' }}>KG</option>
                            <option value="liter"  {{ old('unit') == 'liter'  ? 'selected' : '' }}>Liter</option>
                            <option value="ream"   {{ old('unit') == 'ream'   ? 'selected' : '' }}>Ream</option>
                            <option value="set"    {{ old('unit') == 'set'    ? 'selected' : '' }}>Set</option>
                            <option value="pair"   {{ old('unit') == 'pair'   ? 'selected' : '' }}>Pair</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Unit Price ($)</label>
                        <input type="number" name="unit_price" step="0.01" min="0"
                            class="form-control @error('unit_price') is-invalid @enderror"
                            value="{{ old('unit_price') }}" placeholder="0.00" required>
                        @error('unit_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Quantity Available</label>
                        <input type="number" name="quantity_available" min="0"
                            class="form-control @error('quantity_available') is-invalid @enderror"
                            value="{{ old('quantity_available') }}" placeholder="0" required>
                        @error('quantity_available')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Description</label>
                        <textarea name="description" class="form-control" rows="2"
                            placeholder="Optional description">{{ old('description') }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-plus me-2"></i> Add Item
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Stock Items List -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-boxes me-2"></i>All Stock Items ({{ count($stockItems) }})</h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Item Name</th>
                            <th>Category</th>
                            <th>Unit Price</th>
                            <th>Qty Available</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stockItems as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <strong>{{ $item->name }}</strong><br>
                                <small class="text-muted">{{ $item->unit }}</small>
                            </td>
                            <td>{{ $item->category ?? 'N/A' }}</td>
                            <td><strong>${{ number_format($item->unit_price, 2) }}</strong></td>
                            <td>
                                <span class="badge bg-{{ $item->quantity_available > 0 ? 'success' : 'danger' }}">
                                    {{ $item->quantity_available }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $item->is_available ? 'success' : 'secondary' }}">
                                    {{ $item->is_available ? 'Available' : 'Unavailable' }}
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                    data-bs-target="#editModal{{ $item->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form method="POST" action="{{ route('admin.stock-items.destroy', $item) }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this item?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Stock Item</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form method="POST" action="{{ route('admin.stock-items.update', $item) }}">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Item Name</label>
                                                <input type="text" name="name" class="form-control" value="{{ $item->name }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Category</label>
                                                <input type="text" name="category" class="form-control" value="{{ $item->category }}">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Unit</label>
                                                <select name="unit" class="form-select">
                                                    @foreach(['piece','box','kg','liter','ream','set','pair'] as $u)
                                                        <option value="{{ $u }}" {{ $item->unit == $u ? 'selected' : '' }}>{{ ucfirst($u) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Unit Price ($)</label>
                                                <input type="number" name="unit_price" step="0.01" min="0" class="form-control" value="{{ $item->unit_price }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Quantity Available</label>
                                                <input type="number" name="quantity_available" min="0" class="form-control" value="{{ $item->quantity_available }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Description</label>
                                                <textarea name="description" class="form-control" rows="2">{{ $item->description }}</textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr><td colspan="7" class="text-center py-3">No stock items yet. Add your first item!</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection