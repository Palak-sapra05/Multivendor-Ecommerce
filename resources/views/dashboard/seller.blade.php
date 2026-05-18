@extends('layouts.app')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h2>Seller Dashboard</h2>
        <div style="display: flex; gap: 1rem; align-items: center;">
            <a href="{{ route('seller.products.create') }}" class="btn" style="padding: 0.5rem 1rem; background: var(--primary); color: white; border-radius: 8px; text-decoration: none;">+ Add Product</a>
            <div class="badge badge-success">Store ID: {{ $store->id }} | {{ $store->name }}</div>
        </div>
    </div>

    @if(!$store->is_approved)
        <div style="background: rgba(245, 158, 11, 0.1); border: 1px solid #f59e0b; color: #b45309; padding: 1.5rem; border-radius: 12px; margin-bottom: 2rem; display: flex; align-items: center; gap: 1rem;">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
            <div>
                <strong style="display: block; font-size: 1.1rem;">Store Approval Pending</strong>
                <span>Your store is currently under review. You can add products but they won't be visible to customers until approved.</span>
            </div>
        </div>
    @endif

    <div class="stats-grid">
        <div class="stat-card">
            <div style="color:var(--text-secondary)">Total Earnings</div>
            <div class="stat-value">₹{{ number_format($earnings, 2) }}</div>
        </div>
        <div class="stat-card">
            <div style="color:var(--text-secondary)">Orders Pending</div>
            <div class="stat-value">{{ $orders->where('status', 'pending')->count() }}</div>
        </div>
        <div class="stat-card">
            <div style="color:var(--text-secondary)">Products Active</div>
            <div class="stat-value">{{ $store->products()->count() }}</div>
        </div>
    </div>

    <h3>Recent Orders</h3>
    <div class="table-container" style="margin-top: 1rem;">
        <table>
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Date</th>
                    <th>Subtotal</th>
                    <th>Platform Fee</th>
                    <th>Net Earning</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $o)
                <tr>
                    <td>#{{ $o->id }} (Master: {{ $o->order_id }})</td>
                    <td>{{ $o->created_at->format('M d, Y') }}</td>
                    <td>₹{{ number_format($o->total_amount, 2) }}</td>
                    <td style="color:var(--danger)">-₹{{ number_format($o->commission_amount, 2) }}</td>
                    <td style="color:var(--success); font-weight:bold;">₹{{ number_format($o->seller_earning, 2) }}</td>
                    <td><span class="badge">{{ ucfirst($o->status) }}</span></td>
                    <td>
                        @if($o->status === 'pending')
                            <form method="POST" action="{{ route('seller.orders.status', $o->id) }}" style="margin:0;">
                                @csrf
                                <input type="hidden" name="status" value="shipped">
                                <button type="submit" class="btn" style="padding: 0.2rem 0.5rem; font-size: 0.75rem;">Mark Shipped</button>
                            </form>
                        @elseif($o->status === 'shipped')
                            <form method="POST" action="{{ route('seller.orders.status', $o->id) }}" style="margin:0;">
                                @csrf
                                <input type="hidden" name="status" value="delivered">
                                <button type="submit" class="btn" style="padding: 0.2rem 0.5rem; font-size: 0.75rem; background: var(--success); border-color: var(--success); color: white;">Mark Delivered</button>
                            </form>
                        @endif
                    </td>
                </tr>
                @endforeach
                @if($orders->isEmpty())
                <tr>
                    <td colspan="6" style="text-align:center">No orders yet.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 3rem;">
        <h3>My Products</h3>
        <a href="{{ route('seller.products.create') }}" style="color: var(--primary); font-weight: 600; text-decoration: none;">View All Products →</a>
    </div>
    <div class="table-container" style="margin-top: 1rem;">
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($store->products as $p)
                <tr>
                    <td>
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <img src="{{ !empty($p->images) ? $p->images[0] : 'https://via.placeholder.com/40' }}" style="width: 40px; height: 40px; border-radius: 4px; object-fit: cover;">
                            <span>{{ $p->name }}</span>
                        </div>
                    </td>
                    <td>{{ $p->category->name ?? 'N/A' }}</td>
                    <td>{{ \App\Helpers\CurrencyHelper::format($p->price) }}</td>
                    <td>{{ $p->stock }}</td>
                    <td><span class="badge {{ $p->is_active ? 'badge-success' : 'badge-danger' }}">{{ $p->is_active ? 'Active' : 'Inactive' }}</span></td>
                    <td style="display: flex; gap: 0.5rem;">
                        <a href="{{ route('seller.products.edit', $p->id) }}" class="btn" style="padding: 0.2rem 0.5rem; font-size: 0.75rem;">Edit</a>
                        <form method="POST" action="{{ route('seller.products.destroy', $p->id) }}" onsubmit="return confirm('Delete this product?')" style="margin:0;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn" style="padding: 0.2rem 0.5rem; font-size: 0.75rem; background: var(--danger); border-color: var(--danger); color: white;">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
