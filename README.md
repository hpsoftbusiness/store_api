# Order Management System

This project is a simple application for managing orders in an online store. It allows users to place new orders, retrieve order details, and update the order status.

The application is built with **PHP 8.3** and **Symfony 7.2**, and it uses **Doctrine ORM** for database interactions.

## Features

### 1. Place a New Order

**Endpoint:** `POST /orders`

- The client sends order data containing a list of products with their:
   - names,
   - unit prices,
   - quantities.
- The system calculates the total price of the order and saves it in the database.
- The response includes the order details, including the total amount.

### 2. Get Order Details

**Endpoint:** `GET /orders/{id}`

- Retrieves order details such as:
   - order status,
   - list of items,
   - total price.

### 3. Update Order Status

**Endpoint:** `PATCH /orders/{id}`

- Allows updating the order status (e.g., from `"new"` to `"paid"`).
- Available statuses:
   - `new`
   - `paid`
   - `shipped`
   - `cancelled`

## Tech Stack

- **PHP 8.3**
- **Symfony 7.2**
- **Doctrine ORM**
- **RESTful API**

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/your-username/order-management.git
   cd order-management
