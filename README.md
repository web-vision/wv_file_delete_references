# TYPO3 Extension wv_file_delete_references

## Documentation
Adds a button to files in filelist to delete the file and all corresponding
references.

## Installation
Install the `wv_file_delete_references` extension via composer and activate it
in the extension module.

## Hooks
There are possibilities to execute a hook before or after deleting a file with
its references within
`WebVision\WvFileDeleteReferences\Utility\File\ExtendedFileUtility`.
Those can be registered within the `TYPO3_CONF_VARS`:
```
\TYPO3\CMS\Core\Utility\ArrayUtility::mergeRecursiveWithOverrule(
    $GLOBALS,
    [
        'TYPO3_CONF_VARS' => [
            'SC_OPTIONS' => [
                'ext/wv_file_delete_references/Utility/ExtendedFileUtility.php' => [
                    'deletion_PreProc' => [
                        $extKey => YourVendor\YourExtension\Hook\YourClass::class,
                    ],
                    'deletion_PostProc' => [
                        $extKey => YourVendor\YourExtension\Hook\YourClass::class,
                    ],
                ],
            ],
        ],
    ]
);
```
The main methods name must be as the hook's key. It gets passed the file to be
deleted `\TYPO3\CMS\Core\Resource\File $file` as first and the `$result` (bool)
as second. The `$file` has a repository for the references. For further
information take a look into the code:
`WebVision\WvFileDeleteReferences\Domain\Repository\FileReferenceRepository` and
`WebVision\WvFileDeleteReferences\Domain\Model\FileReference`.
