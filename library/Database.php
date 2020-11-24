<?php

abstract class Database
{

    private static $instance = null;
    private static $db_name = "agriandco";
    /**
     * Retourne une connexion à la base de donnée
     * 
     * @return PDO
     */


    public static function getPdo(): PDO
    {
        $dump = self::isDatabaseExist();
        if ($dump === false) {
            self::createDatabase();
        }else{
            if (self::$instance === null) {

                self::$instance = new PDO("mysql:host=localhost;dbname=" . self::$db_name . ";charset=utf8", 'root', 'root', [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]);
            }
    
            return self::$instance;
        }
        
    }

    /**
     * Fonction pour savoir si la base de données existe
     * @param $pdo
     * @return bool $selectBDD
     */
    public static function isDatabaseExist(): bool
    {
        $connexionBDD = mysqli_connect('localhost', 'root', 'root');
        $selectBDD = mysqli_select_db($connexionBDD, self::$db_name);

        return $selectBDD;
    }

    /**
     * Fonction pour créer la base de données si elle n'existe pas
     */
    public static function createDatabase()
    {

        $pdo = self::getPdo();
        if (self::isDatabaseExist($pdo) != true) {
            $query = "CREATE DATABASE IF NOT EXISTS" . self::$db_name . "DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";
            $pdo->prepare($query)->execute();
            self::createTables();
        }
    }

    /**
     * Fonction pour créer les tables de la base de données si elles n'existent pas
     */
    public static function createTables()
    {

        $pdo = self::getPdo();
        $article_table = "CREATE TABLE IF NOT EXISTS `articles` (
            `id` int(11) NOT NULL,
            `title` varchar(255) NOT NULL,
            `title_href` varchar(255) NOT NULL,
            `content` text NOT NULL,
            `img` varchar(255) NOT NULL,
            `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        $pdo->prepare($article_table)->execute();

        $product_table = "CREATE TABLE IF NOT EXISTS `produits` (
            `id` int(11) NOT NULL,
            `title` varchar(255) NOT NULL,
            `title_href` varchar(255) NOT NULL,
            `content` text NOT NULL,
            `story` text NOT NULL,
            `saison` varchar(255) NOT NULL,
            `type` varchar(255) NOT NULL,
            `stock` int(11) NOT NULL,
            `img` varchar(255) NOT NULL,
            `date_appro` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        $pdo->prepare($product_table)->execute();


        $users_table = "CREATE TABLE IF NOT EXISTS `users` (
            `id` int(11) NOT NULL,
            `role` varchar(255) NOT NULL DEFAULT 'user',
            `name` varchar(255) NOT NULL,
            `firstname` varchar(255) NOT NULL,
            `mail` varchar(255) NOT NULL,
            `password` varchar(255) NOT NULL,
            `fav` text,
            `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        $pdo->prepare($users_table)->execute();
    }
}
