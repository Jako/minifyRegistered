<?php
/**
 * Setting Lexicon Entries for minifyRegistered
 *
 * @package minifyregistered
 * @subpackage lexicon
 */
$_lang['area_minify'] = 'Minify';
$_lang['setting_minifyregistered.allowDebugFlag'] = 'Allow debug mode output';
$_lang['setting_minifyregistered.allowDebugFlag_desc'] = 'Once true, you can send the cookie minDebug to request debug mode output. The cookie value should match the URIs you’d like to debug. E.g. to debug /min/f=file1.js send the cookie minDebug=file1.js You can manually enable debugging by appending `&debug` to a URI. E.g. /min/?f=script1.js,script2.js&debug<br><br>In `debug` mode, Minify combines files with no minification and adds comments to indicate line #s of the original files.';
$_lang['setting_minifyregistered.allowDirs'] = 'Allowed directories for minify';
$_lang['setting_minifyregistered.allowDirs_desc'] = '<strong>(JSON-encoded array of directory names)</strong> If you’d like to restrict the `f` option to files within/below particular directories below DOCUMENT_ROOT, set this here. You will still need to include the directory in the f or b GET parameters.<br><br>// = shortcut for DOCUMENT_ROOT';
$_lang['setting_minifyregistered.bubbleCssImports'] = 'Move @import rules to top';
$_lang['setting_minifyregistered.bubbleCssImports_desc'] = 'Combining multiple CSS files can place @import declarations after rules, which is invalid. Minify will attempt to detect when this happens and place a warning comment at the top of the CSS output. To resolve this you can either move the @imports within your CSS files, or enable this option, which will move all @imports to the top of the output. Note that moving @imports could affect CSS values (which is why this option is disabled by default).';
$_lang['setting_minifyregistered.cacheFileLocking'] = 'Cache file locking';
$_lang['setting_minifyregistered.cacheFileLocking_desc'] = 'Set to "No" if filesystem is NFS. On at least one NFS system flock-ing attempts stalled PHP for 30 seconds!';
$_lang['setting_minifyregistered.closureCompiler'] = 'Use Google’s Closure Compiler API';
$_lang['setting_minifyregistered.closureCompiler_desc'] = 'Falling back to JSMin on failure.';
$_lang['setting_minifyregistered.documentRoot'] = 'Document root';
$_lang['setting_minifyregistered.documentRoot_desc'] = 'Leave an empty string to use PHP’s $_SERVER[\'DOCUMENT_ROOT\'].';
$_lang['setting_minifyregistered.errorLogger'] = 'Log errors';
$_lang['setting_minifyregistered.errorLogger_desc'] = 'Set to true to log messages to FirePHP.';
$_lang['setting_minifyregistered.excludeJs'] = 'Exclude files';
$_lang['setting_minifyregistered.excludeJs_desc'] = 'Comma-separated list of files (including pathnames) not to be minified';
$_lang['setting_minifyregistered.groupFolder'] = 'Group folder';
$_lang['setting_minifyregistered.groupFolder_desc'] = 'Group files in this folder with `groupJs` enabled';
$_lang['setting_minifyregistered.groupJs'] = 'Group files';
$_lang['setting_minifyregistered.groupJs_desc'] = 'Group minified files in `groupFolder`';
$_lang['setting_minifyregistered.groupsOnly'] = 'Allow only group parameter';
$_lang['setting_minifyregistered.groupsOnly_desc'] = 'Set to true to disable the `f` GET parameter for specifying files. Only the `g` parameter will be considered.';
$_lang['setting_minifyregistered.libPath'] = 'Path to Minify’s lib folder.';
$_lang['setting_minifyregistered.libPath_desc'] = 'If you happen to move it, change this accordingly.';
$_lang['setting_minifyregistered.maxAge'] = 'Max-age value sent to browser';
$_lang['setting_minifyregistered.maxAge_desc'] = 'Cache-Control: max-age value sent to browser (in seconds). After this period, the browser will send another conditional GET. Use a longer period for lower traffic but you may want to shorten this before making changes if it’s crucial those changes are seen immediately.<br><br>Note: Despite this setting, if you include a number at the end of the querystring, maxAge will be set to one year. E.g. /min/f=hello.css&123456';
$_lang['setting_minifyregistered.minPath'] = 'Minify Path';
$_lang['setting_minifyregistered.minPath_desc'] = 'Path to a working minify installation';
$_lang['setting_minifyregistered.noMinPattern'] = 'Filename pattern not minified';
$_lang['setting_minifyregistered.noMinPattern_desc'] = 'By default, Minify will not minify files with names containing .min or -min before the extension. E.g. myFile.min.js will not be processed by JSMin<br><br>To minify all files, set this option to null. You could also specify your own pattern that is matched against the filename.';
$_lang['setting_minifyregistered.symlinks'] = 'Rewrite symlinks';
$_lang['setting_minifyregistered.symlinks_desc'] = '<strong>(JSON-encoded array of directory names and their replaced values)</strong> If you minify CSS files stored in symlink-ed directories, the URI rewriting algorithm can fail. To prevent this, provide an array of link paths to target paths, where the link paths are within the document root.<br><br>Because paths need to be normalized for this to work, use `//` to substitute the doc root in the link paths (the array keys).';
$_lang['setting_minifyregistered.uploaderHoursBehind'] = 'Modify upload time behind';
$_lang['setting_minifyregistered.uploaderHoursBehind_desc'] = 'If you upload files from Windows to a non-Windows server, Windows may report incorrect mtimes for the files. This may cause Minify to keep serving stale cache files when source file changes are made too frequently (e.g. more than once an hour).';
