<?php
/**
 * Hooks
 *
 * Allow install or uninstall WooCommerce Git Hooks.
 */
namespace WooCommerce\GitHooks;

use Exception;
use Composer\Script\Event;

/**
 * Hooks class.
 */
class Hooks
{

    /**
     * Hooks.
     *
     * @var string[]
     */
    protected static $hooks = [
        'pre-commit',
    ];

    /**
     * Get current directory.
     *
     * @return string
     */
    protected static function getProjectDir(Event $event)
    {
        return $event->getComposer()->getConfig()->get('vendor-dir') . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;
    }

    /**
     * Pre hooks.
     *
     * @param  Event $event
     * @return bool
     */
    public static function preHooks(Event $event)
    {

        $directory = self::getProjectDir($event);
        $io        = $event->getIO();
        $removed   = false;

        foreach (self::$hooks as $hookName) {
            $hook = sprintf('%1$s.git%3$shooks%3$s%2$s', $directory, $hookName, DIRECTORY_SEPARATOR);

            if (file_exists($hook)) {
                unlink($hook);
                $removed = true;
            }
        }

        if ($removed) {
            $io->write('<info>Hooks removed!</info>');
        }

        return true;
    }

    /**
     * Post hooks.
     *
     * @param  Event $event
     * @return bool
     */
    public static function postHooks(Event $event)
    {
        $directory = self::getProjectDir($event);

        if (!file_exists($directory . '.git')) {
            throw new Exception(sprintf('Oops! Local Git repository not found.'));
        }

        $io = $event->getIO();

        foreach (self::$hooks as $hookName) {
            $hook     = sprintf('%1$s.git%3$shooks%3$s%2$s', $directory, $hookName, DIRECTORY_SEPARATOR);
            $original = sprintf('%1$s%3$s..%3$shooks%3$s%2$s', __DIR__, $hookName, DIRECTORY_SEPARATOR);

            copy($original, $hook);
            chmod($hook, 0777);
        }

        $io->write('<info>Hooks added!</info>');

        return true;
    }
}
