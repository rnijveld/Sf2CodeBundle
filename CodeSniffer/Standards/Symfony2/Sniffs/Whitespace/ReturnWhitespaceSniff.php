<?php

/**
 * Checks that for blocks where the only statement is a return statement, there should be no
 * blank lines and for blocks where the return statement is preceded by one or more other
 * statements, there should be exactly one blank line between those statements and the return
 * statement.
 */
class Symfony2_Sniffs_Whitespace_ReturnWhitespaceSniff implements PHP_CodeSniffer_Sniff
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

        $allowed = 2; // allowed number of newline characters before the statement
        while ($stackPtr > 1) {
            $stackPtr -= 1;
            if ($tokens[$stackPtr]['code'] === T_OPEN_CURLY_BRACKET) {
                $allowed = 1;
                break;
            } else if ($tokens[$stackPtr]['code'] === T_COMMENT) {
                continue;
            } else if ($tokens[$stackPtr]['code'] === T_WHITESPACE) {
                $newlines += substr_count($tokens[$stackPtr]['content'], "\n");
            } else {
                break;
            }
        }

        if ($newlines !== $allowed) {
            if ($allowed === 2) {
                $error = 'There must be one blank line before a return statement';
            } else {
                $error = 'There must be no blank lines for scopes with only a return statement';
            }
            $phpcsFile->addError($error, $originalStackPtr, 'Found');
        }
    }
}
