<?php
// ini_set('display_errors',E_ALL);

/**
 * Cache Buster
 *
 * This file must be placed in the
 * system/plugins/ folder in your ExpressionEngine installation.
 *
 * @package CacheBuster
 * @version 1.1.1
 * @author Erik Reagan http://erikreagan.com
 * @copyright Copyright (c) 2010 Erik Reagan
 * @see http://erikreagan.com/projects/cache_buster/
 * @license http://creativecommons.org/licenses/by-nd/3.0/ Creative Commons Attribution-No Derivative Works 3.0 Unported
 */

$plugin_info       = array(
   'pi_name'        => 'Cache Buster',
   'pi_version'     => '1.2.0',
   'pi_author'      => 'Erik Reagan',
   'pi_author_url'  => 'http://erikreagan.com',
   'pi_description' => 'Adds a simple cache buster to your flat file references',
   'pi_usage'       => Cache_buster::usage()
   );

class Cache_buster
{

   public $return_data  = "";

   public function Cache_buster()
   {

      if (version_compare(APP_VER, '2', '<'))
      {

         global $REGX, $TMPL;

         $file = ($TMPL->fetch_param('file') != '') ? $TMPL->fetch_param('file') : FALSE ;
         $separator = ($TMPL->fetch_param('separator') != '') ? $TMPL->fetch_param('separator') : '?v=' ;
         $root_path = ($TMPL->fetch_param('root_path') != '') ? $TMPL->fetch_param('root_path') : $_SERVER['DOCUMENT_ROOT'] ;
         $time = filemtime($root_path.$REGX->xss_clean(html_entity_decode($file)));

      } else {

         $this->EE =& get_instance();

         $file = ($this->EE->TMPL->fetch_param('file') != '') ? $this->EE->TMPL->fetch_param('file') : FALSE ;
         $separator = ($this->EE->TMPL->fetch_param('separator') != '') ? $this->EE->TMPL->fetch_param('separator') : '?v=' ;
         $root_path = ($this->EE->TMPL->fetch_param('root_path') != '') ? $this->EE->TMPL->fetch_param('root_path') : $_SERVER['DOCUMENT_ROOT'] ;
         $time = filemtime($root_path.ee('Security/XSS')->clean(html_entity_decode($file)));

      }


      if ($file)
      {
         $return = $file;
         if ($time !== FALSE)
         {
            $return .= $separator . $time;
         }
      }

      $this->return_data = $return;

   }

   /**
    * Plugin Usage
    */

   // This function describes how the plugin is used.
   //  Make sure and use output buffering

   public static function usage()
   {
      ob_start();
?>

Using ExpressionEngine's CSS template provides a nice cache buster string of the most recent time
the template was saved to the database. This is quite handy but still requires EE to process the template.

This plugin will take a file path and use PHP to check the modification time returning a cache busting
string like ExpressionEngine's. This allows you to server flat files from your server without having
ExpressionEngine's template parser run through the code first. It is very simple to use.

There are 2 parameters. One is required and the other is optional.

{exp:cache_buster file="/css/style.css"}

This will return

/css/style.css?v=1266264101

Where "1266264101" is the UNIX timestamp of the last time /css/style.css was saved to the server.

You can change the separator between the file and the timestamp with the use of separator="" in the tag.

{exp:cache_buster file="/css/style.css" separator="?"}

This will return

/css/style.css?1266264101

<?php
      $buffer         = ob_get_contents();

      ob_end_clean();

      return $buffer;
   }
   // END

}


/* End of file pi.cache_buster.php */
/* Location: ./system/plugins/pi.er_cache_buster.php */
