const express = require('express');
const mongoose = require('mongoose');
const cors = require('cors');
require('dotenv').config();

const app = express();
const PORT = process.env.PORT || 5000;

// Middleware
app.use(cors());
app.use(express.json());

// MongoDB Connection
const MONGODB_URI = process.env.MONGODB_URI || 'mongodb://mongodb:27017/razorpay_clone';
mongoose.connect(MONGODB_URI)
    .then(async () => {
        console.log('Connected to MongoDB');
        // Seed dummy data if empty
        const count = await Transaction.countDocuments();
        if (count === 0) {
            const dummyData = [
                { transactionId: 'pay_ABC123', userName: 'Aditya Verma', amount: 500, method: 'upi', status: 'success' },
                { transactionId: 'pay_DEF456', userName: 'Priya Sharma', amount: 1200, method: 'card', status: 'success' },
                { transactionId: 'pay_GHI789', userName: 'Rahul Singh', amount: 250, method: 'wallet', status: 'failure' }
            ];
            await Transaction.insertMany(dummyData);
            console.log('Dummy data seeded');
        }
    })
    .catch(err => console.error('MongoDB connection error:', err));

// Transaction Schema
const transactionSchema = new mongoose.Schema({
    transactionId: { type: String, required: true, unique: true },
    userName: { type: String, required: true },
    amount: { type: Number, required: true },
    method: { type: String, required: true },
    status: { type: String, enum: ['success', 'failure'], required: true },
    createdAt: { type: Date, default: Date.now }
});

const Transaction = mongoose.model('Transaction', transactionSchema);

// Routes
// POST /pay - Create a new transaction
app.post('/api/pay', async (req, res) => {
    try {
        const { userName, amount, method, status } = req.body;
        
        // Generate a random transaction ID like pay_XYZ123
        const transactionId = 'pay_' + Math.random().toString(36).substr(2, 9).toUpperCase();
        
        const newTransaction = new Transaction({
            transactionId,
            userName,
            amount,
            method,
            status
        });

        await newTransaction.save();
        res.status(201).json({ success: true, transaction: newTransaction });
    } catch (error) {
        console.error('Payment error:', error);
        res.status(500).json({ success: false, message: 'Internal Server Error' });
    }
});

// GET /transactions - Fetch all transactions for dashboard
app.get('/api/transactions', async (req, res) => {
    try {
        const transactions = await Transaction.find().sort({ createdAt: -1 });
        
        // Calculate stats
        const totalTransactions = transactions.length;
        const totalAmount = transactions.reduce((sum, t) => sum + t.amount, 0);
        const successCount = transactions.filter(t => t.status === 'success').length;
        const failureCount = transactions.filter(t => t.status === 'failure').length;

        res.json({
            transactions,
            stats: {
                totalTransactions,
                totalAmount,
                successCount,
                failureCount
            }
        });
    } catch (error) {
        console.error('Fetch transactions error:', error);
        res.status(500).json({ success: false, message: 'Internal Server Error' });
    }
});

app.listen(PORT, () => {
    console.log(`Backend server running on port ${PORT}`);
});
