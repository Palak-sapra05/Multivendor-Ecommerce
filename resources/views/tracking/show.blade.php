@extends('layouts.app')

@section('content')
<div class="tracking-container">
    <div class="tracking-card">
        <div class="tracking-header">
            <div class="info">
                <h1>Track Order #{{ $subOrder->order_id }}</h1>
                <p>Carrier: <strong>{{ $subOrder->carrier ?? 'NexMart Express' }}</strong> | Tracking: <strong>{{ $subOrder->tracking_number }}</strong></p>
            </div>
            <div class="status-badge {{ $subOrder->status }}">
                {{ ucfirst($subOrder->status) }}
            </div>
        </div>

        <div class="tracking-body">
            <div class="map-section">
                <div id="map" style="height: 400px; border-radius: 12px; border: 1px solid var(--border);"></div>
            </div>

            <div class="details-section">
                <div class="eta-card">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    <div class="eta-info">
                        <span>Estimated Delivery</span>
                        <h3>{{ $subOrder->estimated_delivery_at ? $subOrder->estimated_delivery_at->format('D, M d, Y') : 'Processing' }}</h3>
                    </div>
                </div>

                <div class="location-card">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                    <div class="loc-info">
                        <span>Current Location</span>
                        <h3>{{ is_array($subOrder->current_location) ? $subOrder->current_location['address'] : 'Sorting Facility' }}</h3>
                    </div>
                </div>

                <div class="timeline">
                    <div class="timeline-item completed">
                        <div class="dot"></div>
                        <div class="content">
                            <h4>Order Placed</h4>
                            <p>{{ $subOrder->created_at->format('M d, Y - H:i') }}</p>
                        </div>
                    </div>
                    <div class="timeline-item {{ $subOrder->status != 'pending' ? 'completed' : 'active' }}">
                        <div class="dot"></div>
                        <div class="content">
                            <h4>Shipped</h4>
                            <p>Handed over to {{ $subOrder->carrier ?? 'NexMart Express' }}</p>
                        </div>
                    </div>
                    <div class="timeline-item {{ $subOrder->status == 'delivered' ? 'completed' : ($subOrder->status == 'shipped' ? 'active' : '') }}">
                        <div class="dot"></div>
                        <div class="content">
                            <h4>In Transit</h4>
                            <p>Arrived at sorting facility</p>
                        </div>
                    </div>
                    <div class="timeline-item {{ $subOrder->status == 'delivered' ? 'active' : '' }}">
                        <div class="dot"></div>
                        <div class="content">
                            <h4>Delivered</h4>
                            <p>Package reached destination</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    .tracking-container {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 0 1rem;
    }

    .tracking-card {
        background: var(--bg-surface);
        border-radius: 20px;
        border: 1px solid var(--border);
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }

    .tracking-header {
        padding: 2rem;
        border-bottom: 1px solid var(--border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: var(--bg-body);
    }

    .tracking-header h1 {
        margin: 0;
        font-size: 1.5rem;
        color: var(--text-primary);
    }

    .tracking-header p {
        margin: 0.5rem 0 0;
        color: var(--text-secondary);
        font-size: 0.9rem;
    }

    .status-badge {
        padding: 0.5rem 1.5rem;
        border-radius: 30px;
        font-weight: 700;
        font-size: 0.9rem;
        text-transform: uppercase;
    }

    .status-badge.pending { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    .status-badge.shipped { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    .status-badge.delivered { background: rgba(16, 185, 129, 0.1); color: #10b981; }

    .tracking-body {
        display: grid;
        grid-template-columns: 1.5fr 1fr;
        gap: 2rem;
        padding: 2rem;
    }

    @media (max-width: 992px) {
        .tracking-body { grid-template-columns: 1fr; }
    }

    .details-section {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .eta-card, .location-card {
        background: var(--bg-body);
        padding: 1.5rem;
        border-radius: 16px;
        display: flex;
        align-items: center;
        gap: 1.5rem;
        border: 1px solid var(--border);
    }

    .eta-card svg, .location-card svg {
        color: var(--primary);
        width: 32px;
        height: 32px;
    }

    .eta-info span, .loc-info span {
        font-size: 0.8rem;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .eta-info h3, .loc-info h3 {
        margin: 0.2rem 0 0;
        font-size: 1.2rem;
        color: var(--text-primary);
    }

    .timeline {
        padding-left: 1rem;
        position: relative;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 14px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: var(--border);
    }

    .timeline-item {
        position: relative;
        padding-left: 3rem;
        padding-bottom: 2rem;
    }

    .timeline-item .dot {
        position: absolute;
        left: 10px;
        top: 5px;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: var(--border);
        z-index: 2;
    }

    .timeline-item.completed .dot {
        background: #10b981;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.2);
    }

    .timeline-item.active .dot {
        background: var(--primary);
        box-shadow: 0 0 0 4px rgba(69, 69, 69, 0.2);
    }

    .timeline-item h4 {
        margin: 0;
        font-size: 1rem;
        color: var(--text-primary);
    }

    .timeline-item p {
        margin: 0.3rem 0 0;
        font-size: 0.85rem;
        color: var(--text-secondary);
    }
</style>

@endsection

@section('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    const lat = {{ is_array($subOrder->current_location) ? $subOrder->current_location['lat'] : 28.6139 }};
    const lng = {{ is_array($subOrder->current_location) ? $subOrder->current_location['lng'] : 77.2090 }};
    
    var map = L.map('map').setView([lat, lng], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    var deliveryIcon = L.divIcon({
        className: 'custom-div-icon',
        html: `<div style="background-color:var(--primary); width:20px; height:20px; border-radius:50%; border:3px solid white; box-shadow:0 0 10px rgba(0,0,0,0.3)"></div>`,
        iconSize: [20, 20],
        iconAnchor: [10, 10]
    });

    L.marker([lat, lng], {icon: deliveryIcon}).addTo(map)
        .bindPopup('{{ is_array($subOrder->current_location) ? $subOrder->current_location["address"] : "Your Package" }}')
        .openPopup();

    // Simulate movement
    setInterval(() => {
        const newLat = lat + (Math.random() - 0.5) * 0.001;
        const newLng = lng + (Math.random() - 0.5) * 0.001;
        // In a real app, you would fetch this from an API
    }, 5000);
</script>
@endsection
