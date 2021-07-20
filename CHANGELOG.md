# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

## [1.7.0](https://github.com/sonata-project/twig-extensions/compare/1.6.0...1.7.0) - 2021-07-20
### Added
- [[#222](https://github.com/sonata-project/twig-extensions/pull/222)] Allow customizing flashbag groups ([@core23](https://github.com/core23))
- [[#221](https://github.com/sonata-project/twig-extensions/pull/221)] `Sonata\Twig\FlashMessage\FlashManager::$requestStack` property ([@yann-eugone](https://github.com/yann-eugone))

### Changed
- [[#221](https://github.com/sonata-project/twig-extensions/pull/221)] `Sonata\Twig\FlashMessage\FlashManager::__construct()` `$session` argument allowed types for `Symfony\Component\HttpFoundation\Session\SessionInterface` or `Symfony\Component\HttpFoundation\RequestStack` ([@yann-eugone](https://github.com/yann-eugone))
- [[#221](https://github.com/sonata-project/twig-extensions/pull/221)] `Sonata\Twig\FlashMessage\FlashManager::getSession()` method to fetch session from request stack ([@yann-eugone](https://github.com/yann-eugone))
- [[#221](https://github.com/sonata-project/twig-extensions/pull/221)] `Sonata\Twig\FlashMessage\FlashManager` service definition to inject `'request_stack'` service instead of `'session'` ([@yann-eugone](https://github.com/yann-eugone))

### Deprecated
- [[#225](https://github.com/sonata-project/twig-extensions/pull/225)] `sonata_urlsafeid` filter ([@VincentLanglet](https://github.com/VincentLanglet))
- [[#221](https://github.com/sonata-project/twig-extensions/pull/221)] Passing `Symfony\Component\HttpFoundation\Session\SessionInterface` as $session argument of `Sonata\Twig\FlashMessage\FlashManager::__construct()` ([@yann-eugone](https://github.com/yann-eugone))
- [[#221](https://github.com/sonata-project/twig-extensions/pull/221)] `Sonata\Twig\FlashMessage\FlashManager::$session` property ([@yann-eugone](https://github.com/yann-eugone))

### Fixed
- [[#221](https://github.com/sonata-project/twig-extensions/pull/221)] Usage of deprecated 'session' service since Symfony 5.3 ([@yann-eugone](https://github.com/yann-eugone))

## [1.6.0](https://github.com/sonata-project/twig-extensions/compare/1.5.1...1.6.0) - 2021-05-18
### Added
- [[#216](https://github.com/sonata-project/twig-extensions/pull/216)] Added "more" ans "less" translations in french. ([@rgrassian](https://github.com/rgrassian))

## [1.5.1](https://github.com/sonata-project/twig-extensions/compare/1.5.0...1.5.1) - 2021-02-15
### Added
- [[#188](https://github.com/sonata-project/twig-extensions/pull/188)] Added support for `sonata-project/doctrine-extensions` 1.9. ([@franmomu](https://github.com/franmomu))

## [1.5.0](https://github.com/sonata-project/twig-extensions/compare/1.4.2...1.5.0) - 2021-01-04
### Added
- [[#159](https://github.com/sonata-project/twig-extensions/pull/159)] Added missing `pl` translations. ([@axzx](https://github.com/axzx))
- [[#169](https://github.com/sonata-project/twig-extensions/pull/169)] Support for PHP8 ([@VincentLanglet](https://github.com/VincentLanglet))

## [1.4.2](https://github.com/sonata-project/twig-extensions/compare/1.4.1...1.4.2) - 2020-12-08
### Fixed
- [[#124](https://github.com/sonata-project/twig-extensions/pull/124)] Fixed throwing a deprecation warning every time the bundle is loaded. ([@franmomu](https://github.com/franmomu))

## [1.4.1](https://github.com/sonata-project/twig-extensions/compare/1.4.0...1.4.1) - 2020-08-09
### Fixed
- [[#103](https://github.com/sonata-project/twig-extensions/pull/103)] Fixed conflict with class names. ([@franmomu](https://github.com/franmomu))

## [1.4.0](https://github.com/sonata-project/twig-extensions/compare/1.3.1...1.4.0) - 2020-08-08
### Added
- [[#90](https://github.com/sonata-project/twig-extensions/pull/90)] Add
  `Sonata\Twig\FlashMessage\FlashManager::addFlash(string $type, string
$message): void` to add flash message directly by FlashManager
([@wbloszyk](https://github.com/wbloszyk))
- [[#90](https://github.com/sonata-project/twig-extensions/pull/90)] Add
  `Sonata\Twig\FlashMessage\FlashManagerInterface` to allow create custom
FlashManager ([@wbloszyk](https://github.com/wbloszyk))
- [[#90](https://github.com/sonata-project/twig-extensions/pull/90)] Add twig
  function `sonata_flashmessage_class` - get css class from flash message type
in Twig ([@wbloszyk](https://github.com/wbloszyk))
- [[#100](https://github.com/sonata-project/twig-extensions/pull/100)] Added
  `Sonata\Twig\Bridge\Symfony\SonataTwigSymfonyBundle`.
([@phansys](https://github.com/phansys))

### Changed
- [[#101](https://github.com/sonata-project/twig-extensions/pull/101)] Bump
  Symfony to version 5.1 ([@franmomu](https://github.com/franmomu))
- [[#99](https://github.com/sonata-project/twig-extensions/pull/99)] Bump Twig
  to 2.6. ([@franmomu](https://github.com/franmomu))

### Deprecated
- [[#99](https://github.com/sonata-project/twig-extensions/pull/99)] Deprecated
  `sonata_template_deprecate` twig tag and
`sonata.twig.deprecated_template_extension` service.
([@franmomu](https://github.com/franmomu))
- [[#99](https://github.com/sonata-project/twig-extensions/pull/99)] Deprecated
  `DeprecatedTemplateExtension`, `DeprecatedTemplateNode` and
`DeprecatedTemplateTokenParser` classes.
([@franmomu](https://github.com/franmomu))

### Fixed
- [[#90](https://github.com/sonata-project/twig-extensions/pull/90)] Fix
  working `FlashManager` as `StatusClassRenderer`
([@wbloszyk](https://github.com/wbloszyk))
- [[#100](https://github.com/sonata-project/twig-extensions/pull/100)] Fixed
  Flex recipe implementation through `SonataTwigSymfonyBundle` alias.
([@phansys](https://github.com/phansys))

### Removed
- [[#101](https://github.com/sonata-project/twig-extensions/pull/101)] Remove
  support of Symfony < 3.4 and < 4.4 ([@franmomu](https://github.com/franmomu))

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
