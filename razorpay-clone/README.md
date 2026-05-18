# RazorClone - Educational Payment Gateway Clone

A modern, full-stack payment gateway clone inspired by Razorpay. Built for educational and portfolio purposes only.

**Note:** This project does NOT process real payments or collect real banking credentials. All transactions are simulated and stored in a local database.

## Features

- 🎨 **Premium UI**: Modern blue-and-white fintech aesthetic.
- 🌓 **Dark Mode**: Support for light and dark themes.
- 📱 **Responsive**: Works on desktop and mobile.
- 💳 **Payment Methods**: UPI, Card, Net Banking, and Wallets (Simulated).
- 🖼️ **QR Code**: Fake QR code generation for UPI payments.
- 📊 **Admin Dashboard**: Track total transactions, success rates, and revenue.
- 🐳 **Dockerized**: Easy deployment using Docker Compose.

## Tech Stack

- **Frontend**: React, Vite, Framer Motion, Lucide Icons, Vanilla CSS.
- **Backend**: Node.js, Express.
- **Database**: MongoDB.
- **Containerization**: Docker, Docker Compose.

## Getting Started

### Prerequisites

- [Docker](https://www.docker.com/products/docker-desktop/) installed on your machine.

### Run the Application

1. Clone or download this project.
2. Open a terminal in the project root.
3. Run the following command:

```bash
docker compose up
```

4. Once the containers are running:
   - **Frontend**: [http://localhost:5173](http://localhost:5173)
   - **Backend API**: [http://localhost:5000](http://localhost:5000)

## Folder Structure

```
razorpay-clone/
├── backend/            # Express.js server
│   ├── Dockerfile
│   ├── server.js
│   └── package.json
├── frontend/           # React application
│   ├── src/
│   │   ├── App.jsx
│   │   └── index.css
│   ├── Dockerfile
│   └── package.json
└── docker-compose.yml  # Orchestration
```

## Disclaimer

This software is for educational purposes only. It is intended to demonstrate full-stack development, UI/UX design, and DevOps practices. Do not use this for actual financial transactions.
