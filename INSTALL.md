# Guida di Installazione - Sistema Prenotazione Dinamico

## Installazione Rapida

### Passo 1: Download e Upload
1. Scarica tutti i file del plugin
2. Comprimi in un file ZIP (se necessario)
3. Carica via **Plugin > Aggiungi nuovo > Carica plugin** nel tuo WordPress

### Passo 2: Attivazione
1. Attiva il plugin dalla pagina **Plugin**
2. Apparirà automaticamente il menu **"Prenotazioni"** nella sidebar admin

### Passo 3: Configurazione Iniziale
1. Vai su **Prenotazioni > Date e Prezzi**
2. Aggiungi le tue prime date disponibili:
   ```
   Data: 2025-07-15
   Prezzo: 150.00
   Prenotazioni Massime: 5
   Disponibile: ✓
   ```

### Passo 4: Creazione Pagina
1. Crea una nuova pagina **"Prenota Ora"**
2. Inserisci il contenuto:
   ```
   [prenotazione_form]
   ```
3. Pubblica la pagina

## Test del Funzionamento

### 1. Test Backend
- [ ] Menu "Prenotazioni" visibile in admin
- [ ] Puoi aggiungere nuove date
- [ ] Puoi modificare prezzi esistenti
- [ ] Tabelle database create correttamente

### 2. Test Frontend
- [ ] Form di prenotazione appare sulla pagina
- [ ] Validazione campi funziona
- [ ] Calendario si attiva dopo compilazione dati
- [ ] Solo date configurate sono selezionabili
- [ ] Prezzo appare dopo selezione data
- [ ] Form invia correttamente

### 3. Test Mobile
- [ ] Form responsive su smartphone
- [ ] Calendario utilizzabile su touch
- [ ] Pulsanti dimensione adeguata

## Risoluzione Problemi

### Plugin non si attiva
```bash
# Controlla permessi file
chmod 755 /wp-content/plugins/booking-system-plugin/
chmod 644 /wp-content/plugins/booking-system-plugin/*.php
```

### Form non appare
1. Verifica shortcode: `[prenotazione_form]`
2. Controlla console browser per errori JavaScript
3. Verifica tema compatibile con WordPress standard

### Database non funziona
- Controlla permessi database MySQL
- Verifica versione PHP >= 7.4
- Attiva debug WordPress per log errori

## Personalizzazione Rapida

### Cambia Colori
Aggiungi nel **Personalizza > CSS aggiuntivo**:
```css
.booking-btn-primary {
    background: #YOUR_COLOR !important;
}
```

### Cambia Testi
Modifica nel file `includes/class-frontend.php` le traduzioni:
```php
__('Prenota Ora', 'booking-system')
```

## Configurazioni Avanzate

### Limiti di Sicurezza
```php
// In wp-config.php per maggiore sicurezza
define('BOOKING_SYSTEM_MAX_DATES_PER_MONTH', 31);
define('BOOKING_SYSTEM_MAX_BOOKINGS_PER_IP', 3);
```

### Email Notifications (da implementare)
```php
// Hook per email personalizzate
add_action('booking_system_after_booking', 'send_custom_booking_email');
```

## Backup e Manutenzione

### Backup Dati
```sql
-- Backup tabelle prenotazioni
mysqldump -u user -p database_name wp_booking_system_dates > backup_dates.sql
mysqldump -u user -p database_name wp_booking_system_bookings > backup_bookings.sql
```

### Pulizia Dati Vecchi
```sql
-- Rimuovi prenotazioni > 1 anno
DELETE FROM wp_booking_system_bookings 
WHERE created_at < DATE_SUB(NOW(), INTERVAL 1 YEAR);
```

## Performance

### Ottimizzazioni Raccomandate
1. **Cache**: Usa plugin di cache (WP Rocket, W3 Total Cache)
2. **CDN**: Configura CDN per assets statici
3. **Database**: Indici automatici già configurati
4. **Immagini**: Ottimizza immagini calendario se personalizzate

### Monitoraggio
- Controlla **Prenotazioni > Prenotazioni** per volume
- Monitor errori in `/wp-content/debug.log`
- Test periodici form frontend

## Supporto

### Log di Debug
```php
// Attiva debug temporaneo
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
```

### Informazioni Sistema
Vai su **Prenotazioni > Impostazioni** per:
- Versione plugin
- Tabelle database
- Shortcode disponibili

---

✅ **Plugin pronto per l'uso!**

*Per ulteriore assistenza, consulta il file README.md completo.*
