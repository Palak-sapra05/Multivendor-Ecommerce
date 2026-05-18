import React, { useState, useEffect } from 'react';
import { 
  CreditCard, 
  Smartphone, 
  Globe, 
  Wallet, 
  Moon, 
  Sun, 
  CheckCircle2, 
  XCircle, 
  LayoutDashboard, 
  ArrowLeft,
  QrCode,
  ShieldCheck
} from 'lucide-react';
import { motion, AnimatePresence } from 'framer-motion';
import axios from 'axios';

const API_BASE_URL = 'http://localhost:5000/api';

const App = () => {
  const [isDark, setIsDark] = useState(false);
  const [view, setView] = useState('payment'); // 'payment', 'processing', 'result', 'dashboard'
  const [paymentData, setPaymentData] = useState(() => {
    const params = new URLSearchParams(window.location.search);
    return {
      userName: params.get('name') || '',
      amount: params.get('amount') || '',
      method: 'upi',
      orderId: params.get('order_id') || ''
    };
  });
  const [result, setResult] = useState(null);
  const [transactions, setTransactions] = useState([]);
  const [stats, setStats] = useState({});

  useEffect(() => {
    if (isDark) {
      document.body.classList.add('dark');
    } else {
      document.body.classList.remove('dark');
    }
  }, [isDark]);

  const handlePayment = async (e) => {
    e.preventDefault();
    if (!paymentData.userName || !paymentData.amount) return;

    setView('processing');
    
    // Simulate processing time
    setTimeout(async () => {
      const isSuccess = Math.random() > 0.1; // 90% success rate
      try {
        const response = await axios.post(`${API_BASE_URL}/pay`, {
          ...paymentData,
          status: isSuccess ? 'success' : 'failure'
        });
        setResult(response.data.transaction);
        setView('result');
      } catch (error) {
        console.error('Payment failed', error);
        setResult({ status: 'failure', message: 'Connection Error' });
        setView('result');
      }
    }, 2500);
  };

  const fetchDashboard = async () => {
    try {
      const response = await axios.get(`${API_BASE_URL}/transactions`);
      setTransactions(response.data.transactions);
      setStats(response.data.stats);
      setView('dashboard');
    } catch (error) {
      console.error('Fetch error', error);
    }
  };

  const reset = () => {
    if (result && result.status === 'success') {
      window.location.href = `http://127.0.0.1:8000/orders`; // Redirect back to orders index
    } else {
      setView('payment');
      setPaymentData({ userName: '', amount: '', method: 'upi' });
      setResult(null);
    }
  };

  const formatCurrency = (amount) => {
    return new Intl.NumberFormat('en-IN', {
      style: 'currency',
      currency: 'INR',
    }).format(amount);
  };

  return (
    <div className="app-container">
      {/* Navbar */}
      <nav className="navbar">
        <div className="logo" onClick={reset} style={{cursor: 'pointer'}}>
          <div style={{background: 'var(--primary)', padding: '5px', borderRadius: '8px', color: 'white'}}>
            <ShieldCheck size={24} />
          </div>
          <span>RazorClone</span>
        </div>
        <div style={{display: 'flex', gap: '1rem'}}>
          <button className="btn btn-outline" onClick={() => setIsDark(!isDark)}>
            {isDark ? <Sun size={20} /> : <Moon size={20} />}
          </button>
          <button className="btn btn-primary" onClick={fetchDashboard}>
            <LayoutDashboard size={20} style={{marginRight: '8px'}} />
            Dashboard
          </button>
        </div>
      </nav>

      <AnimatePresence mode="wait">
        {view === 'payment' && (
          <motion.div 
            key="payment"
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            exit={{ opacity: 0, y: -20 }}
            className="card"
            style={{ maxWidth: '500px', margin: '0 auto' }}
          >
            <h2 style={{ marginBottom: '1.5rem' }}>Secure Payment</h2>
            
            <div style={{ background: 'rgba(51, 149, 255, 0.05)', padding: '1rem', borderRadius: '12px', marginBottom: '2rem', border: '1px dashed var(--primary)' }}>
              <div style={{fontSize: '0.8rem', color: '#64748b', marginBottom: '0.25rem'}}>ORDER SUMMARY</div>
              <div style={{fontSize: '1.1rem', fontWeight: '700'}}>Services / Subscription</div>
            </div>

            <form onSubmit={handlePayment}>
              <div className="form-group">
                <label>Billing Name</label>
                <input 
                  type="text" 
                  placeholder="e.g. John Doe"
                  value={paymentData.userName}
                  onChange={(e) => setPaymentData({...paymentData, userName: e.target.value})}
                  required
                />
              </div>
              <div className="form-group">
                <label>Amount (INR)</label>
                <input 
                  type="number" 
                  placeholder="0.00"
                  value={paymentData.amount}
                  onChange={(e) => setPaymentData({...paymentData, amount: e.target.value})}
                  required
                />
              </div>

              <div style={{ marginBottom: '0.5rem', fontWeight: 500, fontSize: '0.9rem', color: '#64748b' }}>Select Payment Method</div>
              <div className="method-grid">
                {[
                  { id: 'upi', label: 'UPI', icon: <Smartphone size={20} /> },
                  { id: 'card', label: 'Card', icon: <CreditCard size={20} /> },
                  { id: 'netbanking', label: 'Net Banking', icon: <Globe size={20} /> },
                  { id: 'wallet', label: 'Wallet', icon: <Wallet size={20} /> },
                ].map(method => (
                  <div 
                    key={method.id}
                    className={`method-card ${paymentData.method === method.id ? 'active' : ''}`}
                    onClick={() => setPaymentData({...paymentData, method: method.id})}
                  >
                    {method.icon}
                    <span>{method.label}</span>
                  </div>
                ))}
              </div>

              {paymentData.method === 'upi' && (
                <div className="qr-container" style={{ marginBottom: '1.5rem' }}>
                  <div className="qr-code">
                    <QrCode size={160} color="#000" />
                  </div>
                  <p style={{fontSize: '0.8rem', color: '#64748b'}}>Scan QR to pay with any UPI App</p>
                </div>
              )}

              <button type="submit" className="btn btn-primary" style={{ width: '100%', height: '50px' }}>
                Pay {paymentData.amount ? formatCurrency(paymentData.amount) : 'Now'}
              </button>
            </form>
          </motion.div>
        )}

        {view === 'processing' && (
          <motion.div 
            key="processing"
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            className="card"
            style={{ maxWidth: '400px', margin: '100px auto', textAlign: 'center' }}
          >
            <div className="spinner" style={{ margin: '0 auto 2rem' }}></div>
            <h3>Processing Payment</h3>
            <p style={{ color: '#64748b', marginTop: '1rem' }}>Please do not close this window or press back button.</p>
          </motion.div>
        )}

        {view === 'result' && (
          <motion.div 
            key="result"
            initial={{ scale: 0.9, opacity: 0 }}
            animate={{ scale: 1, opacity: 1 }}
            className="card"
            style={{ maxWidth: '500px', margin: '0 auto', textAlign: 'center' }}
          >
            <div className={`status-icon ${result.status}`}>
              {result.status === 'success' ? <CheckCircle2 size={48} /> : <XCircle size={48} />}
            </div>
            
            <h2 style={{ marginBottom: '0.5rem' }}>
              Payment {result.status === 'success' ? 'Successful' : 'Failed'}
            </h2>
            <p style={{ color: '#64748b', marginBottom: '2rem' }}>
              {result.status === 'success' 
                ? 'Your transaction was completed successfully.' 
                : 'There was an error processing your payment.'}
            </p>

            <div style={{ textAlign: 'left', background: 'rgba(51, 149, 255, 0.05)', padding: '1.5rem', borderRadius: '12px', marginBottom: '2rem' }}>
              <div style={{ display: 'flex', justifyContent: 'space-between', marginBottom: '0.75rem' }}>
                <span style={{ color: '#64748b' }}>Transaction ID</span>
                <span style={{ fontWeight: 600 }}>{result.transactionId || 'N/A'}</span>
              </div>
              <div style={{ display: 'flex', justifyContent: 'space-between', marginBottom: '0.75rem' }}>
                <span style={{ color: '#64748b' }}>Amount</span>
                <span style={{ fontWeight: 600 }}>{formatCurrency(result.amount)}</span>
              </div>
              <div style={{ display: 'flex', justifyContent: 'space-between' }}>
                <span style={{ color: '#64748b' }}>Date</span>
                <span style={{ fontWeight: 600 }}>{new Date().toLocaleDateString()}</span>
              </div>
            </div>

            <button className="btn btn-primary" onClick={reset} style={{ width: '100%' }}>
              Back to Merchant
            </button>
          </motion.div>
        )}

        {view === 'dashboard' && (
          <motion.div 
            key="dashboard"
            initial={{ opacity: 0, x: 20 }}
            animate={{ opacity: 1, x: 0 }}
            className="dashboard-view"
          >
            <div style={{ display: 'flex', alignItems: 'center', gap: '1rem', marginBottom: '2rem' }}>
              <button className="btn btn-outline" onClick={() => setView('payment')}>
                <ArrowLeft size={20} />
              </button>
              <h1>Admin Dashboard</h1>
            </div>

            <div className="stats-grid">
              <div className="stat-card">
                <div style={{color: '#64748b', fontSize: '0.9rem'}}>Total Transactions</div>
                <div style={{fontSize: '1.5rem', fontWeight: 700}}>{stats.totalTransactions}</div>
              </div>
              <div className="stat-card">
                <div style={{color: '#64748b', fontSize: '0.9rem'}}>Total Revenue</div>
                <div style={{fontSize: '1.5rem', fontWeight: 700, color: 'var(--primary)'}}>{formatCurrency(stats.totalAmount)}</div>
              </div>
              <div className="stat-card">
                <div style={{color: '#64748b', fontSize: '0.9rem'}}>Successful</div>
                <div style={{fontSize: '1.5rem', fontWeight: 700, color: 'var(--success)'}}>{stats.successCount}</div>
              </div>
              <div className="stat-card">
                <div style={{color: '#64748b', fontSize: '0.9rem'}}>Failed</div>
                <div style={{fontSize: '1.5rem', fontWeight: 700, color: 'var(--error)'}}>{stats.failureCount}</div>
              </div>
            </div>

            <div className="card">
              <h3 style={{ marginBottom: '1.5rem' }}>Recent History</h3>
              <div style={{ overflowX: 'auto' }}>
                <table style={{ width: '100%', borderCollapse: 'collapse' }}>
                  <thead>
                    <tr style={{ textAlign: 'left', borderBottom: '1px solid var(--border-light)' }}>
                      <th style={{ padding: '1rem 0', color: '#64748b' }}>User</th>
                      <th style={{ padding: '1rem 0', color: '#64748b' }}>Method</th>
                      <th style={{ padding: '1rem 0', color: '#64748b' }}>Amount</th>
                      <th style={{ padding: '1rem 0', color: '#64748b' }}>Status</th>
                      <th style={{ padding: '1rem 0', color: '#64748b' }}>Date</th>
                    </tr>
                  </thead>
                  <tbody>
                    {transactions.map((t) => (
                      <tr key={t.transactionId} style={{ borderBottom: '1px solid var(--border-light)', fontSize: '0.95rem' }}>
                        <td style={{ padding: '1rem 0' }}>{t.userName}</td>
                        <td style={{ padding: '1rem 0', textTransform: 'uppercase' }}>{t.method}</td>
                        <td style={{ padding: '1rem 0', fontWeight: 600 }}>{formatCurrency(t.amount)}</td>
                        <td style={{ padding: '1rem 0' }}>
                          <span style={{ 
                            padding: '4px 8px', 
                            borderRadius: '4px', 
                            fontSize: '0.75rem',
                            fontWeight: 700,
                            textTransform: 'uppercase',
                            background: t.status === 'success' ? 'rgba(16, 185, 129, 0.1)' : 'rgba(239, 68, 68, 0.1)',
                            color: t.status === 'success' ? 'var(--success)' : 'var(--error)'
                          }}>
                            {t.status}
                          </span>
                        </td>
                        <td style={{ padding: '1rem 0', color: '#64748b' }}>{new Date(t.createdAt).toLocaleDateString()}</td>
                      </tr>
                    ))}
                  </tbody>
                </table>
              </div>
            </div>
          </motion.div>
        )}
      </AnimatePresence>
    </div>
  );
};

export default App;
