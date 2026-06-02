# Tasklist Reloaded

A task management web app built with Symfony, FrankenPHP, and Docker.

## ✨ Features

- **Authentication:** Register and log in with a secure session.
- **Tasks:** Create, complete, pin, archive, and delete tasks.
- **Folders:** Organize tasks into custom color-coded folders.
- **Priorities:** Define personal priority levels (urgent, important, normal…) and filter tasks by them.
- **Filters:** Filter tasks by folder, priority, and status (pending / completed / archived).
- **Live UI:** Instant updates powered by Turbo Drive and Stimulus — no full page reloads.

## 🛠️ Tech Stack

- **Backend:** PHP 8.4, Symfony 8.0.
- **Frontend:** Tailwind CSS, Font Awesome, Stimulus, Turbo (Symfony UX).
- **Database:** SQLite.
- **Server:** FrankenPHP (Caddy-based, all-in-one PHP runtime).
- **Containerization:** Docker + Docker Compose.

## ⚙️ Prerequisites

- Docker and Docker Compose.

## 📦 Installation

### 1. Clone the repository

```bash
git clone https://github.com/LuzRivera154/phase3-symfony-tasklist-reloaded.git
cd phase3-symfony-tasklist-reloaded
```

### 2. Build and start

```bash
docker compose up --build -d
```

This starts two containers:

- **php** — Symfony app served by FrankenPHP on port 8080
- **mailer** — Mailpit (local mail catcher) on port 8025

### 3. Run database migrations

```bash
docker compose exec php php bin/console doctrine:migrations:migrate --no-interaction
```

### 4. Build Tailwind CSS

```bash
docker compose exec php php bin/console tailwind:build
```

## ▶️ Open the app

```
http://localhost:8080
```

## ⏹️ Stop the app

```bash
docker compose down
```

## 🗑️ Remove containers and volumes

```bash
docker compose down -v
```
