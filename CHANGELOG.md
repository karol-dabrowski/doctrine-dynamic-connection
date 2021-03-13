# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

- Unit test checking database reinitialization when no parameters passed
- Library description in README
- PHPStan configuration

### Changed

- Refactored method retrieving connection object in `DynamicEntityManager`

## [0.2.1] - 2021-03-07

### Added

- README badge for code analysis
- README badge for the library version

### Changed

- Refactored unit tests of `DynamicEntityManager` class

## [0.2.0] - 2021-03-06

### Added

- Installation section in README.md
- Setup section in README.md
- Tests status shield in README.md
- Usage section in README.md

### Changed

- Method `changeDatabase()` renamed to `modifyConnection()`

### Removed

- Documentation section in README.md

## [0.1.0] - 2021-02-27

### Added

- Wrapper class for the default Doctrine connection
- Dynamic entity manager extending the default entity manager
- PHPUnit configuration
- Unit tests
- PHPCS configuration
- Readme
- Changelog
- License


[unreleased]: https://github.com/karol-dabrowski/doctrine-dynamic-connection/compare/v0.2.1...HEAD
[0.2.1]: https://github.com/karol-dabrowski/doctrine-dynamic-connection/releases/tag/v0.2.1
[0.2.0]: https://github.com/karol-dabrowski/doctrine-dynamic-connection/releases/tag/v0.2.0
[0.1.0]: https://github.com/karol-dabrowski/doctrine-dynamic-connection/releases/tag/v0.1.0
