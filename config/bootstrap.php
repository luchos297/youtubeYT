<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.10.8
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

/**
 * Configure paths required to find CakePHP + general filepath
 * constants
 */
require __DIR__ . '/paths.php';

// Use composer to load the autoloader.
require ROOT . DS . 'vendor' . DS . 'autoload.php';

//Use environment
require CONFIG . 'environment.php';

/**
 * Bootstrap CakePHP.
 *
 * Does the various bits of setup that CakePHP needs to do.
 * This includes:
 *
 * - Registering the CakePHP autoloader.
 * - Setting the default application paths.
 */
require CORE_PATH . 'config' . DS . 'bootstrap.php';

// You can remove this if you are confident you have intl installed.
if (!extension_loaded('intl')) {
    trigger_error('You must enable the intl extension to use CakePHP.', E_USER_ERROR);
}

use Cake\Cache\Cache;
use Cake\Console\ConsoleErrorHandler;
use Cake\Core\App;
use Cake\Core\Configure;
use Cake\Core\Configure\Engine\PhpConfig;
use Cake\Core\Plugin;
use Cake\Database\Type;
use Cake\Datasource\ConnectionManager;
use Cake\Error\ErrorHandler;
use Cake\Log\Log;
use Cake\Mailer\Email;
use Cake\Network\Request;
use Cake\Routing\DispatcherFactory;
use Cake\Utility\Inflector;
use Cake\Utility\Security;


/**
 * Read configuration file and inject configuration into various
 * CakePHP classes.
 *
 * By default there is only one configuration file. It is often a good
 * idea to create multiple configuration files, and separate the configuration
 * that changes from configuration that does not. This makes deployment simpler.
 */
$domain = strtolower(@$_SERVER['SERVER_NAME']);

try {
    Configure::config('default', new PhpConfig());

    if(strtolower(ENVIRONMENT) == 'production'){
        Configure::load('app_prod', 'default', false);
    }
    else if (strtolower(ENVIRONMENT) == 'test'){
        Configure::load('app_test', 'default', false);
    }
    else if (strtolower(ENVIRONMENT) == 'development'){
        Configure::load('app_dev', 'default', false);
    }

} catch (\Exception $e) {
    die($e->getMessage() . "\n");
}

// Load an environment local configuration file.
// You can use a file like app_local.php to provide local overrides to your
// shared configuration.
//Configure::load('app_local', 'default');

// When debug = false the metadata cache should last
// for a very very long time, as we don't want
// to refresh the cache while users are doing requests.
if (!Configure::read('debug')) {
    Configure::write('Cache._cake_model_.duration', '+1 years');
    Configure::write('Cache._cake_core_.duration', '+1 years');
}

/**
 * Set server timezone to UTC. You can change it to another timezone of your
 * choice but using UTC makes time calculations / conversions easier.
 */
date_default_timezone_set('America/Argentina/Buenos_Aires');

/**
 * Configure the mbstring extension to use the correct encoding.
 */
mb_internal_encoding(Configure::read('App.encoding'));

/**
 * Set the default locale. This controls how dates, number and currency is
 * formatted and sets the default language to use for translations.
 */
//ini_set('intl.default_locale', 'en_US');
ini_set('intl.default_locale', 'es');
/**
 * Register application error and exception handlers.
 */
$isCli = php_sapi_name() === 'cli';
if ($isCli) {
    (new ConsoleErrorHandler(Configure::read('Error')))->register();
} else {
    (new ErrorHandler(Configure::read('Error')))->register();
}

// Include the CLI bootstrap overrides.
if ($isCli) {
    require __DIR__ . '/bootstrap_cli.php';
}

/**
 * Set the full base URL.
 * This URL is used as the base of all absolute links.
 *
 * If you define fullBaseUrl in your config file you can remove this.
 */
if (!Configure::read('App.fullBaseUrl')) {
    $s = null;
    if (env('HTTPS')) {
        $s = 's';
    }

    $httpHost = env('HTTP_HOST');
    if (isset($httpHost)) {
        Configure::write('App.fullBaseUrl', 'http' . $s . '://' . $httpHost);
    }
    unset($httpHost, $s);
}

Cache::config(Configure::consume('Cache'));
ConnectionManager::config(Configure::consume('Datasources'));
Email::configTransport(Configure::consume('EmailTransport'));
Email::config(Configure::consume('Email'));
Log::config(Configure::consume('Log'));
Security::salt(Configure::consume('Security.salt'));

/**
 * The default crypto extension in 3.0 is OpenSSL.
 * If you are migrating from 2.x uncomment this code to
 * use a more compatible Mcrypt based implementation
 */
// Security::engine(new \Cake\Utility\Crypto\Mcrypt());

/**
 * Setup detectors for mobile and tablet.
 */
Request::addDetector('mobile', function ($request) {
    $detector = new \Detection\MobileDetect();
    return $detector->isMobile();
});
Request::addDetector('tablet', function ($request) {
    $detector = new \Detection\MobileDetect();
    return $detector->isTablet();
});

/**
 * Custom Inflector rules, can be set to correctly pluralize or singularize
 * table, model, controller names or whatever other string is passed to the
 * inflection functions.
 *
 * Inflector::rules('plural', ['/^(inflect)or$/i' => '\1ables']);
 * Inflector::rules('irregular', ['red' => 'redlings']);
 * Inflector::rules('uninflected', ['dontinflectme']);
 * Inflector::rules('transliteration', ['/å/' => 'aa']);
 */
Inflector::rules('irregular', ['portal' => 'portales', 'imagen'=>'imagenes']);
/**
 * Plugins need to be loaded manually, you can either load them one by one or all of them in a single call
 * Uncomment one of the lines below, as you need. make sure you read the documentation on Plugin to use more
 * advanced ways of loading plugins
 *
 * Plugin::loadAll(); // Loads all plugins at once
 * Plugin::load('Migrations'); //Loads a single plugin named Migrations
 *
 */

