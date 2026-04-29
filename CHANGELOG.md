# Changelog

## [1.9.0](https://github.com/matheusmarnt/scoutify/compare/v1.8.0...v1.9.0) (2026-04-29)


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
* auto-inject Searchable trait + interface via scoutify:searchable ([#19](https://github.com/matheusmarnt/scoutify/issues/19)) ([5d1d202](https://github.com/matheusmarnt/scoutify/commit/5d1d20202c15cf21dfb6955e27984e09c645ce9e))
* globalSearchBuilder hook + Meilisearch prefix search warning in DoctorCommand ([#48](https://github.com/matheusmarnt/scoutify/issues/48)) ([fefbffb](https://github.com/matheusmarnt/scoutify/commit/fefbffb293026e40145ee640256bc8ad93d2a8a1))
* **gs:** improve keyboard navigation and visual polish in global search ([#54](https://github.com/matheusmarnt/scoutify/issues/54)) ([7d05222](https://github.com/matheusmarnt/scoutify/commit/7d05222651f95853931866172ca218cd376acf5e))
* inject smart globalSearchUrl stub via scoutify:searchable ([#21](https://github.com/matheusmarnt/scoutify/issues/21)) ([d7a08eb](https://github.com/matheusmarnt/scoutify/commit/d7a08eb9bb154b091d31625b5f71caf6bcb3ec9b))
* **install:** detect environment and configure search backend automatically ([#28](https://github.com/matheusmarnt/scoutify/issues/28)) ([0ec8711](https://github.com/matheusmarnt/scoutify/commit/0ec8711098f7ed950124fb278b07f7041fbb27e2))
* **modal:** grouped search results, visual parity, power UX (v1.4.0) ([#38](https://github.com/matheusmarnt/scoutify/issues/38)) ([e33b8d2](https://github.com/matheusmarnt/scoutify/commit/e33b8d2d00dc8d122a2574e0fe76a0da5c7b83f7))
* port blade components from laravel-tall global-search ([4b24283](https://github.com/matheusmarnt/scoutify/commit/4b242836437065372a8b0690f492a685cc5dd4a5))
* port blade components from laravel-tall global-search ([b054fe6](https://github.com/matheusmarnt/scoutify/commit/b054fe6f26a25232b22286a323ee5e44f07e52fa))
* port Livewire Modal component ([9d3085e](https://github.com/matheusmarnt/scoutify/commit/9d3085e378f154bcc97a7f7e069b48e703a06ea6))
* port Livewire Modal component ([32ef41d](https://github.com/matheusmarnt/scoutify/commit/32ef41d70e7984375432f97c6cf70b6df6181580))
* **search:** accent-insensitive highlight, auto subtitle, accordion chips, and modal trigger improvements ([#56](https://github.com/matheusmarnt/scoutify/issues/56)) ([e86a381](https://github.com/matheusmarnt/scoutify/commit/e86a381aa8c5d44e6d545bf52fb41a601b3df3f0))
* zero-config auto-discovery, color tokens, keyboard nav, filter row stability ([#40](https://github.com/matheusmarnt/scoutify/issues/40)) ([83cb937](https://github.com/matheusmarnt/scoutify/commit/83cb9374892b4a46ff49fd9395f12e420a550b35))


### Bug Fixes

* add static defaults to Searchable trait and ResultDto factory ([22a6c4c](https://github.com/matheusmarnt/scoutify/commit/22a6c4cff7d448953f89f8512534d0a9af1ba9d3))
* caret for installing carbon dependency on windows workflow now escaped correctly ([2b3191e](https://github.com/matheusmarnt/scoutify/commit/2b3191ec206ef0380dec367e7fd9eee846650fd6))
* caret for installing carbon dependency on windows workflow now escaped correctly ([3843528](https://github.com/matheusmarnt/scoutify/commit/38435288dcf6c8bbec0680604b2c792b6fdb64c5))
* change branch reference from `master` to `main` ([aae91fe](https://github.com/matheusmarnt/scoutify/commit/aae91fe6ae600f7e9d19ff55bdaa0a316e4a0716))
* **ci:** extract PR number from release-please JSON output before merging ([#23](https://github.com/matheusmarnt/scoutify/issues/23)) ([0bc6bf5](https://github.com/matheusmarnt/scoutify/commit/0bc6bf5d0ad34a4df625475b8198ed94c837db3a))
* **ci:** pass --repo flag to gh pr merge so it works without checkout ([#25](https://github.com/matheusmarnt/scoutify/issues/25)) ([1379b14](https://github.com/matheusmarnt/scoutify/commit/1379b1434c4f5850c09a138b8483bdc1c2de6993))
* **ci:** remove broken auto-merge step, add workflow_dispatch trigger ([#26](https://github.com/matheusmarnt/scoutify/issues/26)) ([b286527](https://github.com/matheusmarnt/scoutify/commit/b286527a53a360a70f84b4cb60a278242e5f9886))
* **ci:** use caret version constraint for testbench ([d689d1f](https://github.com/matheusmarnt/scoutify/commit/d689d1fd513ff9727094f64819da75211e394bb6))
* **console:** replace exec with Process, propagate exit codes, add --all flag ([a912c56](https://github.com/matheusmarnt/scoutify/commit/a912c56befa0eae5876f32ce0f705a912bad80f3))
* correct instanceof check, IconResolver guard, ScoutConfigurator, ModelDiscoverer namespace and contract methods ([786b47b](https://github.com/matheusmarnt/scoutify/commit/786b47b85c7cb728a8e7d011312279614f0d59a4))
* correct trans_choice usage and add zero-count form to all lang files ([e3657d4](https://github.com/matheusmarnt/scoutify/commit/e3657d474b40a13f9392337643b579ff89db81a1))
* Deprecated message ([8aed67e](https://github.com/matheusmarnt/scoutify/commit/8aed67ef6fa85c5979d4b5607d1f43f39d96aead))
* **deps:** add PHP 8.3 and Laravel 10 compatibility ([9fa7eca](https://github.com/matheusmarnt/scoutify/commit/9fa7eca936e81d8e06f35d9706d8b6836f371758))
* expose isOpen getter in scoutifyModal to fix keyboard nav ReferenceError ([#46](https://github.com/matheusmarnt/scoutify/issues/46)) ([4722655](https://github.com/matheusmarnt/scoutify/commit/472265560e626b8bb01d5b1230fdd75af5a42d6c))
* handle null and dev- version strings in LivewireVersion::major() ([e3a3de6](https://github.com/matheusmarnt/scoutify/commit/e3a3de6f806350f85523a3d450245e625df294d8))
* illuminate/contracts dependency version to add Laravel 13 compatibility ([#380](https://github.com/matheusmarnt/scoutify/issues/380)) ([f59cfcb](https://github.com/matheusmarnt/scoutify/commit/f59cfcb495b8bc008838118fbb8d17daafe6cb03))
* **install:** pass services to sail:add as string, detect all compose file names ([#30](https://github.com/matheusmarnt/scoutify/issues/30)) ([fd99813](https://github.com/matheusmarnt/scoutify/commit/fd998131dc8c74bbcb91e1023e84631896d3928a))
* **migration:** fixed a small missing semicolon ([1b60c88](https://github.com/matheusmarnt/scoutify/commit/1b60c8840c42d1427ddb11d6f854f2eca89713a8))
* **migration:** fixed a small missing semicolon ([b455fe6](https://github.com/matheusmarnt/scoutify/commit/b455fe6df670d7877c8456679a1072d0315274e5))
* **modal:** authorize results, render highlights, restore recents ([#36](https://github.com/matheusmarnt/scoutify/issues/36)) ([3381405](https://github.com/matheusmarnt/scoutify/commit/3381405656134be0628a39c42a61b9627bce06f6))
* **modal:** remove nested Alpine x-data from dialog to fix keyboard navigation ([#52](https://github.com/matheusmarnt/scoutify/issues/52)) ([924f285](https://github.com/matheusmarnt/scoutify/commit/924f2852f5a98a88c2f6bd958c0bc27ddc5c85f4))
* **modal:** restore panel width to match reference (md:max-w-2xl) ([#34](https://github.com/matheusmarnt/scoutify/issues/34)) ([a65e889](https://github.com/matheusmarnt/scoutify/commit/a65e8898d41c241ae7f937399495696fc49329e8))
* **modal:** wire keyboard shortcuts and ship self-contained CSS partial ([#32](https://github.com/matheusmarnt/scoutify/issues/32)) ([95ac910](https://github.com/matheusmarnt/scoutify/commit/95ac91020c21d30c0e0a0aa0b755672467e5950a))
* normalize Highlighter output to NFC and update stale modal listener test ([#60](https://github.com/matheusmarnt/scoutify/issues/60)) ([eaad2e2](https://github.com/matheusmarnt/scoutify/commit/eaad2e2ff9ef705bdc641f9cc7a80c3f8f9faa55))
* phpstan/extension-installer dependency version ([b2fb840](https://github.com/matheusmarnt/scoutify/commit/b2fb840466aed8d586c9dc734d364fb442e4d33a))
* prevent start script timeout ([a071991](https://github.com/matheusmarnt/scoutify/commit/a07199141ce910f4a8e8327f5f2bd4751ae287b8))
* **tests:** correct migrations path in comment ([17ed68e](https://github.com/matheusmarnt/scoutify/commit/17ed68e908f2f98933f9eaa5ab9dcc2df232edb6))
* type chip labels respect APP_LOCALE (dynamic label resolution) ([#42](https://github.com/matheusmarnt/scoutify/issues/42)) ([0a9b0c0](https://github.com/matheusmarnt/scoutify/commit/0a9b0c0e214822573d95218e1d39d397a1e431d2))
* widen pest version constraint to support v3 and v4 ([#14](https://github.com/matheusmarnt/scoutify/issues/14)) ([1bc5622](https://github.com/matheusmarnt/scoutify/commit/1bc56227bea3e0fc5b26d8ecd206e4469d1a80b6))
* window listener in scoutifyModal init opens modal from custom triggers ([#44](https://github.com/matheusmarnt/scoutify/issues/44)) ([c9d0902](https://github.com/matheusmarnt/scoutify/commit/c9d0902ea52be1ea38c55f0129e50f890bbdfb4f))
* wire config class overrides and recents limit in blade components ([877be7e](https://github.com/matheusmarnt/scoutify/commit/877be7e9962c27d0f73fcea61090c5b4306f0e7c))
* wire includeTrashed/onlyActive filters through SearchAggregator and clean dead code in Modal ([b8e8de6](https://github.com/matheusmarnt/scoutify/commit/b8e8de6bc8ee016589dff4362edc4d98e2d22856))
* zero-config discovery pipeline — 7 cooperating defects ([#50](https://github.com/matheusmarnt/scoutify/issues/50)) ([20d12fc](https://github.com/matheusmarnt/scoutify/commit/20d12fcaa06e171cbc34dc44c0f90640b7b9319b))


### Miscellaneous Chores

* bootstrap package from spatie skeleton ([d010336](https://github.com/matheusmarnt/scoutify/commit/d010336c583cb155aae8fc0e6e7bb583a4e3ded1))
* **deps:** bump actions/checkout from 4 to 6 ([#17](https://github.com/matheusmarnt/scoutify/issues/17)) ([1c77f17](https://github.com/matheusmarnt/scoutify/commit/1c77f1776ca338f18dc04f7ebfd3b89f454d74d8))
* **deps:** bump googleapis/release-please-action from 4 to 5 ([#18](https://github.com/matheusmarnt/scoutify/issues/18)) ([c9421ce](https://github.com/matheusmarnt/scoutify/commit/c9421ce57774ea2289a83c86c4ff760c336c5dac))
* **deps:** update laravel/scout requirement from ^10.0 to ^11.1 ([#2](https://github.com/matheusmarnt/scoutify/issues/2)) ([2e3fbb8](https://github.com/matheusmarnt/scoutify/commit/2e3fbb80bea1fd88c097b2eb4c3c605299c323c4))
* fix author username ([0d5502e](https://github.com/matheusmarnt/scoutify/commit/0d5502ea8fe3462173a3765e8a4e3f8fdf4d242b))
* **main:** release 1.0.0 ([d8ecaf2](https://github.com/matheusmarnt/scoutify/commit/d8ecaf221da107c3d34abeb504c16f65866753c5))
* **main:** release 1.0.0 ([1dbb199](https://github.com/matheusmarnt/scoutify/commit/1dbb1990eb20f3a040960f93d8b64b72131ed5b5))
* **main:** release 1.0.1 ([#15](https://github.com/matheusmarnt/scoutify/issues/15)) ([ddabd20](https://github.com/matheusmarnt/scoutify/commit/ddabd20990c98695e861137d33fcdca45b4d1f2f))
* **main:** release 1.1.0 ([#20](https://github.com/matheusmarnt/scoutify/issues/20)) ([e26bb4e](https://github.com/matheusmarnt/scoutify/commit/e26bb4ee5b892062a6d782d6ca34c9b3205202c6))
* **main:** release 1.2.0 ([#22](https://github.com/matheusmarnt/scoutify/issues/22)) ([eb12073](https://github.com/matheusmarnt/scoutify/commit/eb120733ba9967757e442db46360b1a145b287ca))
* **main:** release 1.2.1 ([#24](https://github.com/matheusmarnt/scoutify/issues/24)) ([f6d23c6](https://github.com/matheusmarnt/scoutify/commit/f6d23c6f619388316618772d1dc7646fa442a30f))
* **main:** release 1.2.2 ([#27](https://github.com/matheusmarnt/scoutify/issues/27)) ([c745812](https://github.com/matheusmarnt/scoutify/commit/c74581255d47ee470dceea3441133b3baafd57b5))
* **main:** release 1.3.0 ([#29](https://github.com/matheusmarnt/scoutify/issues/29)) ([6d173ba](https://github.com/matheusmarnt/scoutify/commit/6d173ba88e6a48df7f2b24fb5fe08e34f798f3ed))
* **main:** release 1.3.1 ([#31](https://github.com/matheusmarnt/scoutify/issues/31)) ([edcae95](https://github.com/matheusmarnt/scoutify/commit/edcae95324f69d9d2647fb599e0d9863127a3cd8))
* **main:** release 1.3.2 ([#33](https://github.com/matheusmarnt/scoutify/issues/33)) ([6c604c8](https://github.com/matheusmarnt/scoutify/commit/6c604c89378517759f9c3d4c62c1b2fb78c02a0a))
* **main:** release 1.3.3 ([#35](https://github.com/matheusmarnt/scoutify/issues/35)) ([e9add2c](https://github.com/matheusmarnt/scoutify/commit/e9add2c65a6584f43ea6a0b14c2bb735563be6b1))
* **main:** release 1.3.4 ([#37](https://github.com/matheusmarnt/scoutify/issues/37)) ([e20cdd3](https://github.com/matheusmarnt/scoutify/commit/e20cdd341d657bb488e1b9923cfd66080a000254))
* **main:** release 1.4.0 ([#39](https://github.com/matheusmarnt/scoutify/issues/39)) ([9d57529](https://github.com/matheusmarnt/scoutify/commit/9d575295a3f2a6c44287ae6d2a2b73f5f7035c19))
* **main:** release 1.5.0 ([#41](https://github.com/matheusmarnt/scoutify/issues/41)) ([55b49c3](https://github.com/matheusmarnt/scoutify/commit/55b49c3ec97ed90051331f80f97066cee81af888))
* **main:** release 1.5.1 ([#43](https://github.com/matheusmarnt/scoutify/issues/43)) ([c5f2421](https://github.com/matheusmarnt/scoutify/commit/c5f2421bce5557d46305b83274a110067d45f09b))
* **main:** release 1.5.2 ([#45](https://github.com/matheusmarnt/scoutify/issues/45)) ([24bfe43](https://github.com/matheusmarnt/scoutify/commit/24bfe43c4042fce871d1d00a557252e564407e46))
* **main:** release 1.5.3 ([#47](https://github.com/matheusmarnt/scoutify/issues/47)) ([d02dab8](https://github.com/matheusmarnt/scoutify/commit/d02dab8fab41ac9a58a277a320fa2e0c43de2491))
* **main:** release 1.6.0 ([#49](https://github.com/matheusmarnt/scoutify/issues/49)) ([8448c01](https://github.com/matheusmarnt/scoutify/commit/8448c01ff21be452b49a4a095568bf72d4758160))
* **main:** release 1.6.1 ([#51](https://github.com/matheusmarnt/scoutify/issues/51)) ([9febe71](https://github.com/matheusmarnt/scoutify/commit/9febe713fe9cb0d2aa895e77140b6003731bb376))
* **main:** release 1.6.2 ([#53](https://github.com/matheusmarnt/scoutify/issues/53)) ([f66f66d](https://github.com/matheusmarnt/scoutify/commit/f66f66d3d579568b7961b6b60748242a29079389))
* **main:** release 1.7.0 ([f51008e](https://github.com/matheusmarnt/scoutify/commit/f51008e174b5385ec239b4cd224241f8b39cf710))
* **main:** release 1.7.0 ([2744e7e](https://github.com/matheusmarnt/scoutify/commit/2744e7e61c02d733d13afc35c60f319835af4c6e))
* **main:** release 1.8.0 ([cbd2160](https://github.com/matheusmarnt/scoutify/commit/cbd2160dcd8165249e13ae4d89665cc2a269d37f))
* **main:** release 1.8.0 ([842741b](https://github.com/matheusmarnt/scoutify/commit/842741b65c1420ef37b7fb3ba9b0611417584793))
* merge origin/main (resolve InstallCommand conflict) ([ecb098d](https://github.com/matheusmarnt/scoutify/commit/ecb098def75666d011303603e02c60e50717ca2a))
* remove skeleton remnants (migrations stub, factory, example test) ([9593a90](https://github.com/matheusmarnt/scoutify/commit/9593a90a730078873082671ad235671262c3a89e))

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
