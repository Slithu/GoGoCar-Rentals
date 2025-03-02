# GoGoCar-Rentals

## Opis projektu
Aplikacja internetowa stworzona w frameworku **Laravel 10** służy do zarządzania wypożyczalnią samochodów. System oferuje funkcjonalności takie jak rejestracja i logowanie użytkowników, przeglądanie dostępnych pojazdów, dokonywanie rezerwacji, generowanie raportów oraz realizację płatności online. Dodatkowo, aplikacja posiada zaawansowany system rekomendacji oparty na algorytmach uczenia maszynowego, który sugeruje użytkownikom najlepsze oferty wynajmu na podstawie ich historii oraz preferencji.

## Technologie
Do stworzenia aplikacji wykorzystano:
- **PHP** (wersja 8.1.10)
- **Laravel** (wersja 10.48.10) – framework PHP do zarządzania logiką aplikacji w architekturze MVC
- **MySQL** (wersja 10.4.25-MariaDB) – relacyjna baza danych
- **Node.js** (wersja 18.16.0) – obsługa środowiska front-endowego
- **Vite** (wersja 5.0.0) oraz **Laravel Mix** (wersja 6.0.49) – kompilacja zasobów front-endowych
- **Blade** – system szablonów Laravel
- **Bootstrap** (wersja 5.2.3) – framework CSS do stylizacji interfejsu użytkownika
- **Microsoft Visual Studio Code** – środowisko programistyczne
- **Composer** – zarządzanie zależnościami PHP

## System rekomendacji
System rekomendacji w aplikacji wykorzystuje cztery różne klasyfikatory do przewidywania preferencji użytkowników:
- **Naiwny Bayes**
- **k-Nearest Neighbors (kNN)**
- **Sztuczna sieć neuronowa MLP** (Multilayer Perceptron)
- **Drzewo decyzyjne**

Użytkownicy otrzymują spersonalizowane sugestie wynajmu na podstawie danych demograficznych (wiek i płeć), historii wypożyczeń oraz ocen pojazdów. Algorytmy są implementowane przy użyciu biblioteki **php-ai/php-ml** (wersja 0.10.0).

## Kluczowe funkcjonalności
- **Autoryzacja i rejestracja** użytkowników
- **Przeglądanie i wyszukiwanie** dostępnych samochodów
- **System rezerwacji i zwrotów** pojazdów
- **Rekomendacje samochodów** na podstawie uczenia maszynowego
- **Generowanie raportów PDF** dla administratora (biblioteka **laravel-dompdf** 2.2)
- **Obsługa płatności online** poprzez **Stripe (stripe-php 15.2)**
- **Obsługa powiadomień e-mail** poprzez **Mailtrap**

## Uruchomienie projektu lokalnie

Aby szybko skonfigurować i uruchomić projekt lokalnie, można skorzystać z pliku **`start.bat`**. Plik ten automatyzuje proces instalacji zależności, konfiguracji środowiska oraz migracji bazy danych.
