<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * PHP version 5
 *
 * @category  Tinycarts
 * @package   Tinycarts_Debug
 * @author    Yevgeniy A. Viktorov <wik@osmonitoring.com>
 * @copyright 2009 CHP Viktorov
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://github.com/yviktorov/tinycarts_libs/tree/master
 */

/**
 * FirePhp wrapper
 *
 * @category Tinycarts
 * @package  Tinycarts_Debug
 * @author   Yevgeniy A. Viktorov <wik@osmonitoring.com>
 * @license  http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link     http://github.com/yviktorov/tinycarts_libs/tree/master
 */
class Tinycarts_Debug_Firephp
{
    /**
     * Logs variables to the Firebug Console
     * via HTTP response headers and the FirePHP Firefox Extension.
     *
     * @param object $var   The variable to log
     * @param string $label Label to prepend to the log event
     * @param string $style Style of the log
     *
     * @return void Returns null if not in developer mode
     *
     * @author  Branko Ajzele
     * @license http://opensource.org/licenses/gpl-2.0.php GPL
     * @see     Zend_Wildfire_Plugin_FirePhp::send()
     */
    public static function send($var, $label = null, $style = 'LOG')
    {
        if (Mage::getIsDeveloperMode()) {
            $request = new Zend_Controller_Request_Http();
            $response = new Zend_Controller_Response_Http();
            $channel = Zend_Wildfire_Channel_HttpHeaders::getInstance();
            $channel->setRequest($request);
            $channel->setResponse($response);

            /**
             * Start output buffering
             */
            ob_start();

            Zend_Wildfire_Plugin_FirePhp::send($var, $label, $style);

            /*
            Style       Description
            LOG         Displays a plain log message
            INFO        Displays an info log message
            WARN        Displays a warning log message
            ERROR       Displays an error log message that increments Firebug’s
                        error count
            TRACE       Displays a log message with an expandable stack trace
            EXCEPTION   Displays an error long message with an expandable stack trace
            TABLE       Displays a log message with an expandable table
            */

            /**
             * Flush log data to browser
             */
            $channel->flush();
            $response->sendHeaders();
        } else {

            return null;
        }
    }
}
