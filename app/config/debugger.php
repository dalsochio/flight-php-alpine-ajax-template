<?php

use App\Helpers\Utility;
use flight\debug\tracy\TracyExtensionLoader;
use Tracy\Debugger;

// set default tracy debugger mode to production (minimal error information)
$debuggerMode = Debugger::Production;

// enable development mode with detailed error information in dev/staging environments
$debuggerMode = Debugger::Development;
$_SESSION['hot-reload'] = Utility::isViteRunning();

// activate tracy debugger with the configured mode
Debugger::enable($debuggerMode);

// get the default tracy file logger
$logger = Debugger::getLogger();

// set a directory for storing local log files
Debugger::$logDirectory = __DIR__ . '/../storage/log';
// enable strict mode to display all errors and notices
Debugger::$strictMode = true; // display all errors

// only show the tracy debug bar in browser (not in cli) and when enabled
if (Debugger::$showBar && php_sapi_name() !== 'cli') {
    // disable content-length header when tracy bar is visible to prevent conflicts
    Flight::set('flight.content_length', false); // if the Debugger bar is visible, then Flight cannot set content-length

    $hotReload = $_SESSION['hot-reload'] === true;

    // Just for dev. Shows a VITE symbol in the tracy debug bar
    Debugger::getBar()->addPanel(
        new class($hotReload) implements \Tracy\IBarPanel {
            private $enabled;

            public function __construct($enabled)
            {
                $this->enabled = $enabled;
            }

            public function getTab(): string
            {
                return $this->enabled ? '<div style="color:#4CAF50; cursor: context-menu;">HOT RELOAD ✓</div>' : '<div style="color:#F44336; cursor: context-menu;">HOT RELOAD ✗</div>';
            }

            public function getPanel(): string
            {
                return '';
            }
        }
    );

    try {
        // initialize tracy extension with access to session data for debugging
        new TracyExtensionLoader(Flight::app(), ['session_data' => $_SESSION]);
    } catch (\Exception $e) {
        throw new \Exception('Tracy extension loader errors: ' . $e->getMessage());
    }
}
