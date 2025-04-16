# System Zarządzania Zamówieniami

Ten projekt to prosta aplikacja do zarządzania zamówieniami w sklepie internetowym. Aplikacja umożliwia składanie zamówień, pobieranie szczegółów zamówienia, oraz aktualizowanie statusu zamówienia.

Aplikacja jest napisana w PHP 8.3 i Symfony 7.2 i wykorzystuje Doctrine ORM do interakcji z bazą danych.

## Funkcjonalności

1. **Złożenie nowego zamówienia (POST /orders):**
   - Klient wysyła dane zamówienia zawierające listę produktów z ich nazwami, cenami jednostkowymi oraz ilościami.
   - System oblicza łączną cenę zamówienia i zapisuje zamówienie w bazie danych.
   - Odpowiedź zawiera szczegóły zamówienia, w tym sumę całkowitą.

2. **Pobranie szczegółów zamówienia (GET /orders/{id}):**
   - Klient może pobrać szczegóły zamówienia, takie jak status, lista pozycji oraz łączna cena.

3. **Zmiana statusu zamówienia (PATCH /orders/{id}):**
   - Klient może zmienić status zamówienia (np. z “new” na “paid”).
   - Dostępne statusy zamówienia to: "new", "paid", "shipped", "cancelled".

## Technologie

- **PHP 8.3**
- **Symfony 7.2**
- **Doctrine ORM** (do interakcji z bazą danych)
- **API RESTful**
>>>>>>> master
