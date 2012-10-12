WELCOME TO MINIFY!

Minify is an HTTP content server. It compresses sources of content 
(usually files), combines the result and serves it with appropriate 
HTTP headers. These headers can allow clients to perform conditional 
GETs (serving content only when clients do not have a valid cache) 
and tell clients to cache the file for a period of time. 
More info: http://code.google.com/p/minify/


WORDPRESS USER?

These WP plugins integrate Minify into WordPress's style and script hooks to
get you set up faster.
  http://wordpress.org/extend/plugins/bwp-minify/
  http://wordpress.org/extend/plugins/w3-total-cache/


INSTALLATION

This code is installed by MODX in the directory /assets/min/ as a child of 
your DOCUMENT_ROOT directory.


CONFIGURATION & USAGE

See http://code.google.com/p/minify/wiki/UserGuide

The settings for minify could be modified in the MODX system settings in the 
minifyregistered namespace.


FILE ENCODINGS

Minify *should* work fine with files encoded in UTF-8 or other 8-bit 
encodings like ISO 8859/Windows-1252. By default Minify appends
";charset=utf-8" to the Content-Type headers it sends. 

Leading UTF-8 BOMs are stripped from all sources to prevent 
duplication in output files, and files are converted to Unix newlines.
