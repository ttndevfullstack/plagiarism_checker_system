# 🧠 Plagiarism Checker System

## 📄 Overview
This project is a **plagiarism checker system** built using **Flask** and **Docker**. It provides APIs and interfaces to upload, process, and detect plagiarism in text-based documents using embedding comparison and vector database search (Milvus).

---

## 🚀 Installation

### 1. Clone the Repository
```bash
git clone https://github.com/your-repo/plagiarism_checker_system.git
cd plagiarism_checker_system
```

### 2. Launch with Docker
```bash
docker compose up -d --build
```

This will build and start all required services including:
- Flask backend
- Laravel admin/user frontend
- MySQL database
- Milvus vector DB
- phpMyAdmin
- Attu (Milvus UI)

---

## 🌐 Accessing the Application

| 🧭 Interface        | 🔗 URL                                   | 📋 Description               |
|--------------------|------------------------------------------|------------------------------|
| 🔐 Admin Panel      | [http://localhost:8000/admin/login](http://localhost:8000/admin/login) | Admin login interface        |
| 👤 User Login       | [http://localhost:8000/user/login](http://localhost:8000/user/login)   | General user login           |
| 🛠️ phpMyAdmin       | [http://localhost:8383](http://localhost:8383)                         | MySQL database management    |
| 📊 Milvus Console   | [http://localhost:3000](http://localhost:3000)                         | Milvus vector DB web UI (Attu) |

---

## 📄 License
This project is licensed under the **MIT License**. See the [LICENSE](LICENSE) file for details.