<?php
namespace Grav\Plugin;

use Grav\Common\Grav;
use Grav\Common\Plugin;
use RocketTheme\Toolbox\Event\Event;
use MtHaml\Environment;
use MtHaml\Support\Twig\Loader;


/**
 * Class HAMLPlugin
 * @package Grav\Plugin
 */
class HAMLPlugin extends Plugin
{
    /**
     * @return array
     *
     * The getSubscribedEvents() gives the core a list of events
     *     that the plugin wants to listen to. The key of each
     *     array section is the event that the plugin listens to
     *     and the value (in the form of an array) contains the
     *     callable (or function) as well as the priority. The
     *     higher the number the higher the priority.
     */
    public static function getSubscribedEvents()
    {
        return [
            'onPluginsInitialized' => ['onPluginsInitialized', 0]
        ];
    }

    /**
     * Initialize the plugin
     */
    public function onPluginsInitialized()
    {
        require_once __DIR__ . '/vendor/autoload.php';

        // Don't proceed if we are in the admin plugin
        if ($this->isAdmin()) {
            return;
        }

        // Enable the main event we are interested in
        $this->enable([
            'onTwigInitialized' => ['onTwigInitialized', 0],
            'onTwigExtensions' => ['onTwigExtensions', 0]
        ]);
    }

    /**
     * Do some work for this event, full details of events can be found
     * on the learn site: http://learn.getgrav.org/plugins/event-hooks
     *
     * @param Event $e
     */
    public function onTwigInitialized(Event $e)
    {
        /* @var $twig \Grav\Common\Twig\Twig */
        $twig = $this->grav['twig'];
        $haml = new Environment('twig', array('enable_escaper' => false));
        // Use a custom loader, whose responsibility is to convert HAML templates
        // to Twig syntax, before handing them out to Twig:
        $hamlLoader = new GravHamlLoader($haml, $twig->twig()->getLoader());
        $twig->twig()->setLoader($hamlLoader);
        // Get a variable from the plugin configuration
        //$text = $this->grav['config']->get('plugins.haml.text_var');

        // Get the current raw content
        //$content = $e['page']->getRawContent();


        // Prepend the output with the custom text and set back on the page
        //$e['page']->setRawContent($text . "\n\n" . $content);
    }

    public function onTwigExtensions(Event $e)
    {
        $this->grav['twig']->twig()->addExtension(new \MtHaml\Support\Twig\Extension());
    }
}
