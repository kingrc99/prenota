# Sistema Prenotazione Dinamico - Plugin WordPress

Un plugin WordPress completo per gestire prenotazioni con form dinamico, calendario integrato e sistema di prezzi variabili.

## Caratteristiche Principali

### 🎯 Frontend
- **Form di prenotazione dinamico** con validazione progressiva
- **Calendario integrato** con date disponibili evidenziate
- **Calcolo prezzi in tempo reale** basato sulla data selezionata
- **Design responsive** ottimizzato per mobile e desktop
- **Validazione client-side e server-side** per massima sicurezza

### ⚙️ Backend
- **Pannello amministrativo intuitivo** per gestione date e prezzi
- **Gestione prenotazioni** con stato e dettagli clienti
- **Sistema di notifiche** per admin
- **Interfaccia user-friendly** con ricerca e filtri

### 🔧 Funzionalità Tecniche
- **Database ottimizzato** con tabelle dedicate
- **AJAX integrato** per esperienza fluida
- **Shortcode flessibile** per inserimento in qualsiasi pagina
- **Sicurezza avanzata** con nonce e validazione
- **Codice modulare** e ben documentato

## Installazione

### Requisiti
- WordPress 5.0 o superiore
- PHP 7.4 o superiore
- MySQL 5.6 o superiore

### Procedura di Installazione

1. **Carica il plugin**
   ```bash
   # Comprimi la cartella del plugin
   zip -r booking-system-plugin.zip booking-system-plugin/
   ```

2. **Installazione via WordPress Admin**
   - Vai su `Plugin > Aggiungi nuovo > Carica plugin`
   - Seleziona il file `booking-system-plugin.zip`
   - Clicca "Installa ora" e poi "Attiva"

3. **Installazione via FTP**
   - Carica la cartella `booking-system-plugin` in `/wp-content/plugins/`
   - Attiva il plugin dal pannello WordPress

## Configurazione

### 1. Configurazione Date e Prezzi

Dopo l'attivazione:

1. Vai su **Prenotazioni > Date e Prezzi** nel menu admin
2. Aggiungi le tue prime date con i relativi prezzi
3. Configura disponibilità e numero massimo prenotazioni

**Esempio di configurazione:**
```
Data: 2025-07-15
Prezzo: €150.00
Prenotazioni Massime: 5
Disponibile: ✓
```

### 2. Inserimento Form nelle Pagine

Usa lo shortcode per mostrare il form:

```
[prenotazione_form]
```

**Con parametri personalizzati:**
```
[prenotazione_form title="Prenota la tua esperienza" show_title="true"]
```

### 3. Personalizzazione Pagina "Prenota Ora"

1. Crea una nuova pagina chiamata "Prenota Ora"
2. Inserisci semplicemente: `[prenotazione_form]`
3. Pubblica la pagina

## Struttura del Plugin

```
booking-system-plugin/
├── booking-system.php          # File principale
├── includes/                   # Classi PHP
│   ├── class-database.php      # Gestione database
│   ├── class-admin.php         # Pannello amministrativo
│   ├── class-frontend.php      # Logica frontend
│   ├── class-shortcode.php     # Gestione shortcode
│   └── class-ajax.php          # Chiamate AJAX
├── assets/                     # Risorse statiche
│   ├── css/
│   │   ├── frontend.css        # Stili form pubblico
│   │   └── admin.css           # Stili pannello admin
│   └── js/
│       ├── frontend.js         # JavaScript form
│       └── admin.js            # JavaScript admin
└── README.md                   # Documentazione
```

## Utilizzo

### Per gli Amministratori

#### Gestione Date
1. Accedi a **Prenotazioni > Date e Prezzi**
2. Aggiungi nuove date con il form dedicato
3. Modifica prezzi e disponibilità esistenti
4. Monitora prenotazioni vs disponibilità

#### Gestione Prenotazioni
1. Vai su **Prenotazioni > Prenotazioni**
2. Visualizza tutte le prenotazioni clienti
3. Controlla stati: In Attesa, Confermata, Annullata
4. Accedi ai dettagli di contatto

### Per gli Utenti Frontend

