<?php
/**
 * Plugin Webcode: Show webcode (Css, HTML) in a iframe
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Nicolas GERARD
 */

// must be run within Dokuwiki
if (!defined('DOKU_INC')) die();

/**
 * All DokuWiki plugins to extend the parser/rendering mechanism
 * need to inherit from this class
 * 
 * Format
 * 
 * syntax_plugin_PluginName_PluginComponent
 */
class syntax_plugin_codeblock_unit extends DokuWiki_Syntax_Plugin
{


    /*
     * What is the type of this plugin ?
     * This a plugin categorization
     * This is only important for other plugin
     * See @getAllowedTypes
     */
    public function getType()
    {
        return 'container';
    }


    // Sort order in which the plugin are applied
    public function getSort()
    {
        return 168;
    }
    
    function getAllowedTypes() { return array('container', 'formatting', 'substition', 'protected', 'disabled', 'paragraphs'); }
    
    /**
     * Handle the node 
     * @return string
     * See
     * https://www.dokuwiki.org/devel:syntax_plugins#ptype
     */
    function getPType(){ return 'block';}

    // This where the addEntryPattern must bed defined
    public function connectTo($mode)
    {
        // This define the DOKU_LEXER_ENTER state
        $this->Lexer->addEntryPattern('<unit.*?>(?=.*?</unit>)', $mode, 'plugin_codeblock_' . $this->getPluginComponent());
    }

    public function postConnect()
    {
        // We define the DOKU_LEXER_EXIT state
        $this->Lexer->addExitPattern('</unit>', 'plugin_codeblock_' . $this->getPluginComponent());
    }


    /**
     * Handle the match
     * You get the match for each pattern in the $match variable
     * $state says if it's an entry, exit or match pattern
     *
     * This is an instruction block and is cached apart from the rendering output
     * There is two caches levels
     * This cache may be suppressed with the url parameters ?purge=true
     */
    public function handle($match, $state, $pos, Doku_Handler $handler)
    {
        switch ($state) {

            case DOKU_LEXER_ENTER :

                // Cache the values
                return array($state);

            case DOKU_LEXER_UNMATCHED :

                // We don't want to process the content
                // We call then the lexer to go further
                // 
                // The below authorized plugin are given in the function 
                // getAllowedTypes
                $handler->_addCall('cdata', array($match), $pos, null);
                break;

            case DOKU_LEXER_EXIT:

                // Cache the values
                return array($state);

        }

    }

    /**
     * Create output
     * The rendering process
     */
    public function render($mode, Doku_Renderer $renderer, $data)
    {
        // The $data variable comes from the handle() function
        //
        // $mode = 'xhtml' means that we output html
        // There is other mode such as metadata, odt 
        if ($mode == 'xhtml') {

            $state = $data[0];
            // No Unmatched because it's handled in the handle function
            switch ($state) {

                case DOKU_LEXER_ENTER :
                    $renderer->doc .= '<div class="unit">';
                    break;

                case DOKU_LEXER_EXIT :
                    $renderer->doc .= '</div>';
                    break;
            }

            return true;
        }
        return false;
    }

}
