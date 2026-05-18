@extends('layouts.app')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h2>Global Operations Command</h2>
        <div class="badge badge-success">Admin Access</div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div style="color:var(--text-secondary)">Total System Revenue</div>
            <div class="stat-value">₹{{ number_format($totalCommission, 2) }}</div>
        </div>
        <div class="stat-card">
            <div style="color:var(--text-secondary)">Active Stores</div>
            <div class="stat-value">{{ $totalStores }}</div>
        </div>
        <div class="stat-card">
            <div style="color:var(--text-secondary)">Total Orders</div>
            <div class="stat-value">{{ $totalOrders }}</div>
        </div>
    </div>

    <h3>Pending Store Applications</h3>
    <div class="table-container" style="margin-top: 1rem; margin-bottom: 3rem;">
        <table>
            <thead>
                <tr>
                    <th>Store Name</th>
                    <th>Seller</th>
                    <th>Description</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendingStores as $s)
                <tr>
                    <td><strong>{{ $s->name }}</strong></td>
                    <td>{{ $s->user->name }} ({{ $s->user->email }})</td>
                    <td><small>{{ Str::limit($s->description, 50) }}</small></td>
                    <td>{{ $s->created_at->format('M d, Y') }}</td>
                    <td style="display: flex; gap: 0.5rem;">
                        <form method="POST" action="{{ route('admin.stores.approve', $s->id) }}">
                            @csrf
                            <button type="submit" class="btn" style="padding: 0.2rem 0.5rem; font-size: 0.75rem; background: var(--success); border-color: var(--success); color: white;">Approve</button>
                        </form>
                        <form method="POST" action="{{ route('admin.stores.reject', $s->id) }}">
                            @csrf
                            <button type="submit" class="btn" style="padding: 0.2rem 0.5rem; font-size: 0.75rem; background: var(--danger); border-color: var(--danger); color: white;">Reject</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align:center">No pending applications.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <h3>All Platform Sub-Orders</h3>
    <div class="table-container" style="margin-top: 1rem;">
        <table>
            <thead>
                <tr>
                    <th>SubOrder ID</th>
                    <th>Store</th>
                    <th>Gross</th>
                    <th>Platform Cut</th>
                    <th>Payout Sent</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($allSubOrders as $o)
                <tr>
                    <td>#{{ $o->id }}</td>
                    <td>{{ $o->store->name }}</td>
                    <td>₹{{ number_format($o->total_amount, 2) }}</td>
                    <td style="color:var(--success); font-weight:bold;">+₹{{ number_format($o->commission_amount, 2) }}</td>
                    <td>₹{{ number_format($o->seller_earning, 2) }}</td>
                    <td><span class="badge">{{ ucfirst($o->status) }}</span></td>
                </tr>
                @endforeach
                @if($allSubOrders->isEmpty())
                <tr>
                    <td colspan="6" style="text-align:center">No orders yet.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
@endsection