Plugin::load('Migrations');
Plugin::load('Xety/Cake3Upload');
Plugin::load('Less');

// Only try to load DebugKit in development mode
// Debug Kit should not be installed on a production system
if (Configure::read('debug')) {
    Plugin::load('DebugKit', ['bootstrap' => true]);
}

/**
 * Connect middleware/dispatcher filters.
 */
DispatcherFactory::add('Asset');
DispatcherFactory::add('Routing');
DispatcherFactory::add('ControllerFactory');

/**
 * Enable default locale format parsing.
 * This is needed for matching the auto-localized string output of Time() class when parsing dates.
 */
Type::build('date')->useLocaleParser();
Type::build('datetime')->useLocaleParser();

/* 
 * Path para imagenes rss
 */
$domain = strtolower(@$_SERVER['SERVER_NAME']);
if ($domain == "vistamedios.com.ar" || 
        $domain == "vistamedios.com" || 
        $domain == '54.232.247.186' ||
        $domain == "www.vistamedios.com.ar" ||
        $domain == "www.vistamedios.com"){
    Configure::write('dominio', 'http://'.$domain.'/');
    Configure::write('reproductor', 'http://vistamedios.com.ar/noticias/reproductor/');
    Configure::write('reproductor_tv', 'http://vistamedios.com.ar/noticias/reproductor_tv/');
}
else if ($domain == "visme-test-srv.aconcaguasf.com.ar" || $domain == "visme-test-srv" || $domain == '192.168.48.62'){
    Configure::write('dominio', 'http://visme-test-srv.aconcaguasf.com.ar/');
    Configure::write('reproductor', 'http://visme-test-srv.aconcaguasf.com.ar/noticias/reproductor/');
    Configure::write('reproductor_tv', 'http://visme-test-srv.aconcaguasf.com.ar/noticias/reproductor_tv/');
}
else if($domain == "http://portales.dev/" || $domain == "portales.dev"){
    Configure::write('dominio', 'http://portales.dev/');
    Configure::write('reproductor', 'http://portales.dev/noticias/reproductor/');
    Configure::write('reproductor_tv', 'http://portales.dev/noticias/reproductor_tv/');
}

