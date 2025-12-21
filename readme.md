# Modularity Plugin

This plugin is an LTS version of the [Modularity plugin](https://github.com/helsingborg-stad/Modularity).

## Changes in this Fork

This LTS version includes significant security enhancements and feature improvements. Critical security fixes include AJAX nonce verification, sanitization of sidebar options and module fields, protection against host header injection vulnerabilities, and XSS prevention through proper output escaping.

New features include WebP image format support, MediaFlow video integration, and text box color customization options. The posts module has been enhanced with better filtering capabilities, archive link visibility controls, and support for rendering posts as template elements. Manual input modules now support proper link fields and improved accessibility with aria-describedby attributes.

Search functionality has been improved with better Elasticsearch indexing, fixed total count display, and prevention of hidden modules appearing in results. The plugin now properly handles translations when installed as an MU plugin and includes numerous accessibility improvements including removal of invalid aria-labelledby attributes.

Module management has been enhanced with submenu support for single post modules, improved sidebar handling, and fixes for module placement issues. The codebase includes performance optimizations such as preventing unnecessary queries with empty includes and proper dependency management.

## New WordPress Hooks

This fork adds extensive WordPress hooks for enhanced customization:

**Filters:**
- `Modularity/Module/Posts/TemplateController/{$template}` - Modify posts module template controller
- `Modularity/Module/Posts/archiveUrl` - Customize posts module archive URL
- `Modularity/Module/Template` - Modify module templates
- `Modularity/Module/Hero/imageSize` - Control hero module image size
- `Modularity/Display/mod-video/pre_getEmbedMarkup` - Pre-process video embed markup
- `Modularity/Module/Posts/Helper/pre_getPosts` - Pre-process posts retrieval
- `Modularity/Module/Posts/Helper/getPosts` - Modify retrieved posts
- `Modularity/Display/BeforeModule::widthClass` - Control module width classes
- `Modularity/Display/modules` - Modify displayed modules
- `Modularity/Display/pre_outputModule` - Pre-process module output
- `Modularity/Module/ManualInput/data/item` - Modify manual input item data

**Actions:**
- `Modularity/Editor/getModule` - Hook into module editor retrieval

## Installation

1. Install the package:
   ```bash
   composer require municipio/wp-plugin-modularity
   ```
2. Activate the plugin in WordPress.
