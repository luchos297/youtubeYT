<?php
use Phinx\Seed\AbstractSeed;

/**
 * Canales seed.
 */
class CanalesSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     *
     * @return void
     */
    public function run()
    {
        /*$data = [];

        $table = $this->table('canales');
        $table->insert($data)->save();*/

        $this->execute(
            'INSERT INTO canales (id, nombre, codigo, url, type, serverurl, weight, height, tipo, filename, habilitado, creado, modificado)
             VALUES(1, "Radio Ayer", "AYER", "http://190.113.128.139:8080/ayer", "audio/mp4", "", 400, 150, "provinciales", "radios/ayer.jpg", 1, NOW(), NULL),
                (2, "Radio Brava", "BRAVA", "radio", "audio/mp4", "rtmp://190.210.105.6/radiobrava", 400, 150, "provinciales", "radios/brava.jpg", 1, NOW(), NULL),
                (3, "Radio Cordillera", "CORDILLERA", "http://emeefemza.wix.com/1037", "page", "", 990, 550, "provinciales", "radios/cordillera.jpg", 1, NOW(), NULL),
                (4, "Estación del Sol", "ESTACIONDELSOL", "http://70.38.29.199:8064/stream", "audio/aac", "", 400, 150, "provinciales", "radios/delsol.jpg", 1, NOW(), NULL),
                (5, "Radio Fantasía", "FANTASIA", "http://192.99.41.102:5658/;?1465321026922.aac", "audio/aac", "", 400, 150, "provinciales", "radios/fantasia.jpg", 1, NOW(), NULL),
                (6, "Radio La Coope", "LACOOPE", "9996.stream", "audio/mp4", "rtmp://hdstreamhost.com:1935/liveorigin/", 400, 150, "provinciales", "radios/lacoope.jpg", 1, NOW(), NULL),
                (7, "Radio MDZ", "MDZRADIO", "default.stream", "audio/mp4", "rtmp://207.198.106.33/mdzradio/", 400, 150, "provinciales", "radios/mdz.jpg", 1, NOW(), NULL),
                (8, "Radio LV Diez", "LVDIEZ", "http://70.38.29.199:8062/stream", "audio/aac", "", 400, 150, "provinciales", "radios/lvdiez.jpg", 1, NOW(), NULL),
                (9, "Radio La Red", "LAREDMENDOZA", "http://190.113.128.139:8080/lared", "audio/mpeg", "", 400, 150, "provinciales", "radios/lared.jpg", 1, NOW(), NULL),
                (10, "Radio Frontera", "FRONTERA", "http://184.107.141.20:7105/;", "audio/mp4", "", 400, 150, "provinciales", "radios/frontera.jpg", 1, NOW(), NULL),
                (11, "Radio Metropolitana", "METROPOLITANA", "http://200.58.118.108:8806/stream", "audio/aac", "", 400, 150, "provinciales", "radios/metropolitana.jpg", 1, NOW(), NULL),
                (12, "Radio Mitre", "MITREMENDOZA", "http://buecrplb01.cienradios.com.ar/mitremdz.mp3", "audio/mpeg", "", 400, 150, "provinciales", "radios/mitre.jpg", 1, NOW(), NULL),
                (13, "Radio Peluca", "PELUCA", "http://radiopeluca.com/", "page", "", 800, 700, "provinciales", "radios/peluca.jpg", 1, NOW(), NULL),
                (14, "Radio Olivos", "OLIVOS", "http://s25.myradiostream.com:8134/;", "audio/mp3", "", 400, 150, "provinciales", "radios/olivos.jpg", 1, NOW(), NULL),
                (15, "Radio Monte Cristo", "MONTECRISTO", "http://190.113.128.139:8080/montecristo", "audio/mp4", "", 400, 150, "provinciales", "radios/montecristo.jpg", 1, NOW(), NULL),
                (16, "Radio Nihuil", "NIHUIL", "http://190.113.128.139:8080/nihuilfm", "audio/mpeg", "", 400, 150, "provinciales", "radios/nihuil.jpg", 1, NOW(), NULL),
                (17, "Radio 2", "RADIO2", "http://70.38.29.199:8004/stream", "audio/aac", "", 400, 150, "provinciales", "radios/radio2.jpg", 1, NOW(), NULL),
                (18, "Radio UNA", "UNA", "http://190.113.128.139:8080/una", "audio/mp4", "", 400, 150, "provinciales", "radios/una.jpg", 1, NOW(), NULL),
                (19, "Radio A", "RADIOA", "http://streamall.alsolnet.com:443/metropolitana", "audio/aac", "", 400, 150, "provinciales", "radios/radioa.jpg", 1, NOW(), NULL),
                (20, "Radio 1 Alvear", "RADIO1", "http://www.unoalvear.com/", "page", "", 800, 700, "provinciales", "radios/radio1alvear.jpg", 1, NOW(), NULL),
                (21, "Radio Andina", "ANDINA", "http://www.radioandina.com.ar/", "page", "", 600, 540, "provinciales", "radios/andina.jpg", 1, NOW(), NULL),
                (22, "Radium", "RADIUM", "http://cdn.instream.audio:8097/stream", "audio/aac", "", 400, 150, "provinciales", "radios/radium.jpg", 0, NOW(), NULL),
                (23, "Radio Red 101", "RED101", "http://170.75.144.146:9888/;", "audio/aac", "", 400, 150, "provinciales", "radios/red101.jpg", 1, NOW(), NULL),
                (24, "Radio Z", "ZETA", "http://streamingraddios.net:7313/;", "audio/mp3", "", 400, 150, "provinciales", "radios/radioz.jpg", 1, NOW(), NULL),
                (25, "Los 40 Principales", "40PRINCIPALES", "http://4553.live.streamtheworld.com/LOS40_ARGENTINA_SC", "audio/mp4", "", 400, 150, "nacionales", "radios/40principales.jpg", 1, NOW(), NULL),
                (26, "Radio ESPN", "ESPN", "http://edge.espn.cdn.abacast.net/espn-deportesmp3-48", "audio/mpeg", "", 400, 150, "nacionales", "radios/espn1.jpg", 1, NOW(), NULL),
                (27, "Radio La 100", "LA100", "http://buecrplb01.cienradios.com.ar/la100.aac", "audio/aac", "", 400, 150, "nacionales", "radios/la100.jpg", 1, NOW(), NULL),
                (28, "Radio Cadena 3", "CADENA3", "http://sal-se-1.se.cadena3.activecds.telecomcdn.com.ar/am700.mp3", "audio/mpeg", "", 400, 150, "nacionales", "radios/cadena3.jpg", 1, NOW(), NULL),
                (29, "Radio La Red", "LAREDNACIONAL", "http://www.lared.am/", "page", "", 700, 500, "nacionales", "radios/larednacional.jpg", 1, NOW(), NULL),
                (30, "Radio Mitre", "MITRE", "http://buecrplb01.cienradios.com.ar/Mitre790.aac", "audio/aac", "", 400, 150, "nacionales", "radios/mitrenacional.jpg", 1, NOW(), NULL),
                (31, "Radio Mega", "MEGA", "live", "audio/mp4", "rtmp://mega983.stweb.tv:1935/mega983/", 400, 150, "nacionales", "radios/mega.jpg", 1, NOW(), NULL),
                (32, "Radio Metro", "METRO", "http://mp3.metroaudio1.stream.avstreaming.net:7200/metro", "audio/mpeg", "", 400, 150, "nacionales", "radios/metro.jpg", 1, NOW(), NULL),
                (33, "Radio Disney", "DISNEY", "http://www.disneylatino.com/radio/player/arg/widget.html", "disney", "", 420, 235, "nacionales", "radios/radiodisney.jpg", 1, NOW(), NULL),
                (34, "Radio Pop", "POP", "live", "audio/mp4", "rtmp://67.213.214.129/popradio/", 400, 150, "nacionales", "radios/pop.jpg", 1, NOW(), NULL),
                (35, "Radio Nacional", "NACIONAL", "http://icecast01.dcarsat.com.ar:8000/sc_rad1", "audio/mpeg", "", 400, 150, "nacionales", "radios/nacional.jpg", 1, NOW(), NULL),
                (36, "Radio Rock&Pop", "ROCKANDPOP", "http://fmrockandpop.com/", "page", "", 980, 450, "nacionales", "radios/rock&pop.jpg", 1, NOW(), NULL),
                (37, "Radio Vorterix", "VORTERIX", "http://www.vorterix.com/", "page", "", 985, 450, "nacionales", "radios/vorterix.jpg", 1, NOW(), NULL),
                (38, "Canal 9 Mendoza", "9MENDOZA", "http://canal9televida.elsol.com.ar/envivo.html", "page", "", 950, 400, "tv", "tvs/9mendoza.jpg", 1, NOW(), NULL),
                (39, "Canal 7 Mendoza", "7MENDOZA", "https://youtu.be/cZlR_1NXl2Q", "video", "", 650, 380, "tv", "tvs/7mendoza.jpg", 1, NOW(), NULL),
                (40, "Canal Acequia TV", "ACEQUIA", "http://livestream.com/accounts/6450028/events/2857381/player?width=560&height=315&autoPlay=true&mute=false", "video", "", 850, 600, "tv", "tvs/acequiatv.jpg", 1, NOW(), NULL),
                (41, "TV Pública", "TVPUBLICA", "http://www.tvpublica.com.ar/vivo-2/", "page", "", 900, 750, "tv", "tvs/tvpublica.jpg", 1, NOW(), NULL),
                (42, "Canal 9", "CANAL9", "http://d1hgdosjnpxc13.cloudfront.net/player_canal9.html", "video", "", 850, 500, "tv", "tvs/canal9.jpg", 1, NOW(), NULL),
                (43, "Canal 13", "CANAL13", "http://www.eltrecetv.com.ar", "page", "", 850, 450, "tv", "tvs/canal13.jpg", 1, NOW(), NULL),
                (44, "América", "AMERICA", "http://www.americatv.com.ar/vivo", "video", "", 850, 600, "tv", "tvs/america.jpg", 1, NOW(), NULL),
                (45, "Telefe", "TELEFE", "http://www.telefe.com", "iframe", "", 850, 600, "tv", "tvs/telefe.jpg", 1, NOW(), NULL),
                (46, "TyC Sports", "TYCSPORTS", "http://www.tycsportsplay.com/", "page", "", 850, 600, "tv", "tvs/tyc.jpg", 1, NOW(), NULL),
                (47, "FOX", "FOX", "http://www.fox.com/full-episodes", "page", "", 900, 600, "tv", "tvs/foxmedios.jpg", 1, NOW(), NULL)'
        );
    }
}
