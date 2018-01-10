<?php

namespace Graviton\Sniff;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * Ensure that @author annotations contain email or URL
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
class FileCommentSniff implements Sniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return [T_DOC_COMMENT_TAG];

    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token
     *                                               in the stack passed in $tokens.
     *
     * @return int
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        $tagValue = $tokens[$stackPtr]['content'];
        if ($tagValue !== '@author') {
            return;
        }

        // get the tag value
        $tokenValue = $phpcsFile->findNext(T_DOC_COMMENT_STRING, $stackPtr);

        $content = $tokens[$tokenValue]['content'];

        $local   = '\da-zA-Z-_+';
        // Dot character cannot be the first or last character in the local-part.
        $localMiddle = $local.'.\w';
        $mailPattern = '['.$local.'](['.$localMiddle.']*['.$local.'])*@[\da-zA-Z][-.\w]*[\da-zA-Z]\.[a-zA-Z]{2,7})';
        // allow simple urls
        $urlPattern = '(http[s]?:\/\/)?[^\s(["<,>]*\.[^\s[",><]*';
        if (preg_match('/^([^<]*)\s+<(('.$urlPattern.'|'.$mailPattern.')>$/', $content) === 0) {
            $error = 'Content of the @author tag must be in the form "Display Name <Email|URL>"';
            $phpcsFile->addError($error, $tokenValue, 'InvalidAuthors');
        }
    }
}