#### Processo di Prenotazione
1. **Compila i dati personali** (Nome, Cognome, Email, Telefono)
2. **Seleziona la data** dal calendario (si attiva automaticamente)
3. **Conferma il prezzo** visualizzato
4. **Invia la prenotazione**

#### Validazione Automatica
- I campi vengono validati in tempo reale
- Il calendario appare solo quando tutti i dati sono corretti
- Il prezzo viene calcolato istantaneamente

## Personalizzazione

### CSS Personalizzato

Aggiungi CSS personalizzato per modificare l'aspetto:

```css
/* Personalizza il form di prenotazione */
.booking-system-container {
    max-width: 800px;
    background: #f9f9f9;
}

/* Personalizza i pulsanti */
.booking-btn-primary {
    background: #your-color;
}
```

### Hook per Sviluppatori

Il plugin offre diversi hook per estensioni:

```php
// Dopo inserimento prenotazione
do_action('booking_system_after_booking', $booking_id, $booking_data);

// Prima di mostrare il form
do_action('booking_system_before_form', $atts);

// Filtro per personalizzare campi form
$fields = apply_filters('booking_system_form_fields', $default_fields);
```

## Sicurezza

### Funzionalità di Sicurezza Implementate

- **Nonce verification** per tutte le operazioni
- **Sanitizzazione input** per prevenire XSS
- **Validazione server-side** di tutti i dati
- **Prepared statements** per query database
- **Controllo permessi** per accesso admin

### Best Practices

- Tieni sempre aggiornato WordPress
- Usa hosting con SSL certificato
- Backup regolari del database
- Monitora i log per attività sospette

## Database

### Tabelle Create

#### `wp_booking_system_dates`
Memorizza date disponibili e prezzi:
```sql
- id (PRIMARY KEY)
- booking_date (UNIQUE)
- price (DECIMAL)
- available (BOOLEAN)
- max_bookings (INT)
- current_bookings (INT)
- created_at, updated_at
```

#### `wp_booking_system_bookings`
Memorizza prenotazioni clienti:
```sql
- id (PRIMARY KEY)
- booking_date
- customer_name, customer_surname
- customer_email, customer_phone
- price (DECIMAL)
- status (VARCHAR)
- created_at, updated_at
```

## Troubleshooting

### Problemi Comuni

#### Il form non appare
- Verifica che il plugin sia attivato
- Controlla che lo shortcode sia corretto: `[prenotazione_form]`
- Verifica permessi file e cartelle

#### Il calendario non funziona
- Controlla che jQuery UI sia caricato
- Verifica connessione internet per CDN
- Controlla console browser per errori JavaScript

#### Errori AJAX
- Verifica URL admin-ajax.php sia accessibile
- Controlla che i nonce siano validi
- Verifica permessi del database

#### Date non disponibili
- Controlla che le date siano configurate nell'admin
- Verifica che siano marcate come "Disponibili"
- Controlla che non abbiano raggiunto il limite prenotazioni

### Log di Debug

Attiva il debug WordPress per dettagli errori:

```php
// In wp-config.php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
```

## Supporto e Contributi

### Segnalazione Bug
Per segnalare bug o richiedere funzionalità:
1. Descrivi il problema dettagliatamente
2. Includi versioni WordPress/PHP
3. Fornisci passi per riprodurre l'errore

### Contributi al Codice
Il plugin è sviluppato seguendo:
- WordPress Coding Standards
- Principi SOLID
- Pattern MVC per separazione logica
- Commenti e documentazione estensiva

## Changelog

### Versione 1.0.0
- Prima release pubblica
- Form dinamico con validazione
- Pannello admin completo
- Sistema AJAX integrato
- Design responsive
- Database ottimizzato
- Sicurezza avanzata

## Licenza

GPL v2 or later

## Crediti

**Sviluppato da:** MiniMax Agent  
**Versione:** 1.0.0  
**Compatibilità WordPress:** 5.0+  
**Testato fino a:** WordPress 6.4  

---

*Questo plugin è stato sviluppato per offrire una soluzione completa e professionale per la gestione di prenotazioni in WordPress, con focus su usabilità, sicurezza e performance.*
