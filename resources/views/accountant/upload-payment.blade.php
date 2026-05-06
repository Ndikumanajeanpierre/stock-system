@extends('layouts.app')

@section('title', 'Upload Payment')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Upload Payment - {{ $requisition->reference_number }}</h5>
        <a href="{{ route('accountant.requisitions') }}" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
    <div class="card-body">
        <div class="row">
            <!-- Requisition Info -->
            <div class="col-md-5">
                <div class="alert alert-info">
                    <h6>Requisition Details</h6>
                    <hr>
                    <p><strong>Reference:</strong> {{ $requisition->reference_number }}</p>
                    <p><strong>Employee:</strong> {{ $requisition->user->name }}</p>
                    <p><strong>Item:</strong> {{ $requisition->item_name }}</p>
                    <p><strong>Quantity:</strong> {{ $requisition->quantity }} {{ $requisition->unit }}</p>
                    <p><strong>Estimated Cost:</strong> {{ $requisition->estimated_cost ? '$'.$requisition->estimated_cost : 'N/A' }}</p>
                    <p class="mb-0"><strong>Status:</strong>
                        <span class="badge bg-{{ $requisition->getStatusBadgeClass() }}">
                            {{ ucfirst($requisition->status) }}
                        </span>
                    </p>
                </div>
            </div>

            <!-- Payment Form -->
            <div class="col-md-7">
                <form method="POST"
                    action="{{ route('accountant.requisitions.payment.store', $requisition) }}"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Amount Paid</label>
                            <input type="number" name="amount" step="0.01" min="0"
                                class="form-control @error('amount') is-invalid @enderror"
                                value="{{ old('amount', $requisition->estimated_cost) }}" required>
                            @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Payment Method</label>
                            <select name="payment_method" class="form-select @error('payment_method') is-invalid @enderror" required>
                                <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                <option value="cash"          {{ old('payment_method') == 'cash'          ? 'selected' : '' }}>Cash</option>
                                <option value="cheque"        {{ old('payment_method') == 'cheque'        ? 'selected' : '' }}>Cheque</option>
                                <option value="mobile_money"  {{ old('payment_method') == 'mobile_money'  ? 'selected' : '' }}>Mobile Money</option>
                            </select>
                            @error('payment_method')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Payment Date</label>
                            <input type="date" name="payment_date"
                                class="form-control @error('payment_date') is-invalid @enderror"
                                value="{{ old('payment_date', now()->format('Y-m-d')) }}" required>
                            @error('payment_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Transaction Reference</label>
                            <input type="text" name="transaction_reference"
                                class="form-control"
                                value="{{ old('transaction_reference') }}"
                                placeholder="Optional">
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Upload Receipt</label>
                            <input type="file" name="receipt"
                                class="form-control @error('receipt') is-invalid @enderror"
                                accept=".pdf,.jpg,.jpeg,.png" required>
                            <small class="text-muted">Accepted: PDF, JPG, PNG (max 2MB)</small>
                            @error('receipt')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Notes (optional)</label>
                            <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-upload"></i> Upload Payment
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection