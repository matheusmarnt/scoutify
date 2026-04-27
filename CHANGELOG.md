# Changelog

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