Configure::write('path_imagen_rss', '../files/imagenes/filename/');
Configure::write('path_imagen_notas' , 'files/imagenes/filename/');
Configure::write('path_imagen_banner', '../img/images/banners/filename/');
Configure::write('path_imagen_banners', 'img/images/banners/filename/');
Configure::write('path_imagen_banner_mobile', '../img/images/banners/filename_mobile/');
Configure::write('path_imagen_portadas', 'img/images/portadas/');
Configure::write('usuario_twitter','vista_medios');
Configure::write('limite_tolerancia', 80);
Configure::write('urls', ['http://www.infobae.com/politica/2016/08/16/cristina-elisabet-kirchner-defendio-el-enriquecimiento-de-lazaro-baez/']);
Configure::write('nombres', ['Adán', 'Agustín', 'Alberto', 'Alejandro', 'Alfonso', 'Alfredo', 'Andrés', 
    'Antonio', 'Armando', 'Arturo', 'Benito', 'Benjamín', 'Bernardo', 'Carlos', 'César', 'Claudio', 
    'Clemente', 'Cristian', 'Cristobal', 'Daniel', 'David', 'Diego', 'Eduardo', 'Emilio', 'Enrique', 
    'Ernesto', 'Esteban', 'Federico', 'Felipe', 'Fernando', 'Francisco', 'Gabriel', 'Gerardo', 'Germán', 
    'Gilberto', 'Gonzalo', 'Gregorio', 'Guillermo', 'Gustavo', 'Hernán', 'Homero', 'Horacio', 'Hugo', 
    'Ignacio', 'Jacobo', 'Jaime', 'Javier', 'Jerónimo', 'Jesús', 'Joaquín', 'Jorge', 'Jorge Luis', 
    'José', 'José Eduardo', 'José Emilio', 'José Luis', 'José María', 'Juan', 'Juan Carlos', 'Julio', 
    'Julio César', 'Lorenzo', 'Lucas', 'Luciano', 'Luis', 'Luis Miguel', 'Manuel', 'Marco Antonio', 
    'Marcos', 'Mariano', 'Mario', 'Martín', 'Mateo', 'Miguel', 'Miguel Ángel', 'Nicolás', 'Octavio', 
    'Óscar', 'Pablo', 'Patricio', 'Pedro', 'Rafael', 'Ramiro', 'Ramón', 'Raúl', 'Ricardo', 'Roberto', 
    'Rodrigo', 'Rubén', 'Salvador', 'Samuel', 'Sancho', 'Santiago', 'Sergio', 'Teodoro', 'Timoteo', 
    'Tomás', 'Vicente', 'Víctor', 'Adela', 'Adriana', 'Alejandra', 'Alicia', 'Amalia', 'Ana', 
    'Ana Luisa', 'Ana María', 'Andrea', 'Anita', 'Ángela', 'Antonia', 'Barbara', 'Beatriz', 'Berta', 
    'Blanca', 'Caridad', 'Carla', 'Carlota', 'Carmen', 'Carolina', 'Catalina', 'Cecilia', 'Clara', 
    'Claudia', 'Concepción', 'Cristina', 'Daniela', 'Débora', 'Diana', 'Dolores', 'Dorotea', 'Elena', 
    'Elisa', 'Eloisa', 'Elsa', 'Elvira', 'Emilia', 'Esperanza', 'Estela', 'Ester', 'Eva', 'Florencia', 
    'Francisca', 'Gabriela', 'Graciela', 'Guillermina', 'Inés', 'Irene', 'Isabel', 'Isabela', 'Josefina',
    'Juana', 'Julia', 'Laura', 'Leonor', 'Leticia', 'Lilia', 'Lorena', 'Lourdes', 'Lucia', 'Luisa', 
    'Luz', 'Magdalena', 'Manuela', 'Marcela', 'Margarita', 'María', 'María del Carmen', 'María Cristina', 
    'María Elena', 'María Eugenia', 'María José', 'María Luisa', 'María Soledad', 'María Teresa', 
    'Mariana', 'Maricarmen', 'Marilu', 'Marisol', 'Marta', 'Mercedes', 'Micaela', 'Mónica', 'Natalia', 
    'Norma', 'Olivia', 'Patricia', 'Pilar', 'Ramona', 'Raquel', 'Rebeca', 'Reina', 'Rocio', 'Rosa', 
    'Rosalia', 'Rosario', 'Sara', 'Silvia', 'Sofia', 'Soledad', 'Sonia', 'Susana', 'Teresa', 'Verónica', 
    'Victoria', 'Virginia', 'Yolanda', 'Mauricio', 'Leonel', 'Lionel', 'Sofía', 'Camila', 'Valentina', 
    'Isabella', 'Valeria', 'Daniela', 'Mariana', 'Sara', 'Victoria', 'Gabriela', 'Ximena', 'Andrea', 
    'Natalia', 'Mía', 'Martina', 'Lucía', 'Samantha', 'María', 'María', 'Fernanda', 'Nicole', 'Alejandra', 
    'Paula', 'Emily', 'María', 'José', 'Fernanda', 'Luciana', 'Ana', 'Sofía', 'Melanie', 'Regina', 
    'Catalina', 'Ashley', 'Renata', 'Agustina', 'Abril', 'Emma', 'Emilia', 'Jazmín', 'Juanita', 'Briana',
    'Vanessa', 'Antonia', 'Laura', 'Antonella', 'Luna', 'Carla', 'Allison', 'Monserrat', 'Paulin', 
    'Isabel', 'Juliana', 'Valerie', 'Florencia', 'Adriana', 'Naomí', 'Amanda', 'Ariana', 'Morena', 'Natalie', 'Constanza', 
    'Lola', 'Zoe', 'Carolina', 'Micaela', 'Julia', 'Claudia', 'Paola', 'Alexa', 'Elena', 'Isidora', 'Rebeca', 'Josefina', 'Abigail', 
    'Julieta', 'Melissa', 'Michelle', 'Alba', 'María', 'Camila', 'Angela', 'Delfina', 'Aitana', 'Stephanie', 'Fátima', 'Manuela', 
    'Alexandra', 'Paloma', 'Candela', 'Clara', 'Laura', 'Sofía', 'Diana', 'Ana', 'María', 'Guadalupe', 'Bárbara', 'Bianca', 'Miranda', 
    'Sabrina', 'Pilar', 'Ana', 'María', 'Marta', 'Ana', 'Génesis', 'Santiago', 'Sebastián', 'Diego', 'Nicolás', 'Samuel', 'Alejandro', 
    'Daniel', 'Mateo', 'Ángel', 'Matías', 'Gabriel', 'Tomás', 'David', 'Emiliano', 'Andrés', 'Joaquín', 'Carlos', 'Alexander', 'Adrián', 
    'Lucas', 'Benjamín', 'Leonardo', 'Rodrigo', 'Felipe', 'Francisco', 'Pablo', 'Martín', 'Fernando', 'Isaac', 'Manuel', 'Juan', 
    'Pablo', 'Emmanuel', 'Emilio', 'Vicente', 'Eduardo', 'Juan', 'Javier', 'Jorge', 'Aarón', 'José', 'Erick', 'Luis', 'Cristian', 
    'Ignacio', 'Christopher', 'Jesús', 'Kevin', 'Juan', 'José', 'Agustín', 'Juan', 'David', 'Simón', 'Joshua', 'Maximiliano', 'Miguel', 
    'Ángel', 'Juan', 'Sebastián', 'Bruno', 'Iván', 'Gael', 'Miguel', 'Thiago', 'Jerónimo', 'Hugo', 'Ricardo', 'Antonio', 'Ian', 
    'Anthony', 'Pedro', 'Rafael', 'Jonathan', 'Esteban', 'Juan', 'Manuel', 'Julián', 'Mauricio', 'Oscar', 'Santino', 'Axel', 'Sergio', 
    'Guillermo', 'Matthew', 'Valentín', 'Bautista', 'Álvaro', 'Dylan', 'Marcos', 'Kimberly', 'Mario', 'César', 'Cristóbal', 
    'Luca', 'Iker', 'Juan', 'Andrés', 'Gonzalo', 'Roberto', 'Valentino', 'Facundo', 'Patricio', 'Diego', 'Alejandro', 'Josué', 'Franco'
]);
Configure::write('exclusion', ['acá', 'allá', 'allí', 'año', 'así', 'chao', 'chau', 'dan', 'das', 'den', 
'día', 'dos', 'doy', 'ella', 'era', 'esa', 'ese', 'eso', 'fue', 'fuí', 'gil', 'gol', 'hay', 'hoy', 
'iba', 'ida', 'ido', 'iré', 'las', 'lea', 'lee', 'leí', 'leo', 'los', 'luz', 'mas', 'meo', 'mia', 
'mio', 'muy', 'ojo', 'oye', 'pie', 'pre', 'que', 'ras', 'rie', 'san', 'sea', 'ser', 'sin', 'son', 
'sos', 'soy', 'sus', 'tal', 'tan', 'ten', 'tus', 'una', 'uno', 'usa', 'use', 'uso', 'van', 'vas', 'ven', 
'veo', 'ver', 'ves', 'vez', 'vos', 'voy', 'abra', 'abre', 'abrí', 'abro', 'algo', 'cabe', 'caca', 'caen', 
'caer', 'caes', 'caía', 'casi', 'coso', 'crea', 'cree', 'creí', 
'creo', 'culo', 'cuya', 'dura', 'duro', 'edad', 'esas', 'esos', 'esta', 'este', 'esto', 'forra', 
'forro', 'gana', 'gano', 'gota', 'goza', 'gozo', 'gran', 'gris', 'hecho', 'hija', 'hijo', 'hilo', 
'hizo', 'hoja', 'hola', 'hubo', 'humo', 'iban', 'jaja', 'jeje',
'jiji', 'jojo', 'juju', 'jeta', 'juga', 'lava', 'lavo', 'loca', 'loco', 'macho', 'mala', 'mano', 'mapa',     
'mata', 'mate', 'mato', 'mear', 'mete', 'metí', 'meto', 'mida', 'mide', 'mido', 'mijo', 'mili', 
'mimo', 'mina', 'mios', 'mira', 'mire', 'miro', 'mucho', 'nace', 'nací', 'odia', 'odio', 'oiga',
'oigo', 'oirá', 'oiré', 'opta', 'opte', 'opto', 'orto', 'osea', 'otra', 'otro', 'pete', 'piba', 
'pibe', 'pija', 'puta', 'puto', 'raíz',
'rama', 'raro', 'rata', 'rato', 'raya', 'reír', 'reis', 'reja', 'rian', 'rica', 'rico', 'rota', 'roto', 
'roza', 'rozo', 'sabe', 'sale', 'salí', 'sean', 'seas', 'sepa', 'sera', 'seré', 'sido', 'siga', 
'sigo', 'silla', 'sube', 'subí', 'subo', 'suma', 'sumo', 'supe', 'suya', 'suyo', 'teta', 'unos', 
'usan', 'usar', 'usas', 'usen', 'uses', 'usos', 'uvis', 'vení', 'abajo', 'abran', 'abren', 'abrir', 
'adiós', 'aguas', 'ambos', 'andan',
'andar', 'andas', 'bajón', 'bajos', 'banca', 'banco', 'banda', 'bando', 'bañan', 'bañar', 'bañas', 
'bañen', 'bañes', 'beban', 'beben', 'beber', 'bebes', 'bebía', 'bebió', 'besan', 'besar', 'besas', 
'besen', 'billar', 'birlo', 'borran', 'borrar', 'borrón', 'brilla', 'brillo', 'bucea', 'buena', 
'bueno', 'caben', 'caber', 'cabes', 'cabía', 'cabio', 'cacas', 'cacos', 'caerá', 'caeré', 'cagan', 
'cagar', 'cagas', 'cagón', 'cague', 'caido', 'calar', 'calas', 'calca', 'calco', 'cansa', 'capaz', 
'casen', 'catan',
'catar', 'catas', 'caten', 'cateo', 'catre', 'cavan', 'cavar', 'caven', 'caves', 'cavia', 'cavío', 
'cazan', 'cazar', 'cazas', 'ceban', 'cebar', 'cebas', 'ceben', 'cebes', 'celan', 'celar', 'celas', 
'celen', 'celes', 'cenan', 'cenar', 'cenas', 'cenen', 'cenes', 'cerca', 'cerrar', 'cerras', 'clara', 
'claro', 'clona', 'clono', 'cobra', 'cobre', 'cobro', 'cogen', 'coger', 'coges', 'cogía', 'cogió', 
'coito',
'cojan', 'cojas', 'colas', 'colga', 'colgo', 'oman', 'comas', 'comen', 'comer', 'comes', 'comía', 
'comió', 'común', 'concha', 'copan', 'copar', 'corno', 'corran', 'corras', 'correa', 'corren', 
'correr', 'corres', 'corría', 'corrió', 'corta', 'corte', 'corto', 'cosas', 'cosen', 'coser', 
'coses', 'cosía', 'cosió', 'cosos', 'crean', 'crear', 'creas', 'crece', 'crecí', 'crien', 'cries', 
'cruja', 'cruje', 'crují',
'crujo', 'cruza', 'cruzo', 'cubra', 'cubre', 'cubrí', 'cubro', 'cueca', 'cuece', 'cueco', 'cuela', 
'cuele', 'cuello', 'cuelo', 'cuero', 'cuida', 'cuide', 'cuido', 'culea', 'culeo', 'culio', 'culón', 
'culos', 'choto', 'chota', 'chatea', 'chatee', 'chetas', 'chetos', 'chillan', 'chillar', 'chillas', 
'chillen', 'chilles', 'chillón', 'chirlo', 'chisme', 'chocan', 'chocar', 'chocas', 'chupan', 
'chupar', 'chupas',
'chupen', 'chupes', 'chupón', 'chusma', 'daban', 'dabas', 'damas', 'dañan', 'dañar', 'dañas', 'dañen', 
'dañes', 'daños', 'daran', 'daras', 'deban', 'debas', 'deben', 'deber', 'debes', 'debía', 'débil', 
'debió', 'decía', 'decir', 'decís', 'dejan', 'dejar', 'dejas', 'dejen', 'dejes', 'desde', 'desea',
'desee', 'deseo', 'dicen', 'dices', 'dichas', 'dichos', 'dicta', 'dicte', 'dicto', 'diera', 'diese',
'dieta', 'digan', 'digas', 'digna', 'digno',
'diosa', 'diran', 'diras', 'diria', 'dobla', 'doble', 'doblo', 'dócil', 'dolía', 'dolió', 'dolor', 
'doman', 'domar', 'domas', 'domen', 'domes', 'donan', 'donar', 'donas', 'donde', 'donen', 'dones',
'doñas', 'doran', 'dorar', 'doras', 'doren', 'dores', 'dormí', 'dotan', 'dotar', 'dotas', 'doten', 
'drama', 'drena', 'drene', 'duchan', 'duchar', 'duchas', 'duchen', 'duches', 'dudan', 'dudar', 
'dudas', 'duden', 'dudes',
'duela', 'duele', 'duran', 'durar', 'duras', 'duren', 'dures', 'duros', 'ebria', 'ebrio', 'echaba', 
'echada', 'echado', 'echara', 'echare', 'echase', 'edita', 'edite', 'edito', 'eluda', 'elude', 
'eludí', 'eludo', 'emana', 'emane', 'emano', 'emita', 'emite', 'emití', 'emito', 'emula',
'emule', 'emulo', 'enana', 'enano', 'enero', 'entra', 'entre', 'entro', 'envia', 'envie', 'envio', 
'épica', 'épico', 'época', 'eruta', 'erute', 'eruto', 'espia', 'espie', 'espio', 'esquí', 'estan', 
'estar', 'estas', 'esten', 'estes', 'estos', 'estoy', 'ética', 'ético', 'evadí', 'evado', 'evita', 
'evite', 'evito', 'exige', 'exigí', 'exija', 'exijo', 'extra', 'fallan', 'fallar', 'fallas', 
'fallen', 'falles', 'fallos', 'falsa', 'falso', 'falta', 'falte', 'falto', 'faros', 'favor', 
'fiaba', 'fiaca', 'fiada', 'fiado', 'fichan', 'fifan', 'fifar', 'fifas', 'fifen',
'fifes', 'fijan', 'fijar', 'fijas', 'fijen', 'fijes', 'fijos', 'fines', 'finge', 'fingí', 'fleta', 
'flete', 'floja', 'flojo', 'flota', 'flote', 'floto', 'fluía', 'fluir', 'fluís', 'flujo', 'flúor', 
'fluya', 'fluye', 'fluyo', 'follan', 'follar', 'follas', 'follen', 'folles', 'follón', 'fondo', 
'forja', 'forje', 'forjo', 'forma', 'forme', 'formo', 'forrea', 'forros', 'freía', 'freír', 'freís',
'frena', 'frene',
'freno', 'fuego', 'fuera', 'fuese', 'fuman', 'fumar', 'fumas', 'fumen', 'fumes', 'funda', 'funde', 
'fundí', 'fundo', 'furia', 'furor', 'gafas', 'gaita', 'gajes', 'gajos', 'galán', 'gallea', 'gallee', 
'gallen', 'galleo', 'gallos', 'ganan', 'ganar', 'ganas', 'ganen', 'ganes', 'ganga', 'gansa', 
'ganso', 'garpe', 'garpo', 'gasta', 'gaste', 'gasto',
'gatas', 'gatea', 'gatee', 'gateo', 'gatos', 'gemas', 'gemía', 'gemís', 'gente', 'giran', 'girar', 
'giras', 'giren', 'gires', 'giros', 'gocen', 'goces', 'golea', 'goleo', 'goles', 'golfa', 'golfo',
'golpe', 'gorda', 'gordo', 'gorras', 'gorrea', 'gorreo', 'gorros', 'gotas', 'gotea', 'goteo', 
'gozan', 'gozar', 'gozas', 'graba', 'grabe', 'grado', 'grano', 'grave', 'grita', 'grite', 'grito', 
'grosa', 'groso', 'grumo',
'guacha', 'guacho', 'guano', 'guapa', 'guapo', 'guata', 'guian', 'guiar', 'guias', 'guien', 'guies', 
'guita', 'gusta', 'guste', 'gusto', 'haber', 'había', 'hábil', 'habla', 'hable', 'hablo', 'habra', 
'habre', 'hacen', 'hacer', 'haces', 'hacia', 'hadas', 'hagan', 'hagas', 'hallan', 'hallar', 'hallas', 'hallen', 'halles', 'harta', 'harte', 'harto',
'hasta', 'hechas', 'hechos', 'helar', 'helas', 'herir', 'herís', 'hiero', 'hijas', 'hijos', 'hilar', 
'hilos', 'honor', 'honra', 'honre', 'honro', 'horas', 'horno', 'hoyos', 'huevo', 'huyan', 'huyas', 
'huyen', 'huyes', 'imita', 'imite', 'imito', 'impar', 'índex', 'india', 'indio', 'irían', 'irías', 
'irriga', 'irrigo', 'irrita', 'irrite', 'irrito', 'items', 'itera', 'itere', 'itero', 'jarras',
'jarrón', 'jodan', 'jodas', 'joden', 'joder', 'jodes', 'jodía', 'jodió', 'jodon', 'joven', 'juana', 
'judas', 'judía', 'judío', 'juega', 'juego', 'jugar', 'jugas', 'jugue', 'julia', 'julio', 'justa',    
'justo', 'juzga', 'juzgolabra', 'labre', 'labro', 'lacra', 'lados', 'larga', 'largo', 'latía', 'latió',
'latir', 'latís', 'laucha', 'lavan', 'lavar', 'lavas', 'laven', 'laves', 'legal', 'leída', 'leido', 
'lejos', 'lenta', 'lente', 'lento', 'letal', 'letra', 'liana', 'libre', 'licua', 'líder', 'lidia', 
'lidie', 'lidio', 'lijan', 'lijar', 'lijas', 'lijen', 'lijes', 'liman', 'limar', 'limas', 'limbo',
'limes', 'lincha', 'linda', 'lindo',
'linea', 'lisos', 'lista', 'liste', 'listo', 'litro', 'locas', 'locos', 'logos', 'logra', 'logre', 
'logro', 'lolas', 'lonja', 'lotee', 'lotes', 'lucen', 'luces', 'luchan', 'luchar', 'luchas', 
'luchen', 'luches', 'lucio', 'lucir', 'lucís', 'lucra', 'lucre', 'lucro', 'luego', 'lugar', 'lujan', 
'luzca', 'luzco',
'llaman', 'llamar', 'llamas', 'llamen', 'llames', 'llanos', 'llanta', 'llaves', 'llegan', 'llegar', 
'llegas', 'llegue', 'llenan', 'llenar', 'llenas', 'llenen', 'llenes', 'llenos', 'lllevan', 'llevar',
'llevas', 'lleven', 'lleves', 'lloran', 'llorar', 'lloras', 'lloren', 'llores', 'llorón', 'llovía', 
'llovió', 'llueva', 'llueve', 'lluvia',
'machos', 'magia', 'maman', 'mamar', 'mamen', 'mames', 'mancha', 'manco', 'manda', 'mande', 'mando', 
'mango', 'manos', 'mansa', 'manso', 'manto', 'mañas', 'mapas', 'marco', 'marea', 'mareo', 'mares', 
'marrón', 'marzo', 'masas', 'masca', 'matan', 'matar', 'maten', ' mates', 'matón', 'mayor', 'mecen',
'mecer', 'meces', 'mechas', 'media', 'medie', 'medio', 'medir', 'medís', 'menor', 'menos', 'meros', 
'metal', 'metan',
'metas', 'meten', 'meter', 'metes', 'metía', 'metió', 'miedo', 'migra', 'migre', 'migro', 'miles',
'miman', 'mimar', 'mimas', 'mimen', 'mimes', 'mimos', 'minas', 'miñón', 'miran', 'mirar', 'miras', 
'miren', 'mires', 'misil', 'misma', 'mismo', 'mitos', 'mixta', 'mixto', 'modal', 'modas', 'módem', 
'modos', 'mojan', 'mojar', 'mojas', 'mojen', 'mojón', 'momia', 'monas', 'monja', 'monje', 'monos', 
'mordí', 'morsa',
'mosca', 'motor', 'motos', 'mover', 'moves', 'movía', 'móvil', 'movió', 'mozas', 'mozos', 'mudan', 
'mudar', 'mudas', 'muden', 'mudos', 'mueca', 'muela', 'muera', 'muere', 'muero', 'mueva', 'mueve',
'muevo', 'mufas', 'mugre', 'multa', 'multe', 'multo', 'murió', 'muros', 'mutua', 'mutuo',
'nabos', 'nacen', 'nacer', 'naces', 'nacía', 'nació', 'nadan', 'nadar', 'nadas', 'naden', 'nades', 
'nadie', 'naipe', 'nalga', 'naval', 'naves', 'nazca', 'nazco', 'negra', 'negro', 'nenas', 'nenes', 
'nichos', 'nidos', 'niega', 'niego', 'nieta', 'nieto', 'nieva', 'nieve', 'noches', 'nodos', 'norma', 
'norte', 'notan', 'notar', 'notas', 'noten', 'notes', 'novia', 'novio', 'nubla', 'nuble', 'nudos', 
'nueva', 'nueve',
'nuevo', 'nulas', 'nulos', 'nutra', 'nutre', 'nutrí', 'obran', 'obrar', 'obren', 'obten', 'obvia', 
'obvio', 'ocios', 'ocupa', 'ocupe', 'ocupo', 'ocurra', 'ocurre', 'ocurrí', 'ocurro', 'ocurrió', 
'odian', 'odiar', 'odias', 'odien', 'odios', 'oídos', 'oigan', 'oigas', 'oimos', 'oirán', 'oirás', 
'oiría', 'oiste', 'ojala', 'ojean', 'ojear', 'ojera', 'ojete', 'oliva', 'omiso', 'omita', 'omite', 
'omití', 'omito', 'otras', 'otros', 'ovnis',
'pacos', 'padre', 'pagan', 'pagar', 'pagas', 'pajas', 'pajea', 'pajeo', 'panal', 'pancha', 'pancho', 
'parche', 'paseo', 'pasos', 'pechos', 'pedal', 'pedir', 'pedís', 'pedos', 'pegan', 'pegar', 'pelea', 
'penal', 'pesos', 'petes', 'pijas', 'piñas', 'piñón', 'piojo', 'piola', 'pisos', 'pitos', 'plano', 
'plata', 'plato', 'playa', 'plaza', 'plazo', 'pleno', 'pobre', 'pocas', 'pocos', 'poder', 'podes', 
'podía', 'podio', 'podrá', 'poema', 'poeta',
'ponen', 'poner', 'pones', 'ponga', 'pongo', 'ponía', 'presa', 'preso', ' prima', 'pudor', 'pudra', 
'pudre', 'pueda', 'puede', 'puedo', 'pulpo', 'pulsa', 'pulso', 'punta', 'punto', 'pupos', 'putas',
'putea', 'putee', 'putos', 'queda', 'quede', 'quedo', 'queja', 'queje', 'quejo', 'quema', 'queme', 
'quemo', 'quizá', 'rabas', 'rabia', 'rabos,rachas', 'radio', 'rajan', 'rajar', 'rajas', 'rajen', 
'rajes', 'ramas', 'ramos', 'rampa', 'ranas', 'rancho', 'rango', 'rapan', 'rapar', 'rapas', 'raras', 
'raros', 'ratas', 'ratón', 'ratos', 'rayan', 'rayar', 'rayas', 'rayón',
'razas', 'recta', 'recto', 'regar', 'regas', 'regio', 'regla', 'reina', 'reino', 'relax', 'reman', 
'remar', 'remas', 'remen', 'remes', 'remos', 'renta', 'rente', 'resta', 'retan', 'retar', 'retas', 
'reten', 'retes', 'retos', 'revés', 'reyes', 'rezan', 'rezar', 'rezas', 'rezos', 'ricas', 'rricos', 
'riega', 'rigor', 'rinda', 'rinde', 'risas', 'roban', 'robar', 'robas', 'roben', 'robes', 'robos', 
'rocas', 'rompa', 'rompe', 'rompí', 'rompo', 'ronca', 'ropas', 'rosas', 'rosca', 'rotar', 'rotas', 
'roten', 'rotor', 'rotos', 'rueda', 'ruega', 'ruego', 'ruido', 'ruina', 'rulos', 'rumbo', 'rumor',
'saben', 'saber', 'sabes', 'sabia', 'sabio', 'sabor', 'sabrá', 'sacan', 'sacar', 'sacas', 'sacos', 
'salas', 'saldo', 'sales', 'salga', 'salgo', 'salia', 'salio', 'salir', 'salís', 'salsa', 'salta', 
'salte', 'salto', 'salud', 'salva', 'salve', 'salvo', 'sanan', 'sanar', 'sanas', 'santa', 'santo', 
'sapos', 'sardo', 'savia', 'secas', 'secos', 'sedal', 'sedan', 'seguí', 'según', 'senos', 'sentí', 
'señal', 'señas', 'señor', 'sepan', 'sepas',
'seria', 'serie', 'serio', 'serví', 'sexos', 'sexta', 'sexto', 'sidra', 'igan', 'sigas', 'sigla', 'siglo', 
'signo', 'sillas', 'sillón', 'simio', 'sirva', 'sirve', 'sitio', 'situa', 'situe', 'situo', 'sobra', 
'sobre', 'solas', 'solos', 'somos', 'soñar', 'sopan', 'sopar', 'sopas', 'sopea', 'sopee', 'sopen', 
'sopeo', 'sopes', 'sopié', 'sopla', 'sople', 'soplo', 'sorbo', 'sorda', 'sordo', 'suave', 'suban', 
'subas', 'suben', 'subes', 'subía', 'subió', 'subir', 'subís',
'sucia', 'sucio', 'sudan', 'sudar', 'sudas', 'suden', 'sudor', 'suela', 'suele', 'suelo', 'suena', 'suene', 
'sueno', 'sueña', 'sueñe', 'sueño', 'ufra', 'sufre', 'sufrí', 'sufro', 'suman', 'sumar', 'sumas', 
'sumen', 'super', 'surco', 'surdo', 'surtí', 'susto', 'suyos', 'tabla', 'tachos', 'tacos', 'tacto', 
'tajos', 'talan', 'talar', 'talas', 'talco,talen', 'tallas', 'taller', 'tanga', 'tanta', 'tanto', 
'tapiz', 'tapón', 'tarros', 'tazas', 'tazón', 'teman', 'temas', 'temen',
'temer', 'temes', 'temía', 'temió', 'temor', 'tener', 'tenes', 'tenga', 'tengo', 'tenia', 'teñía', 'teñir', 
'termo', 'terror', 'tetas', 'tetón', 'tetra,texto', 'tiene', 'tierra', 'tiran', 'tirar', 'tiras', 
'tiren', 'tires', 'tiros', 'toalla', 'todas', 'todos', 'toldo', 'tonta', 'tonto', 'torran', 'torrar', 
'tosan', 'tosas', 'traen', 'traer', 'traes', 'traga', 'trago', 'traía', 'traje', 'trajo', 'trama', 
'tramo', 'trapo', 'trata', 'trate', 'trato', 'traza',
'trazo', 'trece', 'trecha', 'trecho', 'trepa', 'trepo', 'tríos', 'tripa', 'trola', 'trucha', 'trucho', 
'truco', 'tubos', 'tucán', 'turno', 'turras', 'turrón', 'turros', 'tutea', 'tutee', 'tuteo', 'tutor', 
'tuyas', 'tuyos', 'vacas', 'vacia', 'vacía', 'vacie', 'vacié', 'vacio', 'vacío', 'vagón', 'vagos', 
'vaina', 'valen', 'valer', 'vales', 'valga', 'valgo', 'valía', 'valió', 'vallan', 'vallas', 'valles', 
'valor', 'valua', 'valuo',
'vamos', 'vasco', 'vasos', 'vayan', 'vayas', 'veces', 'veían', 'veías', 'vejes', 'vejez', 'velan', 'velar', 
'velas', 'vemos', 'vence', 'vencí', 'venga', 'vengo', 'venir', 'venís', 'venta', 'venus', 'veras', 
'verga', 'vetan', 'viaja', 'viaje', 'viajo', 'vibra', 'vibro', 'vidas', 'vídeo', 'vieja', 'viejo', 
'viene', 'viera', 'viese', 'vigor', 'viñas', 'viola', 'viole', 'violo', 'vista', 'viste', 'visto', 
'viuda', 'viudo',
'vivan', 'vivas', 'viven', 'vives', 'vivió', 'vivir', 'vivís', 'vivos', 'volea', 'voleo', 'volví', 'votan', 
'votar', 'votas', 'voten', 'votes', 'vudús', 'vuela', 'vuelo', 'ubica', 'ubico', 'ungen', 'unges', 
'ungía', 'única', 'único', 'unida', 'unido', 'unión', 'unirá', 'uniré', 'usaba', 'usada', 'usado', 
'usara', 'usare', 'usase', 'usted', 'usual', 'xenón', 'yacen', 'yacer', 'yaces', 
'yacía', 'yuyal', 'yuyos', 'zafan', 'zafar', 'zafas', 'zafen', 'zafes', 'zombi', 'zorras', 'aquello', 
'aquella', 'aquellos', 'aquellas', 'para', 'dijo', 'pidió', 'cuando', 'años', 'asesinó', 'fotos', 
'durante', 'final', 'mejor', 'todo', 'este', 'discutirse', 'quiere', 'empiece', 'nervioso', 
'nerviosos', 'fantasma', 'Ruta', 'Bailando', 'primeros', 'apostar', 'arena', 'casi', 'país', 
'Aumentaron', 'aumentaron', 'positivo', 'negativo', '2009', '2010', '2011', '2012', '2013', '2014', 
'2015', '2016', 'fiesta', 'pleno', 'podemos', 'momento', 'hubo', 'Por', 'por', 'dolido', 'tener', 'algunas', 
'algunos', 'papa', 'víctima', 'Habló', 'habló', 'masiva', 'masivo', 'creí', 'Creí', 'exterior', 'mercado', 
'médicos', 'Médicos', 'vacías', 'suban', 'calificación', 'morir', 'qué', 'diferencias', 'entender', 
'entre', 'realidad', 'aumentada', 'claves', 'gatos', 'mueven', 'apoyar', 'porteño', 'porteña', 'dije',
'cotizará', 'abre', 'representa', 'derecha', 'preso', 'variantes', 'cerró', 'estar', 'debía', 'tenía',
'salidas', 'transitorias', 'pero', 'lunes', 'próximo', 'supuestamente', 'controlaron', 'pide', 
'defiende', 'difícil', 'más', 'salir', 'mentira', 'Las', 'las', 'foto', 'conmovedora', 'rescatista', 
'bebé', 'protegí', 'perpetua', 'cadena', 'condenaron', 'como', 'estuviera', 'impulsa', 'porteños', 
'procesamiento', 'límites', 'partido', 'vivo', 'puso', 'últimos', 'llorar', 'contó', 'divorció', 'hizo', 
'vienen', 'cuales', 'ignoró', 'disculpas', 'bañándose', 'video', 'calles', 'Calles', 'repatriar', 
'tiene', 'aparición', 'Nuevo', 'más', 'Ordenaron', 'indagar', 'cite', 'Causa', 'dilate', 'sobre', 
'cierran', 'sospechoso', 'sería', 'aparten', 'hablás', 'buscar', 'contra', 'envió', 'efectos', 
'casa', 'Giro', 'política', 'Barrios', 'realiza', 'pondrá', 'venta', 'formalizará', 'Video', 'vuelve', 
'vivir', 'viaje', 'usó', 'urgente', 'trasladado', 'tomar', 'tarda', 'semana', 'reunión', 'redescrubre', 
'recibe', 'presionaron', 'podemos', 'pleno', 'pedido', 'pasar', 'padres', 'padre', 'negó', 'mérito', 
'murió', 'muerte', 'mostrar', 'momento', 'ministro', 'meses', 'meca', 'local', 'llama', 'joven', 
'investigar', 'del', 'con', 'Asesinó', 'Día', 'Fotos', 'Los', 'perro', 'Perro', 'Qué', 'Que', 'tras',
'Tras', 'Acceso', 'acceso', 'Admitió', 'admitió', 'ante', 'Ante', 'Anticipan', 'anticipan', 'Asegurar',
'asegurar', 'Aseguran', 'aseguran', 'Atrevida', 'atrevida', 'avanza', 'Avanza', 'Cara', 'cara', 'chicas',
'Chicas', 'chicos', 'Chicos', 'uno', 'dos', 'tres', 'cuatro', 'cinco', 'seis', 'siete', 'ocho', 'nueve',
'diez', 'comenzó', 'Comenzó', 'confiaba', 'Confiaba', 'constató', 'Constató', 'continúa', 'Continúa',
'cuarta', 'Cuarta', 'Cómo', 'cómo', 'declarar', 'Declarar', 'dedicaba', 'Dedicaba', 'embarazar',
'Embarazar', 'empezó', 'Empezó', 'entran', 'Entran', 'escoger', 'Escoger', 'está', 'Está', 'vivo', 'Vivo',
'VIVO', 'cero', 'Gran', 'gran', 'ganó', 'Ganó', 'copa', 'Copa', 'baja', 'Baja', 'para', 'Para', 'busca',
'Busca', 'Una', 'presenta', 'Presenta', 'tuvo', 'Tuvo', 'http', 'HTTP', 'Http', 'acceder', 'Acceder',
'vida', 'Vida', 'colocó', 'Colocó', 'así', 'Así', 'fuga', 'Fuga', 'secuestro', 'Secuestro', 'murió',
'Murió', 'descubren', 'Descubren', 'hombre', 'Hombre', 'hallaron', 'Hallaron', 'hot', 'Hot', 'será',
'Será', 'error', 'Error', 'mundo', 'Mundo', 'dio', 'Dio', 'dió', 'Dió', 'mil', 'Mil', 'mar', 'Mar',
'san', 'San', 'podría', 'Podría', 'base', 'Base', 'moved', 'Moved', 'permanently', 'Permanently', 'del',
'Del', 'con', 'Con', 'debe', 'Debe', 'dejó', 'Dejó', 'cayó', 'Cayó', 'venció', 'Venció', 'san', 'San',
'vino', 'Vino', 'junto', 'Junto', 'función', 'Función', 'llegó', 'Llegó', 'recibió', 'Recibió',
'quieren', 'Quieren', 'quiero', 'Quiero', 'hace', 'Hace', 'dio', 'Dio', 'piden', 'Piden', 'comprar',
'Comprar', 'será', 'Será', 'nada', 'Nada', 'mató', 'Mató', 'error', 'Error', 'hallaron', 'Hallaron',
'así', 'Así', 'bien', 'Bien', 'quedó', 'Quedó', 'cada', 'Cada', 'this', 'This', 'nos', 'Nos', 'junto',
'Junto', 'después', 'Después', 'ahora', 'Ahora', 'mal', 'Mal', 'perdió', 'Perdió', 'peor', 'Peor',
'mirá', 'Mirá', 'dice', 'Dice', 'días', 'Días', 'venía', 'Venía', 'juntos', 'Juntos', 'partir', 'Partir',
'confirmó', 'Confirmó', 'empató', 'Empató', 'tendrá', 'Tendrá', 'cuánto', 'Cuánto', 'superó', 'Superó', 
'fueron', 'Fueron', 'goleó', 'Goleó', 'compartir', 'Compartir', 'pronto', 'Pronto', 'habrá', 'Habrá',
'obtener', 'Obtener', 'nuevos', 'Nuevos', 'también', 'También', 'denunció', 'Denunció', 'aumentar',
'Aumentar', 'les', 'Les', 'sobre', 'Sobre', 'recibir', 'Recibir', 'todo', 'Todo', 'mejorar', 'Mejorar',
'más', 'Más', 'porque', 'Porque', 'están', 'Están', 'tienen', 'Tienen', 'marcar', 'Marcar', 'nos', 'Nos',
'links', 'Links', 'destacados', 'Destacados'
]);