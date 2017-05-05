<?php

namespace Graviton\Sniff;

/**
 * Ensure that Interfaces are named as they should
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Greg Sherwood <gsherwood@squiz.net>
 * @author    Marc McIntyre <mmcintyre@squiz.net>
 * @copyright 2006-2011 Squiz Pty Ltd (ABN 77 084 670 600)
 * @license   http://matrix.squiz.net/developer/tools/php_cs/licence BSD Licence
 * @link      http://pear.php.net/package/PHP_CodeSniffer
 * @class
 */
class ValidInterfaceNameSniff implements PHP_CodeSniffer_Sniff
{
    /**
     * welche tokens wollen wir sniffen
     *
     * @return void
     */
    public function register()
    {
        return array(
            T_INTERFACE
        );
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The current file being processed.
     * @param int                  $stackPtr  The position of the current token
     *                                        in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        $className = $phpcsFile->findNext(T_STRING, $stackPtr);
        $name      = trim($tokens[$className]['content']);
        $errorData = array(ucfirst($tokens[$stackPtr]['content']));

        // Make sure the first letter in last part is not a capital I followed by another capitol.
        $parts = explode('_', $name);
        $lastPart = array_pop($parts);
        if (preg_match('|^I[A-Z]|', $lastPart) !== 0) {
            $error = $lastPart.' name must not begin with an I and another capital letter';
            $phpcsFile->addError($error, $stackPtr);
        }

        if (preg_match('|Interface$|', $name) === 0) {
            $error = $name.' name must end with Interface';
            $phpcsFile->addError($error, $stackPtr);
        }


    }//end process()

}
