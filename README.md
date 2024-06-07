# Kalendarz Internetowy

Kalendarz Internetowy to aplikacja do zarządzania wydarzeniami i notatkami. Użytkownicy mogą tworzyć, edytować i usuwać wydarzenia, a także przeglądać nadchodzące wydarzenia w formie listy. Dodatkowo, użytkownicy otrzymują cotygodniowe podsumowania wydarzeń na swoje adresy e-mail.

## Funkcje

- Rejestracja i logowanie użytkowników
- Dodawanie, edytowanie i usuwanie wydarzeń
- Przypisywanie notatek do wydarzeń
- Wyświetlanie kalendarza z wydarzeniami
- Wyświetlanie listy nadchodzących wydarzeń
- Wysyłanie cotygodniowych podsumowań na e-mail

## Technologie

- Frontend: HTML, CSS, JavaScript, Bootstrap, FullCalendar
- Backend: PHP
- Baza danych: MySQL
- Wysyłanie e-maili: PHPMailer

## Instalacja

### Lokalna instalacja

1. Zainstaluj serwer Apache, PHP i MySQL (np. za pomocą XAMPP).
2. Sklonuj repozytorium na swój lokalny komputer:
    ```bash
    git clone https://github.com/KRacz0/ProjectPPSI.git
    ```
3. Skopiuj pliki projektu do katalogu `htdocs`.
4. Uruchom XAMPP i włącz serwer Apache oraz MySQL.
5. Utwórz bazę danych i tabele:
    ```sql
    CREATE DATABASE kalendarz_db;
    CREATE TABLE users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );
    CREATE TABLE events (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        event_title VARCHAR(255) NOT NULL,
        event_date DATE NOT NULL,
        event_end_date DATE DEFAULT NULL,
        event_time TIME DEFAULT NULL,
        event_color VARCHAR(7) DEFAULT '#378006',
        all_day TINYINT(1) DEFAULT 0,
        event_start_time TIME DEFAULT NULL,
        event_end_time TIME DEFAULT NULL,
        note TEXT DEFAULT NULL,
        FOREIGN KEY (user_id) REFERENCES users(id)
    );
    CREATE TABLE email_config (
        id INT AUTO_INCREMENT PRIMARY KEY,
        host VARCHAR(255) NOT NULL,
        port INT NOT NULL,
        username VARCHAR(255) NOT NULL,
        password VARCHAR(255) NOT NULL
    );
    ```
6. Dodaj konfigurację e-mail:
    ```sql
    INSERT INTO email_config (host, port, username, password) VALUES ('smtp.example.com', 587, 'your_email@example.com', 'your_email_password');
    ```
7. Skonfiguruj połączenie z bazą danych w pliku `db_connect.php`:
    ```php
    <?php
    $servername = "localhost";
    $username = "your_db_username";
    $password = "your_db_password";
    $dbname = "kalendarz_db";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    ?>
    ```

8. Odwiedź `http://localhost/kalendzarz/login/index.html`, aby uzyskać dostęp do aplikacji.

### Zdalna instalacja

1. Wybierz serwer hostingowy obsługujący PHP i MySQL.
2. Sklonuj repozytorium na swój lokalny komputer:
    ```bash
    git clone https://github.com/KRacz0/ProjectPPSI.git
    ```
3. Skopiuj pliki projektu na serwer za pomocą FTP.
4. Utwórz bazę danych i tabele:
    ```sql
    CREATE DATABASE kalendarz_db;
    CREATE TABLE users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );
    CREATE TABLE events (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        event_title VARCHAR(255) NOT NULL,
        event_date DATE NOT NULL,
        event_end_date DATE DEFAULT NULL,
        event_time TIME DEFAULT NULL,
        event_color VARCHAR(7) DEFAULT '#378006',
        all_day TINYINT(1) DEFAULT 0,
        event_start_time TIME DEFAULT NULL,
        event_end_time TIME DEFAULT NULL,
        note TEXT DEFAULT NULL,
        FOREIGN KEY (user_id) REFERENCES users(id)
    );
    CREATE TABLE email_config (
        id INT AUTO_INCREMENT PRIMARY KEY,
        host VARCHAR(255) NOT NULL,
        port INT NOT NULL,
        username VARCHAR(255) NOT NULL,
        password VARCHAR(255) NOT NULL
    );
    ```
5. Dodaj konfigurację e-mail:
    ```sql
    INSERT INTO email_config (host, port, username, password) VALUES ('smtp.example.com', 587, 'your_email@example.com', 'your_email_password');
    ```
6. Skonfiguruj połączenie z bazą danych w pliku `db_connect.php`:
    ```php
    <?php
    $servername = "localhost";
    $username = "your_db_username";
    $password = "your_db_password";
    $dbname = "kalendarz_db";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    ?>
    ```

7. Odwiedź publiczny URL Twojej aplikacji, aby uzyskać dostęp do systemu.

## Konfiguracja e-mail

Aby wysyłać cotygodniowe podsumowania e-mail, skonfiguruj dane SMTP w bazie danych:
```sql
CREATE TABLE email_config (
    id INT AUTO_INCREMENT PRIMARY KEY,
    host VARCHAR(255) NOT NULL,
    port INT NOT NULL,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL
);
```


### Uruchomienie

1. Uruchom lokalny serwer WWW (np. Apache).
2. Umieść pliki projektu w katalogu serwera WWW (np. `htdocs` w przypadku XAMPP).
3. Otwórz przeglądarkę internetową i przejdź do adresu `http://localhost/kalendzarz/login/index.html`, aby uzyskać dostęp do strony logowania.

### Wysyłanie e-maili

Aby wysłać e-maile z podsumowaniem wydarzeń, odwiedź stronę `email.php` i kliknij przycisk "Wyślij e-maile".

## Wnioski projektowe

Projekt Kalendarz jest funkcjonalną aplikacją umożliwiającą zarządzanie wydarzeniami i notatkami. Implementacja takich funkcji jak autoryzacja użytkowników, integracja z bazą danych oraz wysyłanie e-maili z podsumowaniem wydarzeń zapewnia pełne i efektywne zarządzanie kalendarzem.
