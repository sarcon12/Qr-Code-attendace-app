# QR Attendance System 📸

A modern, high-performance, and fully responsive QR Code Attendance System. Built with web technologies, this system allows students to seamlessly register, generate their unique QR codes, and have their attendance scanned and managed in real-time.

## ✨ Features
* **Triple-Tier Architecture**: Automatically adapts to its hosting environment (Firebase Cloud, Local PHP Server, or Offline LocalStorage).
* **Instant QR Generation**: Students get an auto-generated QR code upon registration.
* **Smart Compression**: Built-in client-side image compression shrinks profile pictures before upload, ensuring lightning-fast registrations even on slow networks.
* **Admin Dashboard**: Real-time attendance monitoring, statistical overviews, and CSV export capabilities.
* **Glassmorphism UI**: A stunning, modern, and premium user interface with dynamic animations.

## 🚀 How to Host & Deploy

This project is built to run anywhere. Depending on your needs, choose one of the deployment methods below:

### 1. Global Cloud Deployment (GitHub Pages + Firebase) 🌍
If you want to host this system on GitHub Pages so students can access it from their own phones anywhere in the world, you must connect it to a free Firebase database.

1. Go to the [Firebase Console](https://console.firebase.google.com/) and create a free project.
2. Navigate to **Build > Realtime Database** and click **Create Database**.
3. Start the database in **Test Mode** (or update your rules to allow read/write).
4. Go to **Project Settings** (gear icon) and add a "Web App".
5. Copy your unique `firebaseConfig` object.
6. Open `firebase-init.js` in this repository and replace the `"YOUR_API_KEY"` placeholders with your actual keys.
7. Upload the repository to GitHub and enable **GitHub Pages**.

### 🔐 Default Admin Credentials
For the first-time setup, use these credentials to access the Admin Portal:
- **Username:** `admin`
- **Password:** `admincute12345`

*The system will automatically detect your keys and switch to Live Cloud Mode!*

### 2. Local Network Deployment (XAMPP / PHP) 🏫
If you do not want to use Firebase, you can run the entire system on a local server. This is perfect for a classroom environment where all students connect to the school's Wi-Fi.

1. Install [XAMPP](https://www.apachefriends.org/).
2. Place this entire folder inside the `C:\xampp\htdocs\` directory.
3. Start the **Apache** module in XAMPP.
4. Open your computer's Command Prompt and type `ipconfig` to find your IPv4 Address (e.g., `192.168.1.10`).
5. Tell students to visit `http://192.168.1.10/YOUR_FOLDER_NAME/` on their phones. 

*The system will automatically detect the PHP server and use `database.json` to sync all devices!*

### 3. Standalone Prototype Mode 💻
If you simply open `index.html` directly in your browser without a server, or if you host it on GitHub Pages *without* Firebase keys, the system will seamlessly fall back to **Local Storage Mode**. It will still function perfectly, but data will only be saved on your specific device.

## 🛠️ Built With
* **HTML5 / Vanilla JS**
* **CSS3** (Custom Glassmorphism UI)
* **Firebase** (Cloud Realtime Database)
* **PHP** (Local Server Fallback)
* **PWA** (Service Workers for offline support)

---
*Created as a comprehensive portfolio project demonstrating cloud-integration, intelligent offline fallbacks, and premium UI/UX design.*
