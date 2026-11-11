<?php

/** Request-local archive selection. Never changes the default database config. */
class CandleArchive
{
    public static function initialize(sfEventDispatcher $dispatcher)
    {
        sfConfig::set('app_archive_prefix', null);
        if (!isset($_SERVER['REQUEST_URI'])) {
            return; // Console tasks have no archive context.
        }

        // Use Symfony's path handling, including front controllers and subdirectories.
        $request = new sfWebRequest($dispatcher);
        $segments = explode('/', ltrim($request->getPathInfo(), '/'));
        $prefix = rawurldecode($segments[0]);
        if ($prefix === 'beta') {
            sfConfig::set('app_archive_prefix', $prefix);
        }
        elseif (preg_match('/\A([0-9]{4})-([0-9]{4})-(zima|leto)\z/', $prefix, $matches)) {
            if ((int) $matches[2] !== (int) $matches[1] + 1) {
                self::notFound();
            }
            sfConfig::set('app_archive_prefix', $prefix);
        }
        elseif (preg_match('/\A[0-9]{4}-/', $prefix)) {
            self::notFound();
        }

        if (self::isActive()) {
            sfConfig::set('sf_factory_storage', 'CandleArchiveSessionStorage');
            // No shared partial/view cache may contain current-semester data or controls.
            sfConfig::set('sf_cache', false);
        }
    }

    public static function isActive()
    {
        return null !== self::getPrefix();
    }

    public static function getPrefix()
    {
        return sfConfig::get('app_archive_prefix');
    }

    public static function getDatabaseName()
    {
        return 'candle_'.str_replace('-', '_', self::getPrefix());
    }

    /** Called at runtime from the compiled database configuration, not at cache-build time. */
    public static function createDatabase($class, $parameters)
    {
        if (!self::isActive()) {
            return new $class($parameters);
        }

        $dsn = $parameters['dsn'];
        if (strpos($dsn, 'mysql:') !== 0 || strpos($dsn, '://') !== false) {
            throw new sfConfigurationException('Archive databases require a PDO MySQL DSN.');
        }
        $databaseName = self::getDatabaseName();
        $dsn = preg_replace('/(^|;)\s*dbname\s*=[^;]*/i', '$1dbname='.$databaseName, substr($dsn, 6), -1, $count);
        if (!$count) {
            $dsn = rtrim($dsn, ';').';dbname='.$databaseName;
        }
        $parameters['dsn'] = 'mysql:'.$dsn;

        // Fail before routing can resolve Doctrine objects. Never fall back to the default DB.
        try {
            $database = new $class($parameters);
            $database->getConnection();
        }
        catch (Exception $e) {
            // This Doctrine version discards PDO errorInfo on connection failures.
            // Restricted MySQL accounts may receive 1044 even for a nonexistent DB.
            // Both mean the archive is unavailable; authentication (1045) and network
            // errors must still surface as server errors.
            if (($e instanceof PDOException && isset($e->errorInfo[1])
                    && in_array((int) $e->errorInfo[1], array(1044, 1049), true))
                || preg_match('/SQLSTATE\[[^\]]+\] \[(?:1044|1049)\]/', $e->getMessage())) {
                self::notFound();
            }
            throw $e;
        }
        return $database;
    }

    /** Bootstrap errors occur before Symfony has a response or an error controller. */
    public static function notFound()
    {
        header('HTTP/1.1 404 Not Found');
        header('Content-Type: text/plain; charset=utf-8');
        echo "Not Found\n";
        exit;
    }
}
