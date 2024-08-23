# Payments Microservice

## Overview

API endpoint that, based on a parameter passed in the URL, sends a request to one of two
(potentially more in the future) external systems and returns a unified response.
Additionally, there is the same functionality for CLI. Based on the parameter passed in the CLI
command, the server sends a request to one of two external systems and return a unified
response in the console.
The request is a regular, one-time purchase, no 3DS, server-2-server.
External systems are Shift4 and ACI.

## Requirements

- PHP 8.2
- Symfony 6.4
- Composer
- MySQL
- Docker

## Installation

### Using Docker

1. **Clone the Repository:**

```bash
   git clone https://github.com/arman424/payment-microservice.git
   cd payment-microservice
```

2. **Build and Run Docker Containers:**

```bash
  docker-compose up --build
```


## Configuration

1. **Copy the example environment file to create your local configuration file:**

    ```bash
    cp .env.example .env.local
    ```

2. **Open `.env.local` and `.env.test` and update the credentials and connection settings, specifically the `DATABASE_URL` variable.**

### Access the Application:

The application should be available at http://localhost:8081.

## Usage

### Command-Line Interface (CLI)

To execute the payment command, use:
```bash
  php bin/console MakePayment --psp --amount=100 --currency=USD --card-number=4111111111111111 --card-exp-year=2024 --card-exp-month=12 --card-cvv=123
```
### API Endpoints
Process a payment request.

```POST /api/v1/payments/{paymentType}:```

- amount (float): The amount to be charged.
- currency (string): The currency code.
- card_number (int): The credit card number.
- card_exp_year (int): The expiration year of the card.
- card_exp_month (int): The expiration month of the card.
- card_cvv (int): The CVV of the card.

### Example Request:

```bash
curl --location 'localhost:8081/api/v1/payments/aci' \
--header 'Accept: application/json' \
--form 'amount="10.12"' \
--form 'currency="USD"' \
--form 'card_number="9931231231239999"' \
--form 'card_exp_year="2025"' \
--form 'card_exp_month="12"' \
--form 'card_cvv="123"'
```

## Running Tests
Run PHPUnit Tests:
```
  php bin/phpunit
```
This will execute all tests in the `tests` directory.

### TODOs
Please consider the TODOs for implementing the `Payment Providers`, `Swagger OAs`, `Money Object` for amounts and making improvements.