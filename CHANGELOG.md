# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

## [1.3.1](https://github.com/sonata-project/twig-extensions/compare/1.3.0...1.3.1) - 2020-06-29
### Fixed
- [[#92](https://github.com/sonata-project/twig-extensions/pull/92)] Fix
  `Sonata\Twig\Status\StatusClassRendererInterface` implementation in
`Sonata\Twig\FlashMessage\FlashManager`
([@wbloszyk](https://github.com/wbloszyk))
- [[#92](https://github.com/sonata-project/twig-extensions/pull/92)] Fix
  `Sonata\Twig\Extension\StatusRuntime` to working with
`Sonata\Twig\FlashMessage\FlashManager` again, after add type hints
([@wbloszyk](https://github.com/wbloszyk))
- [[#94](https://github.com/sonata-project/twig-extensions/pull/94)] CSS Class
  now returned for flash messages ([@benrcole](https://github.com/benrcole))

## [1.3.0](https://github.com/sonata-project/twig-extensions/compare/1.2.0...1.3.0) - 2020-06-04
### Removed
- remove return type hints in `Sonata\Twig\FlashMessage\FlashManager::handlesObject()`
- remove return type hints in `Sonata\Twig\FlashMessage\FlashManager::getStatusClass()`
- remove return type hints in `Sonata\Twig\FlashMessage\StatusClassRendererInterface::handlesObject()`
- remove return type hints in `Sonata\Twig\FlashMessage\StatusClassRendererInterface::getStatusClass()`
- remove return type hints in `Sonata\Form\Type\BaseStatusType::configureOptions()`

## [1.2.0](https://github.com/sonata-project/twig-extensions/compare/1.1.1...1.2.0) - 2020-03-21
### Added
- Added support for `twig/twig:^3.0`
- Added `flashmessage.css` from CoreBundle

## [1.1.1](https://github.com/sonata-project/twig-extensions/compare/1.1.0...1.1.1) - 2020-01-04
### Fixed
- Fixed wrong service ids
- Add missing `form_type` parameter in configuration
- Make twig templates discoverable automatically

## [1.1.0](https://github.com/sonata-project/twig-extensions/compare/1.0.0...1.1.0) - 2019-12-06
### Added
- Added support for Symfony 5

### Changed
- Migrated to Twig namespaces
