# 💸 Micro Wallet — Async Transactions

> A learning project exploring asynchronous money transfers with a queue-based worker — a Lumen/PHP API with correct money handling (integer cents), a test suite, and RabbitMQ in the stack.

## 📋 Overview

I built this to learn message queues and asynchronous transaction processing. A transfer is
**accepted immediately** (`202 Accepted`, status `pending`) and is meant to be settled later
by a queue worker — the intake half is done and tested; the settlement worker is in progress.

## 🚦 Status

- **Done:** transfer intake (`202` + `pending`), money stored as integer cents, transfer
  lookup, request validation, and a PHPUnit suite covering the flow.
- **In progress:** wiring the RabbitMQ producer/consumer to settle transfers
  (`pending → completed`) with idempotency and balance checks.

## 🚀 Tech Stack

| Layer | Technology |
|-------|-----------|
| **Language / Framework** | PHP 8.1 · Laravel Lumen |
| **Database** | MySQL |
| **Messaging** | RabbitMQ |
| **Testing** | PHPUnit |
| **Runtime** | Docker / Docker Compose |

## 🧠 Technical Decisions

- **Money as integer cents.** Amounts are stored as integers (`amount * 100`), never floats
  — avoiding rounding errors in financial values.
- **Accept-now, settle-later intake.** `POST /transfers` returns `202 Accepted` with a
  `pending` status, modelling asynchronous processing rather than settling inline.
- **RabbitMQ as the broker.** Provisioned in the stack as the queue backing the settlement
  worker.

## 🧪 Testing

```bash
./vendor/bin/phpunit
```

The suite covers the happy path (`202`), field validation (`422`), persistence (cents), and
retrieval (`404` when missing).

## 🔧 How to Run Locally

```bash
cp .env.example .env
docker compose up -d          # PHP app + MySQL + RabbitMQ
php artisan migrate
```

## 🗺 Roadmap

- Publish transfers to RabbitMQ on intake and consume them in a settlement worker.
- Idempotent settlement (`pending → completed`) with sender/receiver balance updates.
- Replace the boilerplate with the above end-to-end async flow.

## 👤 Author

**João Barbosa** — Software Engineer (backend / platform).
[LinkedIn](https://www.linkedin.com/in/joao1barbosa/) · [GitHub](https://github.com/joao1barbosa)
