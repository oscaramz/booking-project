# Booking System API — PHP Backend

## Description du projet

Je n'avais pas de projet à présenter pour l'entretien, alors j’ai décidé de créer un petit système de réservation de salles.

Ce projet est une API backend développée en PHP pur, sans framework, suivant une architecture clean et modulaire.

Il permet de gérer :
- La création et la gestion de salles de réservation
- La création de réservations de créneaux horaires
- La vérification des conflits de créneaux
- La recherche des disponibilités des salles

L'objectif est de montrer la maîtrise des bonnes pratiques backend (architecture, découplage, règles métier, gestion des erreurs et tests unitaires) dans un contexte d'entretien technique senior.

---

## Architecture du projet

- `public/index.php` — Point d'entrée de l'application.
- `config/database.php` — Connexion PDO MySQL.
- `src/Core/` — Routeur maison, gestion des erreurs, parsing JSON.
- `src/Models/` — Entités métier Room & Booking.
- `src/Repositories/` — Accès base de données (PDO).
- `src/Services/` — Logique métier complète.
- `src/Controllers/` — Gère les requêtes HTTP.
- `tests/Services/` — Tests unitaires avec PHPUnit.

---

## Stack technique

- PHP 8.4.8
- PDO (MySQL)
- Composer (autoload PSR-4)
- PHPUnit

---

## Business Rules (Contraintes métier)

- Les salles doivent exister pour réserver.
- Les créneaux ne doivent pas se chevaucher.
- Les dates doivent être valides (`start < end`).
- Les capacities des salles doivent être positives.
- Gestion des erreurs REST standardisée : 400, 404, 422, 500.
- Les validations métier sont centralisées dans les Services.

---

## Endpoints API

| Méthode | Endpoint              | Description                                 | Paramètres requis                                  |
|--------:|-----------------------|---------------------------------------------|--------------------------------------------------- |
| GET     | /rooms                | Liste toutes les salles                     | -                                                  |
| POST    | /rooms                | Créer une salle                             | name, capacity                                     |
| POST    | /bookings             | Créer une réservation                       | room_id, start_datetime, end_datetime, client_name |
| GET     | /availability         | Liste les salles disponibles sur un créneau | start, end                                         |
| GET     | /availability/check   | Vérifie la dispo d’une salle                | room_id, start, end                                |

---

## Exemples de requêtes cURL

```bash
curl -X POST http://localhost:8000/rooms -H "Content-Type: application/json" -d '{"name": "Salle A", "capacity": 10}'

curl -X POST http://localhost:8000/bookings -H "Content-Type: application/json" -d '{"room_id":1,"start_datetime":"2025-07-01 10:00:00","end_datetime":"2025-07-01 11:00:00","client_name":"Oscar"}'

curl "http://localhost:8000/availability/check?room_id=1&start=2025-07-01%2010:00:00&end=2025-07-01%2011:00:00"
```

---

## Diagramme UML logique

```
+----------------+        +----------------+
|     Room       |        |    Booking     |
+----------------+        +----------------+
| id (PK)        |<-------| id (PK)        |
| name           |        | room_id (FK)   |
| capacity       |        | start_datetime |
+----------------+        | end_datetime   |
                          | client_name    |
                          +----------------+
```

---

## Tests

Lancer les tests :

```bash
vendor/bin/phpunit tests/
```
