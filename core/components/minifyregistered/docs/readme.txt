minifyRegistered
================================================================================

Collect the registered javascript and css files/chunks and minify them
for the MODX Revolution content management framework

Features
--------------------------------------------------------------------------------
minifyRegistered is a simple but effective tool for MODX Revolution. With this 
tool all js and css files (and chunks) added by the MODX API functions 
regClientStartupScript, regClientScript, regClientCSS, regClientStartupHTMLBlock
and regClientHTMLBlock are checked to minify them by minify

Installation
--------------------------------------------------------------------------------
MODX Package Management

Parameters
--------------------------------------------------------------------------------
The following parameters could be set in system settings

groupJs     - Group minified files in `groupFolder` (Note 1) (default false)
groupFolder - Group files in this folder with `groupJs` enabled 
              (default 'assets/js')
minPath     - Path to a working minify installation
              (default '/assets/min/')
excludeJs   - Comma separated list of files (including pathnames) not to be 
              minified (Note 2)

Notes
--------------------------------------------------------------------------------
1. Grouping registered javascripts could change the inclusion order of the 
   registered javascripts.
2. Not minified files are included later than the grouped minified and minified 
   files.
3. Registered chunks (i.e. javascript code) are included at the last position of
   head/body.
4. The order of inclusion is *external*, *grouped minified*, *minified*, 
   *not minified* and direct code.
