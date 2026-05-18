@extends('layouts.app')

@section('content')
<div style="max-width: 900px; margin: 3rem auto; padding: 0 1.5rem;">
    <div style="text-align: center; margin-bottom: 2.5rem;">
        <h1 style="font-weight: 900; color: var(--text-primary); font-size: 2rem;">Complete Payment</h1>
        <p style="color: var(--text-secondary); margin-top: 0.5rem;">Order #{{ $order->id }} &bull; {{ \App\Helpers\CurrencyHelper::format($order->total_amount) }}</p>
    </div>

    <div style="display: grid; grid-template-columns: 1.4fr 1fr; gap: 2.5rem; align-items: start;">
        <!-- Payment Methods -->
        <div class="cart-widget" style="padding: 2.5rem; border-radius: 24px;">
            <h3 style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                Select Payment Method
            </h3>

            <div style="display: grid; gap: 0.75rem;">
                <!-- Card -->
                <label class="pay-method" onclick="selectMethod(this)" style="display: flex; align-items: center; justify-content: space-between; padding: 1.25rem; border: 2px solid var(--primary); border-radius: 16px; cursor: pointer; transition: all 0.2s; background: rgba(69,69,69,0.03);">
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <input type="radio" name="method" value="card" checked style="accent-color: var(--primary); transform: scale(1.3);">
                        <div>
                            <span style="font-weight: 700; color: var(--text-primary); display: block;">Credit / Debit Card</span>
                            <span style="font-size: 0.8rem; color: var(--text-secondary);">Visa, Mastercard, RuPay</span>
                        </div>
                    </div>
                    <div style="display: flex; gap: 6px; align-items: center;">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg" height="14" alt="Visa">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" height="18" alt="Mastercard">
                    </div>
                </label>

                <!-- UPI -->
                <label class="pay-method" onclick="selectMethod(this)" style="display: flex; align-items: center; justify-content: space-between; padding: 1.25rem; border: 1px solid var(--border); border-radius: 16px; cursor: pointer; transition: all 0.2s;">
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <input type="radio" name="method" value="upi" style="accent-color: var(--primary); transform: scale(1.3);">
                        <div>
                            <span style="font-weight: 700; color: var(--text-primary); display: block;">UPI</span>
                            <span style="font-size: 0.8rem; color: var(--text-secondary);">Google Pay, PhonePe, Paytm</span>
                        </div>
                    </div>
                    <img src="https://upload.wikimedia.org/wikipedia/commons/e/e1/UPI-Logo-vector.svg" height="16" alt="UPI">
                </label>

                <!-- Net Banking -->
                <label class="pay-method" onclick="selectMethod(this)" style="display: flex; align-items: center; justify-content: space-between; padding: 1.25rem; border: 1px solid var(--border); border-radius: 16px; cursor: pointer; transition: all 0.2s;">
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <input type="radio" name="method" value="netbanking" style="accent-color: var(--primary); transform: scale(1.3);">
                        <div>
                            <span style="font-weight: 700; color: var(--text-primary); display: block;">Net Banking</span>
                            <span style="font-size: 0.8rem; color: var(--text-secondary);">All major banks supported</span>
                        </div>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--text-secondary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18"/><path d="M3 10h18"/><path d="M5 6l7-3 7 3"/><path d="M4 10v11"/><path d="M20 10v11"/><path d="M8 14v3"/><path d="M12 14v3"/><path d="M16 14v3"/></svg>
                </label>

                <!-- Wallets -->
                <label class="pay-method" onclick="selectMethod(this)" style="display: flex; align-items: center; justify-content: space-between; padding: 1.25rem; border: 1px solid var(--border); border-radius: 16px; cursor: pointer; transition: all 0.2s;">
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <input type="radio" name="method" value="wallet" style="accent-color: var(--primary); transform: scale(1.3);">
                        <div>
                            <span style="font-weight: 700; color: var(--text-primary); display: block;">Wallets</span>
                            <span style="font-size: 0.8rem; color: var(--text-secondary);">Paytm, Amazon Pay, Mobikwik</span>
                        </div>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--text-secondary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12V7H5a2 2 0 0 1 0-4h14v4"/><path d="M3 5v14a2 2 0 0 0 2 2h16v-5"/><path d="M18 12a2 2 0 0 0 0 4h4v-4Z"/></svg>
                </label>
            </div>

            <!-- Pay Button -->
            <button onclick="processPayment()" class="btn" style="width: 100%; margin-top: 2rem; padding: 1.25rem; font-size: 1.15rem; font-weight: 800; letter-spacing: 1px; border-radius: 16px;">
                PAY {{ \App\Helpers\CurrencyHelper::format($order->total_amount) }}
            </button>

            <div style="display: flex; align-items: center; justify-content: center; gap: 0.75rem; margin-top: 1.5rem; color: var(--text-secondary); font-size: 0.85rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--success)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                Secured with 256-bit SSL Encryption
            </div>
        </div>

        <!-- Order Summary -->
        <div style="position: sticky; top: 100px;">
            <div class="cart-widget" style="border-radius: 24px; padding: 2rem;">
                <h3 style="margin-bottom: 1.25rem;">Order Summary</h3>
                @foreach($order->subOrders as $subOrder)
                    <div style="margin-bottom: 1rem;">
                        <span style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.5px;">{{ $subOrder->store->name }}</span>
                        @foreach($subOrder->items as $item)
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 0.5rem;">
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    <span style="background: var(--bg-surface-hover); padding: 2px 8px; border-radius: 6px; font-size: 0.75rem; font-weight: 700;">{{ $item->quantity }}x</span>
                                    <span style="font-size: 0.9rem; color: var(--text-primary);">{{ $item->product->name }}</span>
                                </div>
                                <span style="font-weight: 700; font-size: 0.9rem;">{{ \App\Helpers\CurrencyHelper::format($item->price * $item->quantity) }}</span>
                            </div>
                        @endforeach
                    </div>
                @endforeach

                <div style="border-top: 1px dashed var(--border); margin-top: 1rem; padding-top: 1rem;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                        <span style="color: var(--text-secondary);">Subtotal</span>
                        <span style="font-weight: 600;">{{ \App\Helpers\CurrencyHelper::format($order->total_amount) }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                        <span style="color: var(--text-secondary);">Shipping</span>
                        <span style="color: var(--success); font-weight: 600;">FREE</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; font-size: 1.4rem; font-weight: 900; margin-top: 0.75rem; padding-top: 0.75rem; border-top: 1px solid var(--border); color: var(--text-primary);">
                        <span>Total</span>
                        <span>{{ \App\Helpers\CurrencyHelper::format($order->total_amount) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Payment Processing Overlay -->
<div id="pay-overlay" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.85); backdrop-filter: blur(12px); z-index: 9999; align-items: center; justify-content: center; padding: 2rem;">
    <div style="background: var(--bg-surface); width: 100%; max-width: 480px; border-radius: 32px; box-shadow: 0 50px 100px rgba(0,0,0,0.5); border: 1px solid var(--border); overflow: hidden;">
        
        <!-- Processing -->
        <div id="pay-processing" style="padding: 4rem 2rem; text-align: center;">
            <div style="width: 80px; height: 80px; border: 6px solid var(--bg-base); border-top: 6px solid var(--primary); border-radius: 50%; margin: 0 auto 2rem; animation: spin 1s linear infinite;"></div>
            <h2 style="font-weight: 800; margin-bottom: 0.5rem; color: var(--text-primary);">Processing Payment</h2>
            <p style="color: var(--text-secondary);">Please do not refresh or close this window...</p>
        </div>

        <!-- Success -->
        <div id="pay-success" style="display: none; padding: 4rem 2rem; text-align: center;">
            <div style="background: var(--success); color: white; width: 100px; height: 100px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 2rem; box-shadow: 0 20px 40px rgba(16, 185, 129, 0.3); animation: scaleIn 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;">
                <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>
            </div>
            <h1 style="font-weight: 900; font-size: 2.25rem; margin-bottom: 0.5rem; color: var(--text-primary);">Payment Successful!</h1>
            <p style="color: var(--text-secondary); font-size: 1.05rem; margin-bottom: 2rem;">Order #{{ $order->id }} is now being processed.</p>

            <div style="background: var(--bg-base); padding: 1.25rem; border-radius: 20px; text-align: left; margin-bottom: 2rem; border: 1px solid var(--border);">
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.6rem;">
                    <span style="color: var(--text-secondary); font-size: 0.85rem;">Transaction ID</span>
                    <span id="pay-txn" style="font-family: monospace; font-weight: 700; color: var(--text-primary);">—</span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: var(--text-secondary); font-size: 0.85rem;">Amount Paid</span>
                    <span style="font-weight: 800; color: var(--primary);">{{ \App\Helpers\CurrencyHelper::format($order->total_amount) }}</span>
                </div>
            </div>

            <a href="{{ route('orders.index') }}" class="btn" style="width: 100%; padding: 1.15rem; border-radius: 16px; font-weight: 700; text-decoration: none; display: block;">VIEW MY ORDERS</a>
        </div>
    </div>
</div>

<style>
    .pay-method:hover { border-color: var(--primary) !important; transform: translateY(-2px); }
    @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    @keyframes scaleIn { 0% { transform: scale(0); opacity: 0; } 100% { transform: scale(1); opacity: 1; } }
</style>

<script>
    function selectMethod(el) {
        document.querySelectorAll('.pay-method').forEach(m => {
            m.style.borderColor = 'var(--border)';
            m.style.borderWidth = '1px';
            m.style.background = 'transparent';
        });
        el.style.borderColor = 'var(--primary)';
        el.style.borderWidth = '2px';
        el.style.background = 'rgba(69,69,69,0.03)';
    }

    async function processPayment() {
        const overlay = document.getElementById('pay-overlay');
        const processing = document.getElementById('pay-processing');
        const success = document.getElementById('pay-success');

        overlay.style.display = 'flex';

        try {
            const response = await fetch('{{ route("payment.process", $order->id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({})
            });

            const result = await response.json();

            if (result.success) {
                const txnId = 'TXN-' + Math.floor(1000 + Math.random() * 9000) + '-' + Math.floor(1000 + Math.random() * 9000);
                document.getElementById('pay-txn').innerText = txnId;

                setTimeout(() => {
                    processing.style.display = 'none';
                    success.style.display = 'block';
                }, 2500);
            } else {
                overlay.style.display = 'none';
                alert('Payment failed: ' + (result.error || 'Unknown error'));
            }
        } catch (err) {
            overlay.style.display = 'none';
            alert('Connection error. Please try again.');
        }
    }
</script>
@endsection
