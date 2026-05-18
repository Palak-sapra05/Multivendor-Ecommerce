@extends('layouts.app')

@section('content')
    <div style="max-width: 1200px; margin: 2rem auto; padding: 0 1.5rem;">
        <div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 3rem; align-items: start;">
            <!-- Checkout Form -->
            <div>
                <h1 style="color: var(--text-primary); margin-bottom: 2rem; font-weight: 800;">Checkout</h1>
                
                <div class="cart-widget" style="padding: 2.5rem; border-radius: 24px;">
                    <h3 style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                        Shipping Address
                    </h3>
                    <div style="display: grid; gap: 1.5rem;">
                        <div>
                            <label style="color:var(--text-secondary); margin-bottom:0.6rem; display:block; font-weight: 600; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">Full Address</label>
                            <input type="text" id="address" required style="width:100%; padding: 1rem; border-radius:12px; border:1px solid var(--border); background:var(--bg-base); color:var(--text-primary); outline: none; transition: border-color 0.3s;" placeholder="e.g. 42, Silicon Valley, Bangalore" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='var(--border)'">
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                            <div>
                                <label style="color:var(--text-secondary); margin-bottom:0.6rem; display:block; font-weight: 600; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">City</label>
                                <input type="text" id="city" required style="width:100%; padding: 1rem; border-radius:12px; border:1px solid var(--border); background:var(--bg-base); color:var(--text-primary); outline: none; transition: border-color 0.3s;" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='var(--border)'">
                            </div>
                            <div>
                                <label style="color:var(--text-secondary); margin-bottom:0.6rem; display:block; font-weight: 600; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">Pincode</label>
                                <input type="text" id="zip" required style="width:100%; padding: 1rem; border-radius:12px; border:1px solid var(--border); background:var(--bg-base); color:var(--text-primary); outline: none; transition: border-color 0.3s;" onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='var(--border)'">
                            </div>
                        </div>
                    </div>

                    <button type="button" onclick="startPayment()" class="btn" style="width: 100%; margin-top: 3rem; padding: 1.25rem; font-size: 1.2rem; font-weight: 800; letter-spacing: 1px; border-radius: 16px;">
                        PLACE ORDER
                    </button>
                </div>
            </div>

            <!-- Order Summary -->
            <div style="position: sticky; top: 100px;">
                <div class="cart-widget" style="background: var(--bg-surface); border-radius: 24px; padding: 2rem;">
                    <h3 style="margin-bottom: 1.5rem;">Order Summary</h3>
                    <div style="display: grid; gap: 1rem;">
                        @php $total = 0; @endphp
                        @foreach($cart as $item)
                            @php $itemTotal = $item['price'] * $item['quantity']; $total += $itemTotal; @endphp
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <div style="background: var(--bg-surface-hover); padding: 4px 8px; border-radius: 6px; font-size: 0.8rem; font-weight: 700;">{{ $item['quantity'] }}x</div>
                                    <span style="font-size: 0.95rem; color: var(--text-primary); font-weight: 500;">{{ $item['title'] }}</span>
                                </div>
                                <span style="font-weight: 700; color: var(--text-primary);">₹{{ number_format($itemTotal, 2) }}</span>
                            </div>
                        @endforeach
                        
                        <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px dashed var(--border);">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 0.75rem;">
                                <span style="color: var(--text-secondary);">Subtotal</span>
                                <span style="font-weight: 600;">₹{{ number_format($total, 2) }}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 0.75rem;">
                                <span style="color: var(--text-secondary);">Shipping</span>
                                <span style="color: var(--success); font-weight: 600;">FREE</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; font-size: 1.5rem; font-weight: 900; margin-top: 1rem; color: var(--text-primary);">
                                <span>Total</span>
                                <span>₹{{ number_format($total, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div style="margin-top: 1.5rem; padding: 1.5rem; background: rgba(5, 150, 105, 0.05); border-radius: 16px; border: 1px solid rgba(5, 150, 105, 0.1); display: flex; align-items: center; gap: 1rem;">
                    <div style="background: var(--success); color: white; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                    </div>
                    <p style="font-size: 0.85rem; color: var(--text-secondary); margin: 0; line-height: 1.4;">
                        <strong style="color: var(--success); display: block;">Secure Checkout</strong>
                        Your information is encrypted with 256-bit SSL technology.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Overlay / Modal -->
    <div id="payment-overlay" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.8); backdrop-filter: blur(10px); z-index: 9999; align-items: center; justify-content: center; padding: 2rem;">
        <div id="payment-card" style="background: var(--bg-surface); width: 100%; max-width: 500px; border-radius: 32px; overflow: hidden; box-shadow: 0 50px 100px -20px rgba(0,0,0,0.5); border: 1px solid var(--border);">
            
            <!-- Processing State -->
            <div id="payment-processing" style="padding: 4rem 2rem; text-align: center;">
                <div class="loader" style="width: 80px; height: 80px; border: 6px solid var(--bg-base); border-top: 6px solid var(--primary); border-radius: 50%; margin: 0 auto 2rem; animation: spin 1s linear infinite;"></div>
                <h2 style="font-weight: 800; margin-bottom: 0.5rem;">Placing Order</h2>
                <p style="color: var(--text-secondary);">Please do not refresh or close this window...</p>
            </div>

            <!-- Success State -->
            <div id="payment-success" style="display: none; padding: 4rem 2rem; text-align: center;">
                <div style="background: var(--success); color: white; width: 100px; height: 100px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 2rem; box-shadow: 0 20px 40px rgba(16, 185, 129, 0.3); animation: scaleIn 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>
                </div>
                <h1 style="font-weight: 900; font-size: 2.5rem; margin-bottom: 0.5rem; color: var(--text-primary);">Order Placed!</h1>
                <p style="color: var(--text-secondary); font-size: 1.1rem; margin-bottom: 2.5rem;">Your order has been placed successfully. You can pay for it in the orders section.</p>
                
                <div style="background: var(--bg-base); padding: 1.5rem; border-radius: 20px; text-align: left; margin-bottom: 2.5rem; border: 1px solid var(--border);">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.75rem;">
                        <span style="color: var(--text-secondary); font-size: 0.9rem;">Order ID</span>
                        <span id="txn-id" style="font-family: monospace; font-weight: 700; color: var(--text-primary);">NXM-8923-4521-0092</span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: var(--text-secondary); font-size: 0.9rem;">Total Amount</span>
                        <span style="font-weight: 800; color: var(--primary);">₹{{ number_format($total, 2) }}</span>
                    </div>
                </div>

                <a href="/orders" class="btn" style="width: 100%; padding: 1.25rem; border-radius: 16px; font-weight: 700; text-decoration: none;">VIEW MY ORDERS</a>
            </div>
        </div>
    </div>

    <style>
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        @keyframes scaleIn { 0% { transform: scale(0); opacity: 0; } 100% { transform: scale(1); opacity: 1; } }
    </style>

    <script>

        async function startPayment() {
            // Check if address is filled
            const address = document.getElementById('address').value;
            const city = document.getElementById('city').value;
            const zip = document.getElementById('zip').value;

            if(!address || !city || !zip) {
                alert('Please fill in all shipping details');
                if(!address) document.getElementById('address').focus();
                else if(!city) document.getElementById('city').focus();
                else document.getElementById('zip').focus();
                return;
            }

            const overlay = document.getElementById('payment-overlay');
            const processing = document.getElementById('payment-processing');
            const success = document.getElementById('payment-success');
            const txnId = document.getElementById('txn-id');

            overlay.style.display = 'flex';

            try {
                // Submit order to backend in background
                const response = await fetch('{{ route("checkout.place") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        address: address,
                        city: city,
                        zip: zip
                    })
                });

                const result = await response.json();

                if(result.success) {
                    // Show real order id
                    txnId.innerText = 'ORD-' + result.order_id;

                    // Artificial delay for premium feel
                    setTimeout(() => {
                        processing.style.display = 'none';
                        success.style.display = 'block';
                    }, 2000);
                } else {
                    overlay.style.display = 'none';
                    alert('Error placing order: ' + (result.error || 'Unknown error'));
                }
            } catch (error) {
                overlay.style.display = 'none';
                alert('Failed to connect to server. Please try again.');
            }
        }
    </script>
@endsection

