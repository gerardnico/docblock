<?php
/**
 * Action Component
 * Add a button in the edit toolbar
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Nicolas GERARD
 */

// must be run within Dokuwiki
if(!defined('DOKU_INC')) die();

if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'action.php');

class action_plugin_docblock extends DokuWiki_Action_Plugin {

    /**
     * register the event handlers
     *
     * @author Nicolas GERARD
     */
    function register(Doku_Event_Handler $controller){
        $controller->register_hook('TOOLBAR_DEFINE', 'AFTER', $this, 'handle_toolbar', array ());
    }

    function handle_toolbar(&$event, $param) {
        $docBlockShortcutKey = $this->getConf('CodeBlockShortCutKey');

        $event->data[] = array(
            'type'   => 'format',
            'title'  => $this->getLang('DocBlockButtonTitle').' ('.$this->getLang('AccessKey').': '.$docBlockShortcutKey.')',
            'icon'   => '../../plugins/docblock/images/codeblock.png',
            'open'   => '<units name="Default">\n\t<unit name="default">\n\tt<code>',
            'close'  => '\n\tt</code>\n\tt<console>\n\tt</console></unit></units>\n',
            'key'    => $docBlockShortcutKey
        );


    }

}

