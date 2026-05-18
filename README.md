# 🚀 MDTrace: Hybrid Web2.5 Supply Chain Traceability Platform

![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Solidity](https://img.shields.io/badge/Solidity-363636?style=for-the-badge&logo=solidity&logoColor=white)
![Ethereum](https://img.shields.io/badge/Ethereum-3C3C3D?style=for-the-badge&logo=ethereum&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)

## 📌 Project Overview

I am thrilled to share my Capstone Project - **MDTrace**, a production-ready supply chain traceability platform designed to bridge the gap between traditional enterprise software (Web2) and the trustless nature of Blockchain (Web3).

Faced with the real-world challenge of product counterfeiting and data tampering in supply chains, I architected MDTrace to ensure data integrity while maintaining a seamless user experience for traditional businesses.

> ⚠️ **Disclaimer:** Please note that all enterprise, product, and batch information displayed in this project and demo are purely fictional and generated solely for demonstration purposes. No real corporate or supply chain data is involved.

---

## 💡 Key Architectural & Technical Highlights

1️⃣ **Hybrid Architecture (Web2.5):** Leveraged MySQL for high-speed, sub-second data retrieval (QR code scanning, product catalogs) while using Blockchain as an immutable "Notary Layer" to guarantee data integrity.

2️⃣ **Server-Side Transaction Signing (Custodial Wallet):** Eliminated the UX barrier of MetaMask for business users. The PHP backend (Laravel) securely fetches the administrative private key from protected environment variables, hashes the batch data via SHA-256, and signs transactions offline before broadcasting.

3️⃣ **Infrastructure via Alchemy RPC:** Utilized Alchemy as the node provider to broadcast signed raw transactions directly to the Ethereum Sepolia Testnet, retrieving a unique Transaction Hash stored as cryptographic proof.

4️⃣ **Tamper Detection & Integrity Verification:** Built a dynamic cryptographic verification mechanism. When a user scans the GS1-compliant QR code, the system re-hashes the live MySQL data and compares it with the original hash fetched from the Smart Contract. Any unauthorized database modification instantly triggers a critical warning interface.

5️⃣ **Automated e-KYC Integration:** Integrated the VietQR Business API into the Admin Dashboard, allowing real-time validation of corporate tax codes against national databases before account approval.

6️⃣ **Automated Background Notification:** Implemented Laravel Mail services wrapped inside try-catch blocks to handle background asynchronous notifications (Approval, Rejection, Account Suspension) flawlessly.

---

## 🛠 Tech Stack

* **Backend:** Laravel (PHP 8.x), OOP, MVC Architecture, SaaS Model
* **Smart Contract:** Solidity (Compiled & tested via Remix IDE)
* **Blockchain Infrastructure:** Ethereum Sepolia, Alchemy RPC Provider, Web3p/EthereumTx
* **Frontend & UI:** Tailwind CSS, Vanilla JavaScript (Asynchronous Fetch API), Alpine.js, UX/UI Design Thinking, User-Centered Design (UCD)
* **Security:** Environmental Variable Masking, Strict Form Validation, Automated Fail-safes

---

## ⚙️ Installation & Configuration Guide

Follow these steps to set up the project on your local machine.

### 1. Prerequisites
* PHP 8.2 or higher
* Composer
* MySQL
* Node.js & npm

### 2. Clone the Repository
```bash
git clone [https://github.com/your-username/mdtrace.git](https://github.com/your-username/mdtrace.git)
cd mdtrace
3. Install Dependencies
composer install
npm install
npm run build

4. Environment Setup
Copy the example environment file and generate the application key:

cp .env.example .env
php artisan key:generate
Configure your database connection in the .env file:

Code snippet
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mdtrace_db
DB_USERNAME=root
DB_PASSWORD=
5. Third-Party Services Configuration (IMPORTANT)
To make the system fully functional, you need to configure the following services in your .env file:

A. Automated Email Setup (Gmail SMTP)
To enable the background notification system (Approval/Rejection emails):

Go to your Google Account -> Security.

Enable 2-Step Verification.

Search for App Passwords and generate a new password (e.g., name it "Laravel App").

Update your .env file with the generated 16-character password (without spaces):

Code snippet
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=465
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your_16_character_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="your-email@gmail.com"
MAIL_FROM_NAME="${APP_NAME}"
B. Blockchain Setup (Alchemy API & Wallet)
To enable the Web3 Server-Side Signing feature on the Sepolia Testnet:

Alchemy API: Go to Alchemy.com, create an account, and create a new App on the Ethereum Sepolia network. Copy the API Key or the full HTTPS RPC URL.

Custodial Wallet: Install MetaMask, switch to the Sepolia Testnet, and get some test ETH from a Sepolia Faucet. Export the Private Key of this wallet. (⚠️ Warning: NEVER use a wallet containing real funds for development).

Update your .env file:

Code snippet
ALCHEMY_API_KEY=your_alchemy_api_key_here
# OR
WEB3_RPC_URL=[https://eth-sepolia.g.alchemy.com/v2/your_alchemy_api_key_here](https://eth-sepolia.g.alchemy.com/v2/your_alchemy_api_key_here)

WALLET_PRIVATE_KEY=your_metamask_private_key_here
WALLET_ADDRESS=your_metamask_public_address_here
6. Database Migration & Seeding
php artisan migrate --seed

7. Run the Application
php artisan serve
Visit http://localhost:8000 in your browser.

🤝 Let's Connect!
This journey has drastically sharpened my skills in Full-stack Development, UX/UI Design Thinking, API Integration, Cryptographic Security, and Web3 Infrastructure Architecture. I’m incredibly grateful to my advisors and peers for their continuous support throughout this development phase.

I am currently open to new opportunities in UX / Web Development and Full-Stack Engineering.

📩 Feel free to reach out to me via LinkedIn or email work.manhdung@gmail.com.
