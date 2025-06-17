# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

yStandard is a modern WordPress theme (v5.0.0) that requires WordPress 6.5+ and PHP 7.4+. It's built with TypeScript, SCSS, and uses modern build tools including Webpack, TailwindCSS, and PostCSS.

## Development Commands

### Asset Development
```bash
npm run watch        # Watch all assets for development
npm run watch:css    # Watch CSS only
npm run watch:script # Watch JS/TS only
npm run build        # Build all assets for production
npm run clean        # Clean build directories
```

### Code Quality
```bash
npm run lint         # Lint all code (PHP, JS, CSS)
npm run lint:php     # PHP linting with WordPress standards
npm run lint:js      # JavaScript/TypeScript linting
npm run test:php     # Run PHP unit tests
composer test        # Alternative PHP test command
```

### WordPress Environment
```bash
npm run wpenv:start    # Start local WordPress environment
npm run wpenv:stop     # Stop environment
npm run wpenv:destroy  # Clean up environment
```

### Distribution
```bash
npm run zip          # Create distribution package
```

## Architecture

### Directory Structure
- `/src/` - Source code for assets
  - `/scripts/` - TypeScript modules (admin, frontend features)
  - `/styles/` - SCSS files organized by foundation/components/utilities
- `/inc/` - PHP classes organized by feature (Admin, Blocks, Customizer, etc.)
- `/template-parts/` - Template part system for theme structure
- `/dist/` - Built assets (generated)

### PHP Architecture
- Object-oriented with autoloader (`class-ys-loader.php`)
- Modular component structure in `/inc/` subdirectories
- Template functions organized by feature
- WordPress coding standards enforced

### Asset Pipeline
- **TypeScript**: Compiled with WordPress Scripts webpack config
- **SCSS**: PostCSS pipeline with TailwindCSS (prefixed `tw-`)
- **CSS**: Custom properties system, modular loading
- **JavaScript**: ES modules with WordPress dependencies

### Build Configuration
- `webpack.app.config.js` - Main build configuration extending @wordpress/scripts
- `postcss.config.js` - CSS processing with TailwindCSS, autoprefixer, cssnano
- `tailwind.config.js` - Custom utility classes with preflight disabled
- `tsconfig.json` - Strict TypeScript configuration

## Key Features

### WordPress Integration
- Full block editor support with custom block styles
- FSE ready with theme.json configuration
- Extensive customizer integration
- WordPress 6.5+ required features

### Development Tools
- PHPUnit testing with WordPress test environment
- ESLint + Prettier for code formatting
- PHP CodeSniffer with WordPress standards
- Local development with @wordpress/env

## Important Notes

- Text domain: `ystandard`
- Requires PHP 8.0+ for development dependencies (Composer)
- TailwindCSS classes are prefixed with `tw-`
- All custom CSS uses CSS custom properties
- Japanese is the primary language but theme is translation-ready