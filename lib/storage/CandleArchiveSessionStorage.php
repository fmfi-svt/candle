<?php

/** Anonymous file sessions, separate from normal login sessions and other archives. */
class CandleArchiveSessionStorage extends sfSessionStorage
{
    public function initialize($options = null)
    {
        $request = sfContext::getInstance()->getRequest();
        $cookiePath = rtrim($request->getPathInfoPrefix(), '/').'/'.CandleArchive::getPrefix();
        $scope = sha1(sfConfig::get('sf_root_dir').'|'.sfConfig::get('sf_app').'|'.$cookiePath);
        $sessionRoot = getenv('CANDLE_SESSION_DIR');
        $directory = $sessionRoot
            ? rtrim($sessionRoot, '/').'/archives/candle-archive-'.$scope
            : sys_get_temp_dir().'/candle-archive-'.$scope;
        CandleSessionStorage::ensureDirectory($directory);

        // Never inherit database storage or accept a session ID from request parameters.
        ini_set('session.save_handler', 'files');
        session_save_path($directory);
        ini_set('session.use_cookies', '1');
        ini_set('session.use_only_cookies', '1');
        ini_set('session.use_trans_sid', '0');
        ini_set('session.use_strict_mode', '1');
        // Custom save paths are not necessarily covered by the host's session cleanup.
        ini_set('session.gc_probability', '1');
        ini_set('session.gc_divisor', '100');

        parent::initialize(array_merge((array) $options, array(
            'session_name' => 'candle_archive_'.$scope,
            'session_id' => null,
            'auto_start' => true,
            'session_cookie_path' => $cookiePath,
            'session_cookie_domain' => '',
            'session_cookie_httponly' => true,
            'session_cookie_secure' => $request->isSecure(),
        )));
    }
}
