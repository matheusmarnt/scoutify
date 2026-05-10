# Changelog

## [2.3.3](https://github.com/matheusmarnt/scoutify/compare/v2.3.2...v2.3.3) (2026-05-10)


### Bug Fixes

* **slots:** normalize slot key hyphens/underscores and type $results as Collection ([#141](https://github.com/matheusmarnt/scoutify/issues/141)) ([0999936](https://github.com/matheusmarnt/scoutify/commit/09999365b064a294c78ef52907c86050baf7c4b9))

## [2.3.2](https://github.com/matheusmarnt/scoutify/compare/v2.3.1...v2.3.2) (2026-05-10)


### Bug Fixes

* **ui:** wire accent, empty-state slot, showIdleHint, and preview button theme setters ([#139](https://github.com/matheusmarnt/scoutify/issues/139)) ([487d5bf](https://github.com/matheusmarnt/scoutify/commit/487d5bf915c7e2875bdc226c811ce0e3dc11aa64))

## [2.3.1](https://github.com/matheusmarnt/scoutify/compare/v2.3.0...v2.3.1) (2026-05-09)


### Bug Fixes

* **tests:** remove unused lambda import in DialogContentClassesTest ([#137](https://github.com/matheusmarnt/scoutify/issues/137)) ([e7a72e3](https://github.com/matheusmarnt/scoutify/commit/e7a72e36e4201163bf87f0e7ad9395ef9ba6a85f))

## [2.3.0](https://github.com/matheusmarnt/scoutify/compare/v2.2.0...v2.3.0) (2026-05-09)


### Features

* **theme:** add dialogContent() to customize modal background and visual style ([#135](https://github.com/matheusmarnt/scoutify/issues/135)) ([b719be5](https://github.com/matheusmarnt/scoutify/commit/b719be565ea5bc3ef5869af27a1d24ec05b97e8a))

## [2.2.0](https://github.com/matheusmarnt/scoutify/compare/v2.1.2...v2.2.0) (2026-05-09)


### Features

* **compat:** add Laravel 13 / Scout 12 / Testbench 11 compatibility ([#132](https://github.com/matheusmarnt/scoutify/issues/132)) ([c983e29](https://github.com/matheusmarnt/scoutify/commit/c983e29c7c5df2beffc4d7d039868e9dc7475117))

## [2.1.2](https://github.com/matheusmarnt/scoutify/compare/v2.1.1...v2.1.2) (2026-05-09)


### Bug Fixes

* **provider:** enrich assertNoLegacyConfigKeys exception with upgrade steps ([#130](https://github.com/matheusmarnt/scoutify/issues/130)) ([fce0741](https://github.com/matheusmarnt/scoutify/commit/fce07419ba7bb6b674ea353fd8c09a984098477b))

## [2.1.1](https://github.com/matheusmarnt/scoutify/compare/v2.1.0...v2.1.1) (2026-05-09)


### Bug Fixes

* **views:** apply ThemeConfig fallback to input component ([#127](https://github.com/matheusmarnt/scoutify/issues/127)) ([06a8695](https://github.com/matheusmarnt/scoutify/commit/06a8695bec4806d6db6bc743c7354473c79a88f8))

## [2.1.0](https://github.com/matheusmarnt/scoutify/compare/v2.0.0...v2.1.0) (2026-05-09)


### Features

* **ai:** llms.txt generator + MCP server cross-links for AI documentation access ([#124](https://github.com/matheusmarnt/scoutify/issues/124)) ([ee180ec](https://github.com/matheusmarnt/scoutify/commit/ee180eccc5842cc96724ddaa57eb4737307c9ecb))

## [2.0.0](https://github.com/matheusmarnt/scoutify/compare/v1.15.2...v2.0.0) (2026-05-09)


### ⚠ BREAKING CHANGES

* v2.0.0 — unified fluent UI/theme/types API ([#120](https://github.com/matheusmarnt/scoutify/issues/120))

### Features

* v2.0.0 — unified fluent UI/theme/types API ([#120](https://github.com/matheusmarnt/scoutify/issues/120)) ([6ae34c4](https://github.com/matheusmarnt/scoutify/commit/6ae34c40176128d00de54043d2945ef1234ebcdb))

## [1.15.2](https://github.com/matheusmarnt/scoutify/compare/v1.15.1...v1.15.2) (2026-05-08)


### Bug Fixes

* **deps:** widen symfony/yaml to ^7.0|^8.0 ([#118](https://github.com/matheusmarnt/scoutify/issues/118)) ([5d13b32](https://github.com/matheusmarnt/scoutify/commit/5d13b32692a50eb515cb213a01d607d6b27961f4))

## [1.15.1](https://github.com/matheusmarnt/scoutify/compare/v1.15.0...v1.15.1) (2026-05-08)


### Miscellaneous Chores

* **deps:** bump actions/checkout from 4 to 6 ([a317499](https://github.com/matheusmarnt/scoutify/commit/a317499890ebffc309942ea6859867f5b6fe2640))
* **deps:** bump actions/configure-pages from 5 to 6 ([69248ab](https://github.com/matheusmarnt/scoutify/commit/69248abd692a21dc22ba24a8dae476d34e0a78f4))
* **deps:** bump actions/setup-node from 4 to 6 ([97a55f1](https://github.com/matheusmarnt/scoutify/commit/97a55f1866f16c88a0165c44b22dd864868a250f))
* **deps:** bump actions/setup-node from 4 to 6 ([30e97f8](https://github.com/matheusmarnt/scoutify/commit/30e97f88138e366ef20df3b4e9811e1481e7aa30))
* **deps:** bump actions/upload-pages-artifact from 3 to 5 ([364fd38](https://github.com/matheusmarnt/scoutify/commit/364fd3827ca7c6dfa07c52a424c1e624a1aae5d2))
* **deps:** bump actions/upload-pages-artifact from 3 to 5 ([96853ea](https://github.com/matheusmarnt/scoutify/commit/96853ea45622581bf64c7c8d370eb12f292c1824))

## [1.15.0](https://github.com/matheusmarnt/scoutify/compare/v1.14.3...v1.15.0) (2026-05-08)


### Features

* add ComposePatcher service for safe depends_on patching ([8124db5](https://github.com/matheusmarnt/scoutify/commit/8124db55f173a1f0e1817b0d2690a0c543b08643))
* auto-patch compose.yaml depends_on via ComposePatcher in InstallCommand ([6f8488d](https://github.com/matheusmarnt/scoutify/commit/6f8488d9f3e287f88ed2b5d51cd6be1bf1d58f4a))
* Docker/Sail reliability hardening ([3af8cc7](https://github.com/matheusmarnt/scoutify/commit/3af8cc78a96183acb5dae2277791cc3995904d8e))
* extend compose patching to generic Docker installs ([f738279](https://github.com/matheusmarnt/scoutify/commit/f7382797cbcbb616db7407af59076fc78cb00237))


### Miscellaneous Chores

* group dev-tooling and Laravel patch bumps in Dependabot ([ceb0523](https://github.com/matheusmarnt/scoutify/commit/ceb0523782caad5be54f52b93e5ba0a8616b789e))
* promote symfony/yaml to direct require for compose patching ([fac9452](https://github.com/matheusmarnt/scoutify/commit/fac9452aeded2c671934991649bff35831be543c))
* track Meilisearch/Typesense image versions in stubs via Dependabot ([7cb9225](https://github.com/matheusmarnt/scoutify/commit/7cb92251ea34a1f773f6d50d6ce3391d0e132d1c))

## [1.14.3](https://github.com/matheusmarnt/scoutify/compare/v1.14.2...v1.14.3) (2026-05-07)


### Bug Fixes

* mouse click on result row no longer navigates ([#107](https://github.com/matheusmarnt/scoutify/issues/107)) ([9c56225](https://github.com/matheusmarnt/scoutify/commit/9c5622586a087fda8fd0d9797328604ae8b21ecb))

## [1.14.2](https://github.com/matheusmarnt/scoutify/compare/v1.14.1...v1.14.2) (2026-05-07)


### Bug Fixes

* keyboard nav & click propagation for preview/download buttons ([#104](https://github.com/matheusmarnt/scoutify/issues/104)) ([db26838](https://github.com/matheusmarnt/scoutify/commit/db268381c1ece93c39507347622e49d0f34bd79f))

## [1.14.1](https://github.com/matheusmarnt/scoutify/compare/v1.14.0...v1.14.1) (2026-05-07)


### Miscellaneous Chores

* stop tracking AGENTS.md (covered by .gitignore) ([5160603](https://github.com/matheusmarnt/scoutify/commit/5160603566a6922580e098e05ff8a657d0f82079))

## [1.14.0](https://github.com/matheusmarnt/scoutify/compare/v1.13.3...v1.14.0) (2026-05-07)


### Features

* file preview & download in global search modal ([#100](https://github.com/matheusmarnt/scoutify/issues/100)) ([b830159](https://github.com/matheusmarnt/scoutify/commit/b83015972d07abb4f50c9764bae16d9beb3a3bb1))

## [1.13.3](https://github.com/matheusmarnt/scoutify/compare/v1.13.2...v1.13.3) (2026-05-06)


### Miscellaneous Chores

* stop tracking PROJECT_MEMORY.md (covered by .gitignore) ([e244740](https://github.com/matheusmarnt/scoutify/commit/e2447404bc1935e7279bc42c10888e21ca9fc259))

## [1.13.2](https://github.com/matheusmarnt/scoutify/compare/v1.13.1...v1.13.2) (2026-05-06)


### Bug Fixes

* **navigation:** prevent wire:navigate from hijacking external URLs ([#97](https://github.com/matheusmarnt/scoutify/issues/97)) ([54795f6](https://github.com/matheusmarnt/scoutify/commit/54795f626fea446c214bbe9afd11901234eb20f0))

## [1.13.1](https://github.com/matheusmarnt/scoutify/compare/v1.13.0...v1.13.1) (2026-05-04)


### Bug Fixes

* **docs:** remove html tags from browser tab title ([3d49300](https://github.com/matheusmarnt/scoutify/commit/3d49300fc0d115248c8bb6b5e94f7482c0cd408e))

## [1.13.0](https://github.com/matheusmarnt/scoutify/compare/v1.12.1...v1.13.0) (2026-05-04)


### Features

* astro starlight docs site ([737dcac](https://github.com/matheusmarnt/scoutify/commit/737dcac7cce7f16a863918c852e41dca50d746a7))
* **docs-site:** add og-card image ([d317213](https://github.com/matheusmarnt/scoutify/commit/d3172136b8581d6053ad74e1f22853b9e276e0ed))
* **docs-site:** apply scoutify brand palette and hero theme ([93e4741](https://github.com/matheusmarnt/scoutify/commit/93e474156d786913b7ce1775e0046bde03f55bb7))
* **docs-site:** wire astro config, splash hero, and sidebar stubs ([4b14975](https://github.com/matheusmarnt/scoutify/commit/4b1497599066a4a21a5937e8097f238b9dd0716f))


### Miscellaneous Chores

* **docs-site:** scaffold astro starlight project ([2f0fdd2](https://github.com/matheusmarnt/scoutify/commit/2f0fdd2712196abef1ff438e611833b39d049b15))

## [1.12.1](https://github.com/matheusmarnt/scoutify/compare/v1.12.0...v1.12.1) (2026-05-04)


### Miscellaneous Chores

* **deps:** bump actions/github-script from 7 to 9 ([#85](https://github.com/matheusmarnt/scoutify/issues/85)) ([39e65d8](https://github.com/matheusmarnt/scoutify/commit/39e65d892cd9b35fa3b52f514a78a8b6bfb1ca1d))
* **deps:** bump dependabot/fetch-metadata from 2 to 3 ([#86](https://github.com/matheusmarnt/scoutify/issues/86)) ([219b43a](https://github.com/matheusmarnt/scoutify/commit/219b43aa464998347a8274f939b66609e7f946a9))

## [1.12.0](https://github.com/matheusmarnt/scoutify/compare/v1.11.4...v1.12.0) (2026-05-04)


### Features

* implement per-model global search visibility ([#87](https://github.com/matheusmarnt/scoutify/issues/87)) ([136e4c3](https://github.com/matheusmarnt/scoutify/commit/136e4c36d70b305a48f9f17ce441ddeb0d305395))

## [1.11.4](https://github.com/matheusmarnt/scoutify/compare/v1.11.3...v1.11.4) (2026-04-30)


### Bug Fixes

* detect third-party blade-icon prefixes in IconResolver to prevent mangling GlobalSearchGroup icons ([#83](https://github.com/matheusmarnt/scoutify/issues/83)) ([be703ce](https://github.com/matheusmarnt/scoutify/commit/be703ce0790daa9c92002b5758b4874f799b3d00))

## [1.11.3](https://github.com/matheusmarnt/scoutify/compare/v1.11.2...v1.11.3) (2026-04-30)


### Bug Fixes

* access Factory sets as array in icon.blade.php (fixes ri-*, tabler-* prefix detection) ([0f0e5b4](https://github.com/matheusmarnt/scoutify/commit/0f0e5b43f0b3a30adbc1c795082bd9af70b83c50))
* access Factory sets as array, not object, in icon.blade.php ([5741a72](https://github.com/matheusmarnt/scoutify/commit/5741a72843603af709c2f21a5bd05d1e46a29e5f))

## [1.11.2](https://github.com/matheusmarnt/scoutify/compare/v1.11.1...v1.11.2) (2026-04-30)


### Bug Fixes

* use Factory::all() with try/catch to detect third-party icon pack prefixes ([#79](https://github.com/matheusmarnt/scoutify/issues/79)) ([066d9a1](https://github.com/matheusmarnt/scoutify/commit/066d9a12bfb05edaf791d746fc591406cbfb1e25))

## [1.11.1](https://github.com/matheusmarnt/scoutify/compare/v1.11.0...v1.11.1) (2026-04-30)


### Bug Fixes

* add missing blade-icons pack prefixes to icon qualifier regex ([#77](https://github.com/matheusmarnt/scoutify/issues/77)) ([854865e](https://github.com/matheusmarnt/scoutify/commit/854865ee09b4e74e71b91039af5c063c1420cc84))

## [1.11.0](https://github.com/matheusmarnt/scoutify/compare/v1.10.0...v1.11.0) (2026-04-30)


### Features

* sanitize html fields to plain text in global search results ([#74](https://github.com/matheusmarnt/scoutify/issues/74)) ([74d8908](https://github.com/matheusmarnt/scoutify/commit/74d89081a9581abbccf9bd4598adaefa3cc1e491))

## [1.10.0](https://github.com/matheusmarnt/scoutify/compare/v1.9.2...v1.10.0) (2026-04-29)


### Features

* add mobile-only trigger component for global search modal ([e8ad4dc](https://github.com/matheusmarnt/scoutify/commit/e8ad4dcafe8f2560c03e799fe70421cf0a512fce))

## [1.9.2](https://github.com/matheusmarnt/scoutify/compare/v1.9.1...v1.9.2) (2026-04-29)


### Bug Fixes

* register Livewire v3 modal component via Livewire::component() ([#71](https://github.com/matheusmarnt/scoutify/issues/71)) ([a1933b2](https://github.com/matheusmarnt/scoutify/commit/a1933b221989776e006f2325391a1a492d452975))

## [1.9.1](https://github.com/matheusmarnt/scoutify/compare/v1.9.0...v1.9.1) (2026-04-29)


### Miscellaneous Chores

* use English label in config example comment ([#69](https://github.com/matheusmarnt/scoutify/issues/69)) ([127826f](https://github.com/matheusmarnt/scoutify/commit/127826ffae710cb5a0097ba8b064d13afbbc0bb5))

## [1.9.0](https://github.com/matheusmarnt/scoutify/compare/v1.8.1...v1.9.0) (2026-04-29)


### Features

* publish Scout config on install, adopt compose.yaml standard ([#67](https://github.com/matheusmarnt/scoutify/issues/67)) ([3712b72](https://github.com/matheusmarnt/scoutify/commit/3712b72825e77f84d121c4708e929a98292290b6))


### Miscellaneous Chores

* add CLAUDE.md with project instructions for Claude Code ([#64](https://github.com/matheusmarnt/scoutify/issues/64)) ([ffde964](https://github.com/matheusmarnt/scoutify/commit/ffde96480bd583b4b54f5a2bf6e27f172ff92b8e))
* untrack CLAUDE.md and add to .gitignore ([#66](https://github.com/matheusmarnt/scoutify/issues/66)) ([40a01d1](https://github.com/matheusmarnt/scoutify/commit/40a01d1058d335723828d0f68d20b201a7b3c26c))

## [1.8.1](https://github.com/matheusmarnt/scoutify/compare/v1.8.0...v1.8.1) (2026-04-29)


### Bug Fixes

* normalize Highlighter output to NFC and update stale modal listener test ([#60](https://github.com/matheusmarnt/scoutify/issues/60)) ([4cc0615](https://github.com/matheusmarnt/scoutify/commit/4cc0615f4f85db8f12d8db91e92ffe38e42516f9))

## [1.8.0](https://github.com/matheusmarnt/scoutify/compare/v1.7.0...v1.8.0) (2026-04-29)


### Features

* **search:** accent-insensitive highlight, auto subtitle, accordion chips, and modal trigger improvements ([#56](https://github.com/matheusmarnt/scoutify/issues/56)) ([e86a381](https://github.com/matheusmarnt/scoutify/commit/e86a381aa8c5d44e6d545bf52fb41a601b3df3f0))

## [1.7.0](https://github.com/matheusmarnt/scoutify/compare/v1.6.2...v1.7.0) (2026-04-29)


### Features

* **gs:** improve keyboard navigation and visual polish in global search ([#54](https://github.com/matheusmarnt/scoutify/issues/54)) ([7d05222](https://github.com/matheusmarnt/scoutify/commit/7d05222651f95853931866172ca218cd376acf5e))

## [1.6.2](https://github.com/matheusmarnt/scoutify/compare/v1.6.1...v1.6.2) (2026-04-28)


### Bug Fixes

* **modal:** remove nested Alpine x-data from dialog to fix keyboard navigation ([#52](https://github.com/matheusmarnt/scoutify/issues/52)) ([924f285](https://github.com/matheusmarnt/scoutify/commit/924f2852f5a98a88c2f6bd958c0bc27ddc5c85f4))

## [1.6.1](https://github.com/matheusmarnt/scoutify/compare/v1.6.0...v1.6.1) (2026-04-28)


### Bug Fixes

* zero-config discovery pipeline — 7 cooperating defects ([#50](https://github.com/matheusmarnt/scoutify/issues/50)) ([20d12fc](https://github.com/matheusmarnt/scoutify/commit/20d12fcaa06e171cbc34dc44c0f90640b7b9319b))

## [1.6.0](https://github.com/matheusmarnt/scoutify/compare/v1.5.3...v1.6.0) (2026-04-28)


### Features

* globalSearchBuilder hook + Meilisearch prefix search warning in DoctorCommand ([#48](https://github.com/matheusmarnt/scoutify/issues/48)) ([fefbffb](https://github.com/matheusmarnt/scoutify/commit/fefbffb293026e40145ee640256bc8ad93d2a8a1))

## [1.5.3](https://github.com/matheusmarnt/scoutify/compare/v1.5.2...v1.5.3) (2026-04-28)


### Bug Fixes

* expose isOpen getter in scoutifyModal to fix keyboard nav ReferenceError ([#46](https://github.com/matheusmarnt/scoutify/issues/46)) ([4722655](https://github.com/matheusmarnt/scoutify/commit/472265560e626b8bb01d5b1230fdd75af5a42d6c))

## [1.5.2](https://github.com/matheusmarnt/scoutify/compare/v1.5.1...v1.5.2) (2026-04-28)


### Bug Fixes

* window listener in scoutifyModal init opens modal from custom triggers ([#44](https://github.com/matheusmarnt/scoutify/issues/44)) ([c9d0902](https://github.com/matheusmarnt/scoutify/commit/c9d0902ea52be1ea38c55f0129e50f890bbdfb4f))

## [1.5.1](https://github.com/matheusmarnt/scoutify/compare/v1.5.0...v1.5.1) (2026-04-28)


### Bug Fixes

* type chip labels respect APP_LOCALE (dynamic label resolution) ([#42](https://github.com/matheusmarnt/scoutify/issues/42)) ([0a9b0c0](https://github.com/matheusmarnt/scoutify/commit/0a9b0c0e214822573d95218e1d39d397a1e431d2))

## [1.5.0](https://github.com/matheusmarnt/scoutify/compare/v1.4.0...v1.5.0) (2026-04-28)


### Features

* zero-config auto-discovery, color tokens, keyboard nav, filter row stability ([#40](https://github.com/matheusmarnt/scoutify/issues/40)) ([83cb937](https://github.com/matheusmarnt/scoutify/commit/83cb9374892b4a46ff49fd9395f12e420a550b35))

## [1.4.0](https://github.com/matheusmarnt/scoutify/compare/v1.3.4...v1.4.0) (2026-04-27)


### Features

* **modal:** grouped search results, visual parity, power UX (v1.4.0) ([#38](https://github.com/matheusmarnt/scoutify/issues/38)) ([e33b8d2](https://github.com/matheusmarnt/scoutify/commit/e33b8d2d00dc8d122a2574e0fe76a0da5c7b83f7))

## [1.3.4](https://github.com/matheusmarnt/scoutify/compare/v1.3.3...v1.3.4) (2026-04-27)


### Bug Fixes

* **modal:** authorize results, render highlights, restore recents ([#36](https://github.com/matheusmarnt/scoutify/issues/36)) ([3381405](https://github.com/matheusmarnt/scoutify/commit/3381405656134be0628a39c42a61b9627bce06f6))

## [1.3.3](https://github.com/matheusmarnt/scoutify/compare/v1.3.2...v1.3.3) (2026-04-27)


### Bug Fixes

* **modal:** restore panel width to match reference (md:max-w-2xl) ([#34](https://github.com/matheusmarnt/scoutify/issues/34)) ([a65e889](https://github.com/matheusmarnt/scoutify/commit/a65e8898d41c241ae7f937399495696fc49329e8))

## [1.3.2](https://github.com/matheusmarnt/scoutify/compare/v1.3.1...v1.3.2) (2026-04-27)


### Bug Fixes

* **modal:** wire keyboard shortcuts and ship self-contained CSS partial ([#32](https://github.com/matheusmarnt/scoutify/issues/32)) ([95ac910](https://github.com/matheusmarnt/scoutify/commit/95ac91020c21d30c0e0a0aa0b755672467e5950a))

## [Unreleased]

### Added

* Global keyboard shortcuts (`Ctrl+K`, `⌘K`, `/`) wired inside the modal component — no extra layout markup required.
* Self-contained CSS partial `resources/css/scoutify.css` with `@source` for all package views, `--color-scoutify-accent` theme tokens, and dynamic badge color safelist.
* `scoutify:install` now injects the CSS `@import` line into `resources/css/app.css` automatically (idempotent).

### Changed

* Replaced `--color-accent` dependency with namespaced `--color-scoutify-accent` to avoid clashing with consumer themes.
* `<x-scoutify::gs.trigger />` kbd badge renders `⌘K` on macOS and `Ctrl K` on other platforms.
* README install steps simplified: manual `@source` directive replaced by single `@import` line.

## [1.3.1](https://github.com/matheusmarnt/scoutify/compare/v1.3.0...v1.3.1) (2026-04-27)


### Bug Fixes

* **install:** pass services to sail:add as string, detect all compose file names ([#30](https://github.com/matheusmarnt/scoutify/issues/30)) ([fd99813](https://github.com/matheusmarnt/scoutify/commit/fd998131dc8c74bbcb91e1023e84631896d3928a))

## [1.3.0](https://github.com/matheusmarnt/scoutify/compare/v1.2.2...v1.3.0) (2026-04-27)


### Features

* **install:** detect environment and configure search backend automatically ([#28](https://github.com/matheusmarnt/scoutify/issues/28)) ([0ec8711](https://github.com/matheusmarnt/scoutify/commit/0ec8711098f7ed950124fb278b07f7041fbb27e2))

## [1.2.2](https://github.com/matheusmarnt/scoutify/compare/v1.2.1...v1.2.2) (2026-04-27)


### Bug Fixes

* **ci:** remove broken auto-merge step, add workflow_dispatch trigger ([#26](https://github.com/matheusmarnt/scoutify/issues/26)) ([b286527](https://github.com/matheusmarnt/scoutify/commit/b286527a53a360a70f84b4cb60a278242e5f9886))

## [1.2.1](https://github.com/matheusmarnt/scoutify/compare/v1.2.0...v1.2.1) (2026-04-27)


### Bug Fixes

* **ci:** extract PR number from release-please JSON output before merging ([#23](https://github.com/matheusmarnt/scoutify/issues/23)) ([0bc6bf5](https://github.com/matheusmarnt/scoutify/commit/0bc6bf5d0ad34a4df625475b8198ed94c837db3a))
* **ci:** pass --repo flag to gh pr merge so it works without checkout ([#25](https://github.com/matheusmarnt/scoutify/issues/25)) ([1379b14](https://github.com/matheusmarnt/scoutify/commit/1379b1434c4f5850c09a138b8483bdc1c2de6993))

## [1.2.0](https://github.com/matheusmarnt/scoutify/compare/v1.1.0...v1.2.0) (2026-04-27)


### Features

* inject smart globalSearchUrl stub via scoutify:searchable ([#21](https://github.com/matheusmarnt/scoutify/issues/21)) ([d7a08eb](https://github.com/matheusmarnt/scoutify/commit/d7a08eb9bb154b091d31625b5f71caf6bcb3ec9b))

## [1.1.0](https://github.com/matheusmarnt/scoutify/compare/v1.0.1...v1.1.0) (2026-04-27)


### Features

* auto-inject Searchable trait + interface via scoutify:searchable ([#19](https://github.com/matheusmarnt/scoutify/issues/19)) ([5d1d202](https://github.com/matheusmarnt/scoutify/commit/5d1d20202c15cf21dfb6955e27984e09c645ce9e))


### Miscellaneous Chores

* **deps:** bump actions/checkout from 4 to 6 ([#17](https://github.com/matheusmarnt/scoutify/issues/17)) ([1c77f17](https://github.com/matheusmarnt/scoutify/commit/1c77f1776ca338f18dc04f7ebfd3b89f454d74d8))
* **deps:** bump googleapis/release-please-action from 4 to 5 ([#18](https://github.com/matheusmarnt/scoutify/issues/18)) ([c9421ce](https://github.com/matheusmarnt/scoutify/commit/c9421ce57774ea2289a83c86c4ff760c336c5dac))

## [1.0.1](https://github.com/matheusmarnt/scoutify/compare/v1.0.0...v1.0.1) (2026-04-27)


### Bug Fixes

* widen pest version constraint to support v3 and v4 ([#14](https://github.com/matheusmarnt/scoutify/issues/14)) ([1bc5622](https://github.com/matheusmarnt/scoutify/commit/1bc56227bea3e0fc5b26d8ecd206e4469d1a80b6))


### Miscellaneous Chores

* **deps:** update laravel/scout requirement from ^10.0 to ^11.1 ([#2](https://github.com/matheusmarnt/scoutify/issues/2)) ([2e3fbb8](https://github.com/matheusmarnt/scoutify/commit/2e3fbb80bea1fd88c097b2eb4c3c605299c323c4))

## 1.0.0 (2026-04-27)


### Features

* add Artisan commands (install, searchable, flush, import, sync) ([f6b0a0c](https://github.com/matheusmarnt/scoutify/commit/f6b0a0c2a5e901abb437b0e2c398694e2a1db1b2))
* add Artisan commands (install, searchable, flush, import, sync) ([9af077d](https://github.com/matheusmarnt/scoutify/commit/9af077d33e7e38c085991cc289258dd65a5ad6db))
* add core services (IconResolver, ModelDiscoverer, ScoutConfigurator, SearchAggregator) ([0c4796b](https://github.com/matheusmarnt/scoutify/commit/0c4796b976d50f553db2ca951a308d64b9c0bcbc))
* add core services (IconResolver, ModelDiscoverer, ScoutConfigurator, SearchAggregator) ([29b2f4c](https://github.com/matheusmarnt/scoutify/commit/29b2f4c5d462c6b40036dfab200335f744f4516c))
* add GloballySearchable contract, Searchable trait and ResultDto ([8473af3](https://github.com/matheusmarnt/scoutify/commit/8473af3eb068f32bd39664ad06a22e18828ceb4f))
* add GloballySearchable contract, Searchable trait and ResultDto ([0d6377d](https://github.com/matheusmarnt/scoutify/commit/0d6377dcbcd57cc64a3d4dd7a04ac04dec7a64cf))
* add i18n support (pt_BR, en, es) ([a4380bd](https://github.com/matheusmarnt/scoutify/commit/a4380bd37426d4ad17e92bbf01b01a0d7037141b))
* add i18n support (pt_BR, en, es) ([d057260](https://github.com/matheusmarnt/scoutify/commit/d05726003198d67cbec96454d32f7c78900b2e0e))
* add Livewire runtime version detection helper ([9ef0fbc](https://github.com/matheusmarnt/scoutify/commit/9ef0fbcdfe171c21d8e53173d358cac61b4cda5d))
* add Livewire runtime version detection helper ([2ad5636](https://github.com/matheusmarnt/scoutify/commit/2ad5636c6bb99979cc225f0eb2233ecc039b4c35))
* add service provider and configurable defaults ([1dc271c](https://github.com/matheusmarnt/scoutify/commit/1dc271cb7bfdf3d035b1dcd58fb123e8e3c5c3f0))
* add service provider and configurable defaults ([6af6a51](https://github.com/matheusmarnt/scoutify/commit/6af6a5161a1e99da27f5b7bbde3872acf063178e))
* port blade components from laravel-tall global-search ([4b24283](https://github.com/matheusmarnt/scoutify/commit/4b242836437065372a8b0690f492a685cc5dd4a5))
* port blade components from laravel-tall global-search ([b054fe6](https://github.com/matheusmarnt/scoutify/commit/b054fe6f26a25232b22286a323ee5e44f07e52fa))
* port Livewire Modal component ([9d3085e](https://github.com/matheusmarnt/scoutify/commit/9d3085e378f154bcc97a7f7e069b48e703a06ea6))
* port Livewire Modal component ([32ef41d](https://github.com/matheusmarnt/scoutify/commit/32ef41d70e7984375432f97c6cf70b6df6181580))


### Bug Fixes

* add static defaults to Searchable trait and ResultDto factory ([22a6c4c](https://github.com/matheusmarnt/scoutify/commit/22a6c4cff7d448953f89f8512534d0a9af1ba9d3))
* caret for installing carbon dependency on windows workflow now escaped correctly ([2b3191e](https://github.com/matheusmarnt/scoutify/commit/2b3191ec206ef0380dec367e7fd9eee846650fd6))
* caret for installing carbon dependency on windows workflow now escaped correctly ([3843528](https://github.com/matheusmarnt/scoutify/commit/38435288dcf6c8bbec0680604b2c792b6fdb64c5))
* change branch reference from `master` to `main` ([aae91fe](https://github.com/matheusmarnt/scoutify/commit/aae91fe6ae600f7e9d19ff55bdaa0a316e4a0716))
* **ci:** use caret version constraint for testbench ([d689d1f](https://github.com/matheusmarnt/scoutify/commit/d689d1fd513ff9727094f64819da75211e394bb6))
* **console:** replace exec with Process, propagate exit codes, add --all flag ([a912c56](https://github.com/matheusmarnt/scoutify/commit/a912c56befa0eae5876f32ce0f705a912bad80f3))
* correct instanceof check, IconResolver guard, ScoutConfigurator, ModelDiscoverer namespace and contract methods ([786b47b](https://github.com/matheusmarnt/scoutify/commit/786b47b85c7cb728a8e7d011312279614f0d59a4))
* correct trans_choice usage and add zero-count form to all lang files ([e3657d4](https://github.com/matheusmarnt/scoutify/commit/e3657d474b40a13f9392337643b579ff89db81a1))
* Deprecated message ([8aed67e](https://github.com/matheusmarnt/scoutify/commit/8aed67ef6fa85c5979d4b5607d1f43f39d96aead))
* **deps:** add PHP 8.3 and Laravel 10 compatibility ([9fa7eca](https://github.com/matheusmarnt/scoutify/commit/9fa7eca936e81d8e06f35d9706d8b6836f371758))
* handle null and dev- version strings in LivewireVersion::major() ([e3a3de6](https://github.com/matheusmarnt/scoutify/commit/e3a3de6f806350f85523a3d450245e625df294d8))
* illuminate/contracts dependency version to add Laravel 13 compatibility ([#380](https://github.com/matheusmarnt/scoutify/issues/380)) ([f59cfcb](https://github.com/matheusmarnt/scoutify/commit/f59cfcb495b8bc008838118fbb8d17daafe6cb03))
* **migration:** fixed a small missing semicolon ([1b60c88](https://github.com/matheusmarnt/scoutify/commit/1b60c8840c42d1427ddb11d6f854f2eca89713a8))
* **migration:** fixed a small missing semicolon ([b455fe6](https://github.com/matheusmarnt/scoutify/commit/b455fe6df670d7877c8456679a1072d0315274e5))
* phpstan/extension-installer dependency version ([b2fb840](https://github.com/matheusmarnt/scoutify/commit/b2fb840466aed8d586c9dc734d364fb442e4d33a))
* prevent start script timeout ([a071991](https://github.com/matheusmarnt/scoutify/commit/a07199141ce910f4a8e8327f5f2bd4751ae287b8))
* **tests:** correct migrations path in comment ([17ed68e](https://github.com/matheusmarnt/scoutify/commit/17ed68e908f2f98933f9eaa5ab9dcc2df232edb6))
* wire config class overrides and recents limit in blade components ([877be7e](https://github.com/matheusmarnt/scoutify/commit/877be7e9962c27d0f73fcea61090c5b4306f0e7c))
* wire includeTrashed/onlyActive filters through SearchAggregator and clean dead code in Modal ([b8e8de6](https://github.com/matheusmarnt/scoutify/commit/b8e8de6bc8ee016589dff4362edc4d98e2d22856))


### Miscellaneous Chores

* bootstrap package from spatie skeleton ([d010336](https://github.com/matheusmarnt/scoutify/commit/d010336c583cb155aae8fc0e6e7bb583a4e3ded1))
* fix author username ([0d5502e](https://github.com/matheusmarnt/scoutify/commit/0d5502ea8fe3462173a3765e8a4e3f8fdf4d242b))
* merge origin/main (resolve InstallCommand conflict) ([ecb098d](https://github.com/matheusmarnt/scoutify/commit/ecb098def75666d011303603e02c60e50717ca2a))
* remove skeleton remnants (migrations stub, factory, example test) ([9593a90](https://github.com/matheusmarnt/scoutify/commit/9593a90a730078873082671ad235671262c3a89e))

## Changelog

All notable changes will be documented in this file. The format is maintained automatically by [release-please](https://github.com/googleapis/release-please) based on [Conventional Commits](https://www.conventionalcommits.org/).
