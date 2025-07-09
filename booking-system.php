<?php
/**
 * Plugin Name: Sistema Prenotazione Dinamico
 * Plugin URI: https://example.com
 * Description: Plugin per gestione prenotazioni con form dinamico, calendario e gestione prezzi con pagamenti Stripe.
 * Version: 2.1.1 (Corrected Version)
 * Author: MiniMax Agent
 * License: GPL v2 or later
 * Text Domain: booking-system
 */

if (!defined('ABSPATH')) { exit; }

// Caricamento libreria Stripe
$stripe_manual_path = plugin_dir_path(__FILE__) . 'stripe-php/init.php';
$stripe_composer_path = plugin_dir_path(__FILE__) . 'vendor/autoload.php';

if (file_exists($stripe_manual_path)) {
    require_once $stripe_manual_path;
} elseif (file_exists($stripe_composer_path)) {
    require_once $stripe_composer_path;
}

// Costanti plugin
define('BOOKING_SYSTEM_VERSION', '2.1.1');
define('BOOKING_SYSTEM_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('BOOKING_SYSTEM_PLUGIN_URL', plugin_dir_url(__FILE__));

/**
 * Classe principale del plugin Sistema Prenotazione Dinamico
 */
class BookingSystem {
    
    private static $instance = null;
    
    /**
     * Singleton pattern
     */
    public static function get_instance() {
        if (null === self::$instance) { 
            self::$instance = new self(); 
        }
        return self::$instance;
    }
    
    /**
     * Costruttore privato per singleton
     */
    private function __construct() {
        add_action('init', array($this, 'init'));
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
    }
    
    /**
     * Inizializzazione plugin
     */
    public function init() {
        // Carica traduzioni
        load_plugin_textdomain('booking-system', false, dirname(plugin_basename(__FILE__)) . '/languages');
        
        // Include file necessari
        $this->includes();
        
        // Inizializza hooks
        $this->init_hooks();
    }
    
    /**
     * Include tutti i file delle classi
     */
    private function includes() {
        require_once BOOKING_SYSTEM_PLUGIN_DIR . 'includes/class-database.php';
        require_once BOOKING_SYSTEM_PLUGIN_DIR . 'includes/class-admin.php';
        require_once BOOKING_SYSTEM_PLUGIN_DIR . 'includes/class-frontend.php';
        require_once BOOKING_SYSTEM_PLUGIN_DIR . 'includes/class-shortcode.php';
        require_once BOOKING_SYSTEM_PLUGIN_DIR . 'includes/class-ajax.php';
        require_once BOOKING_SYSTEM_PLUGIN_DIR . 'includes/class-thank-you.php';
    }
    
    /**
     * Inizializza tutti gli hooks e le istanze
     */
    private function init_hooks() {
        // Inizializza le classi
        new BookingSystem_Database();
        new BookingSystem_Admin();
        new BookingSystem_Frontend();
        new BookingSystem_Shortcode();
        new BookingSystem_Ajax();
        new BookingSystem_ThankYou();
        
        // Hooks per scripts e stili
        add_action('wp_enqueue_scripts', array($this, 'register_frontend_assets'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));

        // Controllo presenza libreria Stripe
        if (!class_exists('\\Stripe\\Stripe')) {
            add_action('admin_notices', array($this, 'stripe_library_missing_notice'));
        }
    }

    /**
     * Notice per libreria Stripe mancante
     */
    public function stripe_library_missing_notice() {
        ?>
        <div class="notice notice-error">
            <p><?php _e('<b>Sistema Prenotazione Dinamico:</b> La libreria di pagamento Stripe non è stata trovata. I pagamenti sono disabilitati.', 'booking-system'); ?></p>
        </div>
        <?php
    }

    /**
     * Registra assets frontend
     */
    public function register_frontend_assets() {
        // Script Stripe
        wp_register_script('stripe-js', 'https://js.stripe.com/v3/', array(), null, true);
        wp_enqueue_script('stripe-js');

        // Script frontend
        wp_register_script(
            'booking-system-frontend', 
            BOOKING_SYSTEM_PLUGIN_URL . 'assets/js/frontend.js', 
            array('jquery', 'jquery-ui-datepicker', 'stripe-js'), 
            BOOKING_SYSTEM_VERSION, 
            true
        );

        // Stili frontend
        wp_register_style(
            'booking-system-datepicker-base', 
            BOOKING_SYSTEM_PLUGIN_URL . 'assets/css/jquery-ui-base.css', 
            array(), 
            BOOKING_SYSTEM_VERSION
        );
        
        wp_register_style(
            'booking-system-datepicker-custom', 
            BOOKING_SYSTEM_PLUGIN_URL . 'assets/css/datepicker-custom.css', 
            array('booking-system-datepicker-base'), 
            BOOKING_SYSTEM_VERSION
        );
        
        wp_register_style(
            'booking-system-frontend', 
            BOOKING_SYSTEM_PLUGIN_URL . 'assets/css/frontend.css', 
            array(), 
            BOOKING_SYSTEM_VERSION
        );
    }
    
    /**
     * Carica scripts admin
     */
    public function enqueue_admin_scripts($hook) {
        // Carica solo nelle pagine del plugin
        if (strpos($hook, 'booking-system') === false) { 
            return; 
        }
        
        // jQuery UI Datepicker
        wp_enqueue_script('jquery-ui-datepicker');
        wp_enqueue_style('wp-jquery-ui-datepicker');
        
        // Script admin
        wp_enqueue_script(
            'booking-system-admin', 
            BOOKING_SYSTEM_PLUGIN_URL . 'assets/js/admin.js', 
            array('jquery', 'jquery-ui-datepicker'), 
            BOOKING_SYSTEM_VERSION, 
            true
        );
        
        // Stili admin
        wp_enqueue_style(
            'booking-system-admin', 
            BOOKING_SYSTEM_PLUGIN_URL . 'assets/css/admin.css', 
            array(), 
            BOOKING_SYSTEM_VERSION
        );
    }
    
    /**
     * Attivazione plugin
     */
    public function activate() {
        // Crea tabelle database
        if (class_exists('BookingSystem_Database')) { 
            BookingSystem_Database::create_tables(); 
        }
        
        // Salva versione
        add_option('booking_system_version', BOOKING_SYSTEM_VERSION);
        
        // Inizializza le rewrite rules per la pagina di ringraziamento
        $this->includes();
        new BookingSystem_ThankYou();
        
        // Flush rewrite rules
        flush_rewrite_rules();
    }
    
    /**
     * Disattivazione plugin
     */
    public function deactivate() { 
        flush_rewrite_rules(); 
    }
}

/**
 * Funzione helper per ottenere l'istanza del plugin
 */
function booking_system() { 
    return BookingSystem::get_instance(); 
}

// Inizializza il plugin
booking_system();
