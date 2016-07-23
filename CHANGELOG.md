# Change Log
All notable changes to this project will be documented in this file.

This projects adheres to [Semantic Versioning](http://semver.org/) and [Keep a 
CHANGELOG](http://keepachangelog.com/).

## [Unreleased]
Nothing right now.

## [0.3.0] - 2016-07-23

### Added

- Support for testing with WordPoints and the module network active. #9

### Changed

- `$this->module_file` is now expected to be the basename path of the module, not the full path. #9
- The module is now installed using `wordpoints_activate_module()`, instead of calling the function manually. #9

## [0.2.4] - 2015-06-25

### Fixed
- Incompatibility with WordPoints 1.10. #8

## [0.2.3] - 2015-06-25

### Added
- Changelog.

### Changed
- Exit code from used when bailing out from 0 to 1. #6

### Removed
- Installation instructions using `git subtree`.

### Fixed
- Incompatibility with WordPoints 2.0.0. #7

[unreleased]: https://github.com/WordPoints/module-uninstall-tester/compare/0.3.0...HEAD
[0.3.0]: https://github.com/WordPoints/module-uninstall-tester/compare/0.2.4...0.3.0
[0.2.4]: https://github.com/WordPoints/module-uninstall-tester/compare/0.2.3...0.2.4
[0.2.3]: https://github.com/WordPoints/module-uninstall-tester/compare/15056ef4...0.2.3
