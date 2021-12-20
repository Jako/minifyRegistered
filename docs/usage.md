After the package is installed, all js and css files (and chunks) added by the
MODX API functions `regClientStartupScript`, `regClientScript`, `regClientCSS`,
`regClientStartupHTMLBlock` and `regClientHTMLBlock` are checked to minify them
by minify.

## System Settings

MinifyRegistered uses the following system settings in the namespace `minifyregistered`:

Key | Description | Default
----|-------------|--------
groupJs  | Group minified files in `groupFolder` [^1]  | No
groupFolder | Group files in this folder with `groupJs` enabled | `assets/js`
minPath | Path to a working minify installation | `/assets/min/`
excludeJs | Comma separated list of files (including pathnames) not to be minified [^2] | -

[^1]: Grouping registered javascripts could change the inclusion order of the registered javascripts.
[^2]: Not minified files are included later than the grouped minified and minified files.
[^3]: Registered chunks (i.e. javascript code) are included at the last position of head/body.
[^4]: The order of inclusion is *external*, *grouped minified*, *minified*, *not minified* and direct code.
