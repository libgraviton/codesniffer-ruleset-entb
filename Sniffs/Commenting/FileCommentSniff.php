<?php
/**
 * Ensure that @author annotations contain email or URL
 *
 * PHP version 5
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
class ENTB_Sniffs_Commenting_FileCommentSniff extends PEAR_Sniffs_Commenting_FileCommentSniff
{
    /**
     * Process the author tag(s) that this header comment has.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param array                $tags      The tokens for these tags.
     *
     * @return void
     */
    protected function processAuthor(PHP_CodeSniffer_File $phpcsFile, array $tags)
    {
        $tokens = $phpcsFile->getTokens();
        foreach ($tags as $tag) {
            if ($tokens[($tag + 2)]['code'] !== T_DOC_COMMENT_STRING) {
                // No content.
                continue;
            }
            $content = $tokens[($tag + 2)]['content'];
            $local   = '\da-zA-Z-_+';
            // Dot character cannot be the first or last character in the local-part.
            $localMiddle = $local.'.\w';
            $mailPattern = '['.$local.'](['.$localMiddle.']*['.$local.'])*@[\da-zA-Z][-.\w]*[\da-zA-Z]\.[a-zA-Z]{2,7})';
            // allow simple urls
            $urlPattern = 'https?\:\/\/[a-z]+[.]+[a-z]+[a-zA-Z\/]*';
            if (preg_match('/^([^<]*)\s+<(('.$urlPattern.'|'.$mailPattern.')>$/', $content) === 0) {
                $error = 'Content of the @author tag must be in the form "Display Name <username@example.com>"';
                $phpcsFile->addError($error, $tag, 'InvalidAuthors');
            }
        }
    }
}
