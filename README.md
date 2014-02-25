# Candle

Candle je webová aplikácia umožňujúca prezerať si rozvrhy a zostaviť si ich
personalizované verzie, čo sa hodí napríklad keď si študent zapíše predmety,
ktoré nie sú v rozvrhu krúžku. Aplikácia tiež zobrazuje ďaľšie užitočné informácie
ako aktuálne prebiehajúcu výučbu alebo voľné miestnosti.

Produkčná inštancia prevádzkovaná študentským vývojovým tímom sa nachádza na adrese
https://candle.fmph.uniba.sk/

## Čo Candle používa?

Candle závisí na niekoľkých projektoch:

* Aplikácia je postavená na Symfony 1.4 - http://symfony.com/legacy
* Candle závisí na MySQL, pretože používa natívne SQL príkazy špecifické pre túto DB
* Candle používa iCalcreator na výstup do iCalendar formátu

## Ako si u seba spustiť vývojársku inštanciu Candle?

1. Vyklonujte si Candle na svoj počítač: `git clone https://github.com/fmfi-svt/candle.git`
2. Ak ešte nemáte, nainštalujte si mysql-server, PHP a php5-mysql
3. Vytvorte si pre Candle databázu:

   ```sql
   create database candle charset utf8 collate utf8_general_ci;
   create user candle@localhost identified by 'TajneHeslo';
   grant all on candle.* to candle@localhost;
   ```

4. Nastavte databázové pripojenie v Candle:
   1. Skopírujte `config/databases.example.yml` do `config/databases.yml`
   2. Upravte `config/databases.yml` aby sedeli údaje použité pri vytváraní databázy
5. Skontrolujte aplikačné parametre Candle v súbore `apps/frontend/config/app.yml`
6. Vytvorte štruktúru databázy:

   ```bash
   ./symfony doctrine:insert-sql
   ```

7. Spustite vývojársky server v PHP:

   ```bash
   cd web/
   php -S localhost:8080
   ```

   Aplikácia by mala na už bežať na http://localhost:8080. Keďže na prihlasovanie sa bežne
   používa CoSign, cez PHP server nefunguje prihlasovanie. Avšak na testovanie
   postačí, ak na začiatku súboru `web/index.php` nastavíte `REMOTE_USER`
   (ktorý je inak nastavovaný z CoSign modulu v Apachi)

   ```php
   <?php
   
   $_SERVER['REMOTE_USER'] = 'sucha14';
   
   // zvysok suboru...
   ```

8. Naimportujte si rozvrhové dáta z XML súboru (napíšte nám ak žiaden ešte nemáte):

   ```bash
   ./symfony candle:import /cesta/k/suboru.xml
   ```

## Ako si u seba spustiť produkčnú inštanciu Candle?

Podobne ako vývojársku inštanciu, len sa použije namiesto `php -S` Apache + suphp + mod_cosign.
Tiež treba nastaviť `session_name` + ukladanie sessions do DB v súbore `apps/frontend/config/factories.yml`

## Ako aktualizovať rozvrh?

Postup je rovnaký ako pri prvkom importe. Aktualizovaný rozvrh importujte pomocou príkazu:

```bash
./symfony candle:import /cesta/k/suboru.xml
```

Pred importom odporúčame zálohovať databázu. Pomocou prepínača `--dry-run` si najskôr môžete overiť či celý proces zbehne bez chýb.
