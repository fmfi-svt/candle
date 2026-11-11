<?php

/** Native PHP sessions with application-created directories on an optional shared volume. */
class CandleSessionStorage extends sfSessionStorage
{
    public function initialize($options = null)
    {
        $sessionRoot = getenv('CANDLE_SESSION_DIR');
        if ($sessionRoot) {
            $directory = rtrim($sessionRoot, '/').'/default';
            self::ensureDirectory($directory);
            ini_set('session.save_handler', 'files');
            session_save_path($directory);
        }
        parent::initialize($options);
    }

    public static function ensureDirectory($directory)
    {
        // Another replica may create the directory between is_dir() and mkdir().
        if (!is_dir($directory) && !@mkdir($directory, 0700, true) && !is_dir($directory)) {
            throw new sfStorageException('Cannot create session directory: '.$directory);
        }
        if (!is_writable($directory)) {
            throw new sfStorageException('Session directory is not writable: '.$directory);
        }
    }
}
