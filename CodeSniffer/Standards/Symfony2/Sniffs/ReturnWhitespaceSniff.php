<?php

class Symfony2_Sniffs_ReturnWhitespaceSniff implements PHP_CodeSniffer_Sniff
{
    /**
     * Returns the token types that this sniff is interested in.
     *
     * @return array(int)
     */
    public function register()
    {
        return array(T_RETURN);
    }

    /**
     * Processes the tokens that this sniff is interested in.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file where the token was found.
     * @param int                  $stackPtr  The position in the stack where
     *                                        the token was found.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $newlines = 0;
        $originalStackPtr = $stackPtr;

        while ($stackPtr > 1) {
            $stackPtr -= 1;
            var_dump($tokens[$stackPtr]['type']);
            var_dump($tokens[$stackPtr]['content']);
            if ($tokens[$stackPtr]['code'] === T_OPEN_CURLY_BRACKET) {
                var_dump($tokens[$stackPtr]);
            }
            if ($tokens[$stackPtr]['code'] === T_COMMENT) {
                continue;
            } else if ($tokens[$stackPtr]['code'] === T_WHITESPACE) {
                $newlines += substr_count($tokens[$stackPtr]['content'], "\n");
            } else {
                break;
            }
        }

        if ($newlines !== 2) {
            $error = 'There must be one blank line before a return statement';
            $phpcsFile->addError($error, $originalStackPtr, 'Found');
        }
    }
}
