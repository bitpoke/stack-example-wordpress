# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [0.52.5] - 2025-06-30
### Changed
- Internal updates.

## [0.52.4] - 2025-06-24
### Changed
- Update dependencies. [#42919]

## [0.52.3] - 2025-06-23
### Changed
- Update package dependencies. [#44020]

## [0.52.2] - 2025-06-16
### Changed
- Update dependencies. [#42919]

## [0.52.1] - 2025-06-10
### Changed
- Update dependencies. [#42919]

## [0.52.0] - 2025-06-09
### Fixed
- Address `body_class` filter fatal in older versions of the Blaskan theme. [#43797]
- Tests: Ensure method param count matches PHPUnit data providers. [#43815] [#43828]

## [0.51.0] - 2025-06-05
### Changed
- Update package dependencies. [#43734] [#43747]

### Removed
- Classic Themes: Replace the Core Search widget instead of adding a new widget to the main sidebar. [#43450]

## [0.50.2] - 2025-06-02
### Changed
- Update package dependencies. [#43710]

### Fixed
- Protect against improper calls to `the_title()` filter. [#43511]

## [0.50.1] - 2025-05-26
### Changed
- Update package dependencies. [#43516] [#43578]

## [0.50.0] - 2025-05-19
### Added
- Add highlighting of search term in returned search results. [#43110]
- Allow new Inline Search to be enabled. [#43457]

### Changed
- Update package dependencies. [#43398]

### Fixed
- Mitigate bug with certain P2 theme [#43503]

## [0.49.0] - 2025-05-15
### Added
- Add query filters support to Inline Search. [#43441]

## [0.48.0] - 2025-05-12
### Added
- Surface search corrections when correcting search terms. [#42473]

### Changed
- Update package dependencies. [#43400]
- Use functions from the `@automattic/number-formatters` package. [#42796]

## [0.47.24] - 2025-05-05
### Changed
- Update package dependencies. [#43320] [#43326] [#43350]

### Fixed
- Linting: Address final rules in WordPress Stylelint config. [#43296]
- Linting: Do additional stylesheet cleanup. [#43247]

## [0.47.23] - 2025-04-28
### Fixed
- Linting: Fix more Stylelint violations. [#43213]
- Linting: Remove outdated vendor prefixes in stylesheets. [#43219]

## [0.47.22] - 2025-04-14
### Fixed
- Fix errors caused by malformed order arguments. [#42897]
- Linting: Format SCSS imports consistently. [#43018]
- Linting: Update stylesheets to use WordPress rules for fonts and colors. [#42920] [#42928]
- Linting: Use double colon notation for pseudo-element selectors. [#43019]

## [0.47.21] - 2025-04-07
### Changed
- Code: First pass of style coding standards. [#42734]

## [0.47.20] - 2025-04-04
### Changed
- Update dependencies. [#42820]
- Update package dependencies. [#42809]

### Fixed
- Admin menu registration: Allow child classes to overwrite priority. [#42814]

## [0.47.19] - 2025-03-31
### Changed
- Update dependencies. [#42678]

### Fixed
- Admin Menu: Ensure Akismet menu item shows alongside standalone plugin menus. [#42776]
- Components: Prevent deprecation notices by adding `__next40pxDefaultSize` to controls. [#42677]

## [0.47.18] - 2025-03-24
### Changed
- Update dependencies. [#42564]

### Fixed
- Search: Ensure the count of returned results is shown after searching. [#42570]

## [0.47.17] - 2025-03-18
### Changed
- Update package dependencies. [#42511]

## [0.47.16] - 2025-03-17
### Changed
- Update dependencies. [#42498]

## [0.47.15] - 2025-03-12
### Added
- Provide connection data to footer component. [#42000]

### Changed
- Update package dependencies. [#42384]

## [0.47.14] - 2025-03-10
### Changed
- Internal updates.

## [0.47.13] - 2025-03-03
### Changed
- Update package dependencies. [#42163]

## [0.47.12] - 2025-02-24
### Fixed
- Code: Prevent dynamic class properties. [#41857]

## [0.47.11] - 2025-02-17
### Changed
- Update dependencies. [#39855]

## [0.47.10] - 2025-02-11
### Changed
- Update dependencies. [#39855]

## [0.47.9] - 2025-02-10
### Changed
- Updated package dependencies. [#41491] [#41577]

## [0.47.8] - 2025-02-03
### Changed
- Updated package dependencies. [#41286]

## [0.47.7] - 2025-01-27
### Changed
- Internal updates.

## [0.47.6] - 2025-01-20
### Changed
- Code: Use function-style exit() and die() with a default status code of 0. [#41167]
- Updated package dependencies. [#41099]

## [0.47.5] - 2025-01-06
### Changed
- Updated package dependencies. [#40784] [#40792] [#40797] [#40813] [#40843]

## [0.47.4] - 2024-12-16
### Changed
- Updated package dependencies. [#40564]

### Fixed
- Fix missing instant search dialog for themes like Rebalance. [#40472]

## [0.47.3] - 2024-12-09
### Changed
- Updated package dependencies. [#40363]

## [0.47.2] - 2024-11-26
### Changed
- Update dependencies. [#39855]

## [0.47.1] - 2024-11-25
### Changed
- Updated dependencies. [#40286]
- Updated package dependencies. [#40288]

## [0.47.0] - 2024-11-18
### Removed
- General: Update minimum PHP version to 7.2. [#40147]

## [0.46.0] - 2024-11-11
### Added
- Search: Add ability to customize results [#36378]

### Changed
- Classic Widget: update asset enqueuing strategy to ensure compatibility with the Elementor plugin. [#39820]
- Exclude corejs `exnext.iterator.` polyfills to avoid bloating the bundle. [#40060]
- Updated package dependencies. [#39999] [#40060]

## [0.45.12] - 2024-11-04
### Added
- Enable test coverage. [#39961]

## [0.45.11] - 2024-10-29
### Changed
- Components: Add __nextHasNoMarginBottom to BaseControl-based components, preventing deprecation notices. [#39877]

## [0.45.10] - 2024-10-28
### Changed
- Updated package dependencies. [#39910]

## [0.45.9] - 2024-10-21
### Changed
- Update dependencies. [#39781]

## [0.45.8] - 2024-10-14
### Changed
- Updated package dependencies. [#39707]

### Fixed
- Add `key` to tag and cat lists in `SearchResultMinimal` to improve behavior if lists change at runtime. [#39709]

## [0.45.7] - 2024-10-07
### Changed
- Updated package dependencies. [#39592] [#39594] [#39595] [#39639]

## [0.45.6] - 2024-09-30
### Changed
- Update dependencies. [#39528]

## [0.45.5] - 2024-09-23
### Changed
- Instant search: Use triggering element's document instead of the global `document`. [#39364]
- Search: reduce database calls by ensuring plan support before checking options [#39384]

### Fixed
- Use React properties rather than pass-throughs (e.g. `className` rather than `class`). [#39414]

## [0.45.4] - 2024-09-16
### Changed
- Updated package dependencies. [#39332]

### Fixed
- Move trailing spaces out of i18n messages. [#39305]

## [0.45.3] - 2024-09-10
### Changed
- Updated package dependencies. [#39302]

## [0.45.2] - 2024-09-09
### Changed
- Updated package dependencies. [#39278]

## [0.45.1] - 2024-09-05
### Changed
- Updated package dependencies. [#39176]

## [0.45.0] - 2024-09-02
### Added
- Search: allow customizing highlighted fields [#39053]

### Changed
- Admin menu: change order of Jetpack sub-menu items [#39095]
- Updated package dependencies. [#39111]

### Fixed
- Search: Use the user's locale when formatting request limits. [#38946]

## [0.44.17] - 2024-08-26
### Changed
- Updated package dependencies. [#39004]

## [0.44.16] - 2024-08-21
### Changed
- Internal updates.

## [0.44.15] - 2024-08-19
### Changed
- Update dependencies. [#38861] [#38662]

### Fixed
- Fix incorrect next-version tokens in php `@since` and/or `@deprecated` docs. [#38869]

## [0.44.14] - 2024-08-05
### Changed
- React compatibility: Changing ReactDOM.render usage to be via ReactDOM.createRoot. [#38649]

## [0.44.13] - 2024-07-22
### Changed
- Update dependencies. [#38402]

## [0.44.12] - 2024-07-08
### Changed
- Updated package dependencies. [#38132] [#38133]

## [0.44.11] - 2024-06-24
### Changed
- Update dependencies. [#37979]

## [0.44.10] - 2024-06-17
### Changed
- Updated package dependencies. [#37796] [#37860]

## [0.44.9] - 2024-06-10
### Changed
- Change codebase to use clsx instead of classnames. [#37708]
- Updated package dependencies. [#37669]

## [0.44.8] - 2024-05-27
### Changed
- Update dependencies. [#37356]

## [0.44.7] - 2024-05-20
### Changed
- Updated package dependencies. [#37379] [#37380] [#37382]

## [0.44.6] - 2024-05-13
### Changed
- Update dependencies. [#37280]

## [0.44.5] - 2024-05-06
### Added
- Add missing package dependencies. [#37141]

### Changed
- Updated package dependencies. [#37147]

### Deprecated
- Deprecate `jetpack_search` property on `Search_Widget`. It has never been set to a non-null value since the package was created. [#37201]

## [0.44.4] - 2024-04-29
### Fixed
- Fix connection flow for new sites. [#37085]

## [0.44.3] - 2024-04-25
### Changed
- Internal updates.

## [0.44.2] - 2024-04-22
### Changed
- Internal updates.

## [0.44.1] - 2024-04-15
### Changed
- Update dependencies. [#36848]

## [0.44.0] - 2024-04-08
### Changed
- Updated package dependencies. [#36760]
- Update to the most recent version of Color Studio, 2.6.0. [#36751]

### Removed
- Removed add Search license link for simple sites due to My Jetpack inavailability. [#36667]

## [0.43.8] - 2024-03-27
### Changed
- Updated package dependencies. [#36585]

## [0.43.7] - 2024-03-25
### Changed
- Internal updates.

## [0.43.6] - 2024-03-18
### Changed
- Internal updates.

## [0.43.5] - 2024-03-12
### Changed
- Updated package dependencies. [#36325]
- Update to the most recent version of the @automattic/calypso-color-schemes package. [#36187]
- Update to the most recent version of the @automattic/calypso-color-schemes package. [#36227]

## [0.43.4] - 2024-03-04
### Changed
- Update dependencies. [#36113]
- Updated package dependencies.

## [0.43.3] - 2024-02-27
### Changed
- Update dependencies. [#35170]

## [0.43.2] - 2024-02-19
### Changed
- Internal updates.

## [0.43.1] - 2024-02-13
### Changed
- Updated package dependencies. [#35608]

## [0.43.0] - 2024-02-05
### Changed
- Updated package dependencies. [#35384] [#35385]
- Use blog ID instead of site slug in checkout links. [#35000]

## [0.42.1] - 2024-01-29
### Changed
- Update dependencies. [#35170]

## [0.42.0] - 2024-01-25
### Added
- Add price and rating to default sort options. [#35167]

## [0.41.1] - 2024-01-22
### Changed
- Update dependencies. [#35117]

## [0.41.0] - 2024-01-04
### Added
- Search: Add a filter to prevent tracking cookie reset. [#34803]

### Changed
- Updated package dependencies. [#34815]

## [0.40.4] - 2023-12-20
### Changed
- Updated package dependencies. [#34694]

## [0.40.3] - 2023-12-11
### Changed
- Updated package dependencies. [#34416]

## [0.40.2] - 2023-12-03
### Changed
- Updated package dependencies. [#34411] [#34427]

### Fixed
- Instant Search: Fixed the title layout for product layout. [#34263]

## [0.40.1] - 2023-11-24
### Changed
- Replaced usage of strpos() with str_contains(). [#34137]
- Replaced usage of substr() with str_starts_with() and str_ends_with(). [#34207]

## [0.40.0] - 2023-11-20
### Changed
- Replaced usage of strpos() with str_starts_with(). [#34135]
- Updated required PHP version to >= 7.0. [#34192]

## [0.39.7] - 2023-11-14
### Changed
- Updated package dependencies. [#34093]

## [0.39.6] - 2023-11-13
### Fixed
- Switched to classic search for iOS 15 or lower. [#33929]

## [0.39.5] - 2023-11-03

## [0.39.4] - 2023-10-30
### Changed
- Instant Search: rely on browsers' native lazy loading functionality when we want to lazy load images. [#33817]

## [0.39.3] - 2023-10-23
### Changed
- Updated package dependencies. [#33646] [#33687]

## [0.39.2] - 2023-10-16
### Added
- Added HEIC (`*.heic`) to list of images types allowed to be passed through Photon during instant search. [#33494]

### Changed
- Updated package dependencies. [#33429, #33569]

### Fixed
- Search: Fixed excluded types option is not available under certain circumstances. [#33548]

## [0.39.1] - 2023-10-10
### Changed
- Updated package dependencies. [#33428]

## [0.39.0] - 2023-10-03
### Added
- Add a setting for Jetpack AI Search [#33432]

## [0.38.8] - 2023-09-19
### Changed
- Updated Jetpack submenu sort order so individual features are alpha-sorted. [#32958]
- Updated package dependencies. [#33001]

## [0.38.7] - 2023-09-11
### Changed
- General: remove WP 6.1 backwards compatibility checks [#32772]

## [0.38.6] - 2023-09-04
### Changed
- Updated package dependencies. [#32803] [#32804]

## [0.38.5] - 2023-08-28
### Changed
- Updated package dependencies. [#32605]

## [0.38.4] - 2023-08-21
### Changed
- Use the new method to render Connection initial state. [#32499]

## [0.38.3] - 2023-08-09
### Changed
- Updated package dependencies. [#32166]

## [0.38.2] - 2023-07-25
### Changed
- Updated package dependencies. [#31923] [#32040]
- Update props passed to JetpackFooter [#31627]

## [0.38.1] - 2023-07-17
### Changed
- Updated package dependencies. [#31785]

## [0.38.0] - 2023-07-10
### Removed
- Disable Customizer integration for Instant Search if the site is using a block-based theme [#31731]

## [0.37.4] - 2023-07-05
### Changed
- Updated package dependencies. [#31659]

## [0.37.3] - 2023-06-26
### Changed
- Updated package dependencies. [#31468]

## [0.37.2] - 2023-06-06
### Changed
- Updated package dependencies. [#31129]

## [0.37.1] - 2023-05-22
### Changed
- PHP Compatibility: fix dynamic property deprecation notices [#30786]

## [0.37.0] - 2023-05-15
### Added
- Jetpack Search: add "Open overlay from filter links" option, which allows users to enable/disable the opening of the search overlay when filtering from a separate widget outside of the Jetpack Search Sidebar [#30455]

### Changed
- PHP 8.1 compatibility updates [#30564]

## [0.36.3] - 2023-05-02
### Changed
- Updated package dependencies.

## [0.36.2] - 2023-05-01
### Changed
- Internal updates.

## [0.36.1] - 2023-04-25

- Minor internal updates.

## [0.36.0] - 2023-04-17
### Added
- Search: Restore Tracks to Instant Search [#29979]

### Changed
- Updated package dependencies. [#30019]

## [0.35.0] - 2023-04-10
### Added
- Add Jetpack Autoloader package suggestion. [#29988]
- Implemented a "tabbed" variation for static filters. This adds tabs on top of the results for each filter group. [#29811]

## [0.34.4] - 2023-04-04
### Changed
- Updated package dependencies. [#29854, #29857]

## [0.34.3] - 2023-04-03
### Changed
- Internal updates.

## [0.34.2] - 2023-03-28
### Changed
- Minor internal updates.

## [0.34.1] - 2023-03-27
### Added
- (Backup, Boost, Search, Social) Add links on upgrade pages to activate a license key, if you already have one. [#29443]

### Changed
- Updated package dependencies. [#29632]

## [0.34.0] - 2023-03-20
### Changed
- Updated package dependencies. [#29471]

### Removed
- Remove Tracks from Instant Search [#29490]

## [0.33.3] - 2023-03-13
### Fixed
- Search Dashboard: fix wrong return URL when site already has a valid subscription [#29409]

## [0.33.2] - 2023-03-08
### Changed
- Updated package dependencies. [#29216]

### Fixed
- Fix bad check against isMultiSite resulting in extended search format not showing expected additional post details. [#29179]

## [0.33.1] - 2023-02-28
### Added
- Search: Add JITM container [#29106]

### Changed
- Updated package dependencies.

### Fixed
- Fix CSS for products in search results [#29110]

## [0.33.0] - 2023-02-20
### Added
- Adds a "show post date" selector to Search customberg, allows non-multisite sites to show the post date in the "expanded" search result format. [#28918]

### Fixed
- Fixed unit tests without internet [#28985]
- Separated authors by comments if there are multiple authors in a search result [#28975]

## [0.32.0] - 2023-02-15
### Added
- Search: Adds assigned post categories as classnames to search results [#28816]

### Changed
- Update to React 18. [#28710]

## [0.31.7] - 2023-02-08
### Changed
- Updated package dependencies. [#28682]

## [0.31.6] - 2023-01-30
### Changed
- Increased Jetpack Search filters caching from one hour to four hours [#28632]

## [0.31.5] - 2023-01-26
### Changed
- Minor internal updates.

## [0.31.4] - 2023-01-23
### Changed
- Start using utilities from Status package to detect whether a site is private or "coming-soon" (unlaunched). [#28328]

### Fixed
- Clean up JavaScript eslint issues. [#28441]

## [0.31.3] - 2023-01-11
### Changed
- Updated package dependencies.

## [0.31.2] - 2022-12-19
### Changed
- Updated package dependencies. [#27887, #27916, #27962]

### Fixed
- Declare field `REST_Controller->plan`. [#27949]
- Improve PHP 8.2 compatibility. [#27968]

## [0.31.1] - 2022-12-06
### Changed
- Updated package dependencies. [#27340, #27688, #27696, #27697]

## [0.31.0] - 2022-11-28
### Added
- Add an optional global flag that prevents instant search from modifying the url as the search query is being written or modified. [#27264]
- Add prompt for Jetpack Search Free plan upgrade for users who exceed limits. [#27462]
- Search: Add checkmark icon for resolved topics [#27586]

### Changed
- Suggest free Jetpack Search plan instead of the paid one to classic search users [#27372]
- Updated package dependencies. [#26069]

## [0.30.2] - 2022-11-10
### Changed
- Updated package dependencies. [#27319]

### Fixed
- Jetpack Search: Fixed link to language support documentation [#27287]

## [0.30.1] - 2022-11-07
### Changed
- Updated package dependencies. [#27278]

## [0.30.0] - 2022-11-01
### Added
- Search: add blog ID filtering and `blogIdFilteringLabels` option [#27120]

### Changed
- Updated package dependencies. [#27089]

## [0.29.2] - 2022-10-26
### Fixed
- Search: Fix typo in CTA [#27044]
- Search: hide meters etc for Classic Search [#27073]

## [0.29.1] - 2022-10-25
### Added
- Search: add purchase tracking [#26981]

### Changed
- Search: now support 38 languages [#27025]
- Tweak colophon link to Search upgrade page [#26952]
- Updated package dependencies. [#26705]

### Fixed
- Hide Jetpack logo toggle, enforce display for free plans [#26951]
- Search: Hide post-purchase tooltips for free plan activation [#26953]

## [0.29.0] - 2022-10-19
### Added
- Search: enable new pricing if pricing_version is set to 202208 from API [#26900]

### Changed
- Updated package dependencies. [#26808]
- Use API values for paid allowance limits shown in the new pricing table [#26895]

## [0.28.0] - 2022-10-17
### Changed
- Check free plan by product_slug and disable Module Control only on free plan. [#26849]
- Fetch checkoutProductUrl for upgrade flow redirection. [#26848]
- Introduce GET param just_upgraded and localStorage upgrade_tooltip_finished to determine the tooltips showing. [#26828]
- Refactor upgrade tooltips. [#26851]
- Search: always add Search Dashboard page even when submenu is hidden [#26807]
- Search Dashboard: Add missing CTAs and associated logic. [#26852]
- Search Dashboard: Add siteTitle to plugin/package data. [#26816]
- Search Dashboard: Better handling of "Unlimited" requests. [#26813]
- Search Dashboard: Disable adaptive colors for Unlimited requests state. [#26850]
- Search Dashboard: Fix responsive layouts for usage meters. [#26815]
- Search Dashboard: Remove code duplication for support link. [#26811]
- Search Dashboard: Update Plan Usage section to remove info icons. [#26812]
- Updated package dependencies. [#26851]
- Use adaptive coloring for donut meters in search dashboard [#26847]

## [0.27.0] - 2022-10-13
### Added
- Add connection error notice to the Search plugin. [#26778]
- Introduce shadowed IconTooltips to tooltips for highlighting upgraded usage limits. [#26790]

### Changed
- Add upgrade button link with applying upgrade actions. [#26737]
- Search Dashboard: Update Plan Usage section to more closely match design. [#26783]
- Updated package dependencies. [#26790]

### Fixed
- Search: wpcom sites should not be considered as connected [#26835]

## [0.26.0] - 2022-10-11
### Added
- Search Dashboard: Add support for conditional CUTs. [#26656]

### Changed
- Apply ContextualUpgradeTrigger with checkout CTA [#26633]
- Apply tier and latest month usage to plan summary [#26695]
- Apply upgrade trigger displaying conditions and messages from API data [#26712]
- Stop controls when usage over plan limit months. [#26732]
- Updated package dependencies. [#26640]

### Fixed
- Fixed search dashboard for simple sites. [#26713]
- Search Dashboard: Add support link for plan limits. [#26694]
- Search Dashboard: Refactoring of new UI components. [#26723]
- Search Dashboard: Rename variables to avoid jargon. [#26691]

## [0.25.0] - 2022-10-05
### Added
- Added string translations used for the new pricing table [#26595]
- Components: Added UsageMeter along with DonutMeterContainer JS component. [#26344]
- Search: add post type breakdown endpoint [#26463]

### Changed
- Apply JetpackSearchLogo and replace footer link to upsell page [#26481]
- Search: add connection support for new pricing page [#26573]
- Search Dashboard: Added first run and usage section components. [#26639]
- Updated package dependencies. [#26457]
- Use library method for number formatting. [#26636]

### Fixed
- Search: fix redirection after purchase [#26598]

## [0.24.0] - 2022-10-03
### Changed
- Fix styles of Control components on page side to avoid deprecating component styles affecting. [#26567]

## [0.23.0] - 2022-09-27
### Added
- Added stories for the NoticeBox component [#26367]
- Search: added free_tier and new_pricing_202208 to gate new pricing features [#26338]

### Changed
- Introduce PricingTable to update Upsell page [#26408]
- Move JetpackColophon to bottom of SearchResults [#26320]
- Updated mentions of "Search" with "Jetpack Search" as part of rebranding project [#26410]
- Updated package dependencies.

### Fixed
- Fix error message styling in Instant Search overlay. [#26339]

## [0.22.2] - 2022-09-20
### Changed
- Updated package dependencies.

### Fixed
- Fixes the issue where search results are not loaded in customizer [#26212]

## [0.22.1] - 2022-09-13
### Changed
- Updated package dependencies. [#26176]

## [0.22.0] - 2022-09-08
### Added
- Search: added support to search through multiple sites [#26046]

### Changed
- Updated package dependencies.

### Fixed
- Instant Search: Update CSS styles to removes uses of "!important" where possible. [#25825]
- Search: Allow non-owner admins to see search dashboard [#26100]
- Search: fixed Automattic link in footer when Jetpack plugin does not exist [#26045]

## [0.21.1] - 2022-08-30
### Added
- Instant Search: add focus border to search input field [#25304]

### Changed
- Updated package dependencies. [#25694]

### Fixed
- Keep widget preview with settings [#25778]

## [0.21.0] - 2022-08-25
### Changed
- Search: revert "Search should not require user connection" [#25802]
- Updated package dependencies. [#25814]

## [0.20.0] - 2022-08-23
### Added
- Add author filtering support [#25409]

### Changed
- Updated package dependencies. [#25338, #25339, #25377, #25628, #25762, #25769]

### Fixed
- Instant Search: Add focus styles for easier keyboard navigation. (a11y) [#25671]
- Instant Search: Remove redundant links from search results. (a11y) [#25699]

## [0.19.0] - 2022-08-16
### Added
- Instant Search: always use submit overlay trigger if user prefers reduced motion. [#25413]
- Instant Search: only show animation to users who have not chosen reduced motion. [#25630]
- Instant Search: user friendly error messaging. [#25433]

### Fixed
- Instant Search: fix button styling in Twenty Twenty One theme. [#25631]
- Instant Search: fix the display order on mobile to match the tab order. [#25415]
- Instant Search: use classname rather than ID for styling sort select. [#25632]

## [0.18.0] - 2022-08-09
### Added
- Search: added proper error messages for search module control [#24476]

### Changed
- Instant Search: Updates dark mode active link color for increased contrast [#25343]
- Search: changed to only require site level connection [#24477]
- Updated package dependencies. [#24477, #25265]

### Removed
- Search: remove 'results' overlay trigger [#25393]

### Fixed
- Dashboard: updated Instant Search description to match changes in default overlay trigger [#25303]
- Instant Search: Constrain tab loop to overlay when visible. [#25288]
- Instant Search: Make "Clear filters" button accessible. [#25342]

## [0.17.1] - 2022-08-03
### Added
- Instant Search: Adds descriptions to post type icons for accessibility purposes [#25323]
- Record Meter: add info link to docs [#25002]

### Changed
- Updated package dependencies. [#25300, #25315]

### Fixed
- Instant Search: Improve accessibility of seach options controls.
- Search: ensure overlay trigger is consistent for new installs [#25093]

## [0.17.0] - 2022-07-29
### Added
- Record Meter: make feature available to all users
- Search: re-add 'Media' to 'Excluded Post Types'

## [0.16.2] - 2022-07-26
### Added
- Dashboard: added support WPCOM simple sites [#25094]

### Changed
- Instant search: updates overlay focus elements for design consistency [#25260]
- Updated package dependencies. [#25144] [#25147] [#25158]

### Fixed
- Instant Search: Fix keyboard handling of sort options. [#25163]
- Instant Search: prevent hidden submit button appearing on focus [#25136]
- Search Dashboard: Fixed layout issues when Hello Dolly plugin is active. [#25139]

## [0.16.1] - 2022-07-19
### Changed
- Updated package dependencies. [#25086]

### Fixed
- Customberg: re-add collapsing wp-admin sidebar to prevent menu overlap issue [#25060]
- Fixed the currency code missing issue for the upsell page [#25068]

## [0.16.0] - 2022-07-12
### Added
- Hide unsupported taxonomies from widget settings [#24823]

### Changed
- Updated package dependencies. [#25055]

### Fixed
- Fix irrelevant widgets in overlay sidebar [#24824]
- Hide 'Media' from 'Excluded Post Types' because we don't index them [#24822]
- Record Meter: rely less on last_indexed_date from API [#24967]
- Use consistent number formatting in Record Meter [#25003]

## [0.15.4] - 2022-07-06
### Added
- Record Meter: adds labels to custom post type breakdown [#24876]

### Changed
- Updated package dependencies. [#24923]

### Removed
- Record Meter: Remove dismissable functionality and design from notice boxes [#24922]

### Fixed
- Reset letter spacing for header tags [#24954]
- Restores support for multiple post types in the Jetpack Search Widget. [#24868]

## [0.15.3] - 2022-06-28
### Changed
- Record meter: format the numbers used in notice. [#24810]
- Record Meter: updated notice box content when site is not indexed [#24785]
- Search: use centralized search pricing API [#24795]
- Updated package dependencies. [#24826]

### Fixed
- Added My Jetpack package as dependency [#24826]

## [0.15.2] - 2022-06-21
### Changed
- Record Meter: Separated component styles [#24732]
- Renaming master to trunk. [#24661]

## [0.15.1] - 2022-06-14
### Changed
- Search record meter: pass sorted records to RecordMeterBar component [#24731]
- Updated package dependencies. [#24529]

## [0.15.0] - 2022-06-08
### Changed
- Record Meter: switches from using chartJS to the Jetpack RecordMeterBar component [#24322]
- Reorder JS imports for `import/order` eslint rule. [#24601]
- Search record meter: changed the notice box to include a header text [#24346]
- Search widget: use 'Search (Jetpack)' as wdiget name and remove `jetpack_widget_name` [#24577]
- Updated package dependencies. [#24510]

### Removed
- Search: don't open modal if only sort parameter is set [#24576]
- Search: Removed unused chart.js library [#24658]

### Fixed
- Search: avoid query on component mount when the overlay might not be visible [#24609]
- Search: fix gridicon color in dark mode on Customberg [#24668]

## [0.14.2] - 2022-05-30
### Changed
- Updated package dependencies
- Updated package dependencies.
- Use the Checkout workflow to establish the connection and make the purchase

### Fixed
- Avoid filter from being added multiple times

## [0.14.1] - 2022-05-24
### Added
- Allow plugins to filter the list of available modules. Only activate and consider active modules that are available [#24454]

### Changed
- Search: Use Modules methods for activating and deactivating the Search module. [#24385]
- Updated package dependencies. [#24449]

## [0.14.0] - 2022-05-19
### Removed
- Search: Disable auto-collapsing the wp-admin sidebar within Customberg [#24399]

## [0.13.4] - 2022-05-18
### Changed
- Adjust translatable string [#24357]
- Record Meter: switch noticebox persistence storage from localStorage to sessionStorage [#24348]
- Record Meter design updates [#24225]
- Search package: search dashboard refactoring [#24266]
- Updated package dependencies. [#23795] [#24153] [#24306] [#24372]

## [0.13.3] - 2022-05-10
### Added
- Add missing JS dep on `core-js`. [#24288]

### Changed
- Search: refactored Settings to expose the settings array for sync [#24167]
- Updated package dependencies. [#24189] [#24204] [#24302] [#24276] [#24296] [#24301]

### Fixed
- Search: Fix left padding for upsell page [#24285]
- Search: handle tiers without a record limit in Record Meter [#24264]

## [0.13.2] - 2022-05-04
### Added
- Add missing JavaScript dependencies. [#24096]

### Changed
- Remove use of `pnpx` in preparation for pnpm 7.0. [#24210]
- Updated package dependencies. [#24095] [#24230] [#24198] [#24228]

### Deprecated
- Moved the options class into Connection. [#24095]

### Fixed
- Adapt Record Meter to change in API response format [#24107]
- Search: Bundle vendor assets within the main chunk [#24068]
- Search: Fix search for private WoA sites [#24099]
- Search: reset border-radius for search buttons [#24100]

## [0.13.1] - 2022-04-26
### Added
- Search: added upsell page

### Changed
- Updated package dependencies.
- Update package.json metadata.

## [0.13.0] - 2022-04-19
### Added
- Search: add class to retrieve search product information
- Search: Add count estimation function
- Search: added API support for search product tier pricing

### Changed
- PHPCS: Fix `WordPress.Security.ValidatedSanitizedInput`
- Record meter: updates noticeboxes to be dismissable & styled
- Search Record Meter updates formatting
- Use new shared Gridicons component and shared Modules library

## [0.12.3] - 2022-04-12
### Added
- Added deprecated methods as a safety.

### Changed
- Updated package dependencies.

### Fixed
- Search: auto config no longer overrides option if it exists.

## [0.12.2] - 2022-04-06
### Added
- Adds API data to record meter chart.

### Changed
- Janitorial: Refactor classes into shared package.
- Updated package dependencies.

### Removed
- Removed tracking dependency.

### Fixed
- Search: allow Search submenu to be added only once

## [0.12.1] - 2022-03-31
### Fixed
- Search: fixed search submenu is shown because compatibility file is loaded too late.

## [0.12.0] - 2022-03-29
### Added
- Add selector for retrieving last indexed date
- Adds notice box component to record meter
- Search: Migrated tests from Jetpack plugin

### Changed
- Microperformance: Use === null instead of is_null
- Search: connection states
- Updated package dependencies

### Fixed
- Fixed lints found after fixing ESLint config
- Search: address feeback for #23477
- Search: move Jetpack plugin compatibility to the package

## [0.11.3] - 2022-03-24
### Added
- Search: adds a record count above the record meter chart.

### Fixed
- Deactivation: Do not attempt to redirect on a behind-the-scene deactivation.

## [0.11.2] - 2022-03-23
### Added
- adds basic structure for record meter with dummy data

### Changed
- Centralized all intializing logic
- Search dashboard: changed condition to always show dashboard submenu
- Updated package dependencies
- Use Migrated GlotPress locale classes from compat pkg.

### Fixed
- Search: fixed cli and package version reporting broken in #23435

## [0.11.1] - 2022-03-15
### Changed
- Fixed minor product defects
- Search: moved globals to a class for sake of autoloading correctly
- Search package: Updated Gridicon dependancy to use local version
- Updated package dependencies.

## [0.11.0] - 2022-03-08
### Changed
- Components: update attributes used within the Button component to match recent deprecations and changes.
- Move customizer integration into search package
- search: move record meter location on dashboard

### Fixed
- Ensure that WP CLI is present before extending the class.
- Ensure the Customizer classes are loaded.

## [0.10.0] - 2022-03-02
### Added
- Search: add chart.js package to dependencies
- Search: fetch search stats endpoint in wp-admin dashboard

### Changed
- Search: Renamed Customberg class file name
- Updated package dependencies.

### Fixed
- Fix various notices shown for Customberg
- Search package: i18n support for auto added search block label and button

## [0.9.1] - 2022-02-25
### Fixed
- Search: Fixed a regression that prevented modal from being spawned by link clicks

## [0.9.0] - 2022-02-22
### Added
- Bump package versions.
- Search: add stats endpoint to REST controller

### Changed
- Search package: refactor `auto_config_search` and run it from activation API

### Fixed
- Search package: fix auto config doesn't add search input for block themes

## [0.8.0] - 2022-02-16
### Added
- Add babel/runtime to dev dependencies
- Add tier maximum records for Record Meter
- Clicking outside overlay now closes overlay

### Changed
- Change `instance` function for improved compatibility
- Updated package dependencies.

### Fixed
- Form: avoid React warning.
- Improve display of colorpicker in Customberg
- Fixed undefined index features
- Should not exclude widget js in package distribution

## [0.7.0] - 2022-02-09
### Added
- Search Dashboard: add scaffolding for new record meter
- Search package: added auto config CLI

### Changed
- Search: move search widgets to package
- Updated package dependencies

## [0.6.0] - 2022-02-02
### Added
- Add `@use "sass:math"` in base styles scss for upcoming `@wordpress/block-editor` 8.1.0 requirement.
- Search package: added package version number and others

### Changed
- Build: remove unneeded files from production build.
- Instant Search: add image alt text from API
- Updated package dependencies.

## [0.5.4] - 2022-01-31
### Fixed
- Search: Fetch plan info as blog, not as user, to allow nonconnected admins to use dashboard

## [0.5.3] - 2022-01-27
### Fixed
- Search package: fixed compatibility issue with plan activation

## [0.5.2] - 2022-01-25
### Added
- Added a watch command for building assets
- Search E2E: added class names for some form components for easier E2E tests

### Changed
- Search: Improve accessibility via headings hierarchy and aria roles
- Updated package dependencies.

### Fixed
- Search widget: changed fetching search result to just before rendering jp search widget

## [0.5.1] - 2022-01-18
### Changed
- General: update required node version to v16.13.2

## [0.5.0] - 2022-01-11
### Added
- Search: Migrated Classic and Instant Search code from Jetpack plugin.
- Search API: activation and deactivation API.

### Changed
- Search: moved search dashboard to the package.
- Updated package dependencies.

## [0.4.0] - 2022-01-04
### Changed
- Do not escape widget title value
- Switch to pcov for code coverage.
- Updated package dependencies.
- Updated package textdomain from `jetpack` to `jetpack-search-pkg`.

### Fixed
- Add missing textdomains in JS code.

## [0.3.0] - 2021-12-14
### Changed
- Search package: add new methods and update timing for `Plan` class.
- Search package: refactored Module_Control class.

## [0.2.1] - 2021-12-07
### Changed
- Updated package dependencies.

## [0.2.0] - 2021-11-30
### Added
- Added essential scaffolding for package.
- Migrate additional helper classes to package
- Search: added new state store for search dashboard
- Search package: duplicated search dashboard dependencies to the package

### Changed
- Search: migrate/create necessary APIs for the frontend
- Search: removed other dependencies from copied code

## 0.1.0 - 2021-11-09
### Added
- Add a new Search package with Helper and Options classes.
- Search: Migrate helper classes from Jetpack plugin

### Changed
- Set `convertDeprecationsToExceptions` true in PHPUnit config.
- Updated package dependencies.
- Update PHPUnit configs to include just what needs coverage rather than include everything then try to exclude stuff that doesn't.

[0.52.5]: https://github.com/Automattic/jetpack-search/compare/v0.52.4...v0.52.5
[0.52.4]: https://github.com/Automattic/jetpack-search/compare/v0.52.3...v0.52.4
[0.52.3]: https://github.com/Automattic/jetpack-search/compare/v0.52.2...v0.52.3
[0.52.2]: https://github.com/Automattic/jetpack-search/compare/v0.52.1...v0.52.2
[0.52.1]: https://github.com/Automattic/jetpack-search/compare/v0.52.0...v0.52.1
[0.52.0]: https://github.com/Automattic/jetpack-search/compare/v0.51.0...v0.52.0
[0.51.0]: https://github.com/Automattic/jetpack-search/compare/v0.50.2...v0.51.0
[0.50.2]: https://github.com/Automattic/jetpack-search/compare/v0.50.1...v0.50.2
[0.50.1]: https://github.com/Automattic/jetpack-search/compare/v0.50.0...v0.50.1
[0.50.0]: https://github.com/Automattic/jetpack-search/compare/v0.49.0...v0.50.0
[0.49.0]: https://github.com/Automattic/jetpack-search/compare/v0.48.0...v0.49.0
[0.48.0]: https://github.com/Automattic/jetpack-search/compare/v0.47.24...v0.48.0
[0.47.24]: https://github.com/Automattic/jetpack-search/compare/v0.47.23...v0.47.24
[0.47.23]: https://github.com/Automattic/jetpack-search/compare/v0.47.22...v0.47.23
[0.47.22]: https://github.com/Automattic/jetpack-search/compare/v0.47.21...v0.47.22
[0.47.21]: https://github.com/Automattic/jetpack-search/compare/v0.47.20...v0.47.21
[0.47.20]: https://github.com/Automattic/jetpack-search/compare/v0.47.19...v0.47.20
[0.47.19]: https://github.com/Automattic/jetpack-search/compare/v0.47.18...v0.47.19
[0.47.18]: https://github.com/Automattic/jetpack-search/compare/v0.47.17...v0.47.18
[0.47.17]: https://github.com/Automattic/jetpack-search/compare/v0.47.16...v0.47.17
[0.47.16]: https://github.com/Automattic/jetpack-search/compare/v0.47.15...v0.47.16
[0.47.15]: https://github.com/Automattic/jetpack-search/compare/v0.47.14...v0.47.15
[0.47.14]: https://github.com/Automattic/jetpack-search/compare/v0.47.13...v0.47.14
[0.47.13]: https://github.com/Automattic/jetpack-search/compare/v0.47.12...v0.47.13
[0.47.12]: https://github.com/Automattic/jetpack-search/compare/v0.47.11...v0.47.12
[0.47.11]: https://github.com/Automattic/jetpack-search/compare/v0.47.10...v0.47.11
[0.47.10]: https://github.com/Automattic/jetpack-search/compare/v0.47.9...v0.47.10
[0.47.9]: https://github.com/Automattic/jetpack-search/compare/v0.47.8...v0.47.9
[0.47.8]: https://github.com/Automattic/jetpack-search/compare/v0.47.7...v0.47.8
[0.47.7]: https://github.com/Automattic/jetpack-search/compare/v0.47.6...v0.47.7
[0.47.6]: https://github.com/Automattic/jetpack-search/compare/v0.47.5...v0.47.6
[0.47.5]: https://github.com/Automattic/jetpack-search/compare/v0.47.4...v0.47.5
[0.47.4]: https://github.com/Automattic/jetpack-search/compare/v0.47.3...v0.47.4
[0.47.3]: https://github.com/Automattic/jetpack-search/compare/v0.47.2...v0.47.3
[0.47.2]: https://github.com/Automattic/jetpack-search/compare/v0.47.1...v0.47.2
[0.47.1]: https://github.com/Automattic/jetpack-search/compare/v0.47.0...v0.47.1
[0.47.0]: https://github.com/Automattic/jetpack-search/compare/v0.46.0...v0.47.0
[0.46.0]: https://github.com/Automattic/jetpack-search/compare/v0.45.12...v0.46.0
[0.45.12]: https://github.com/Automattic/jetpack-search/compare/v0.45.11...v0.45.12
[0.45.11]: https://github.com/Automattic/jetpack-search/compare/v0.45.10...v0.45.11
[0.45.10]: https://github.com/Automattic/jetpack-search/compare/v0.45.9...v0.45.10
[0.45.9]: https://github.com/Automattic/jetpack-search/compare/v0.45.8...v0.45.9
[0.45.8]: https://github.com/Automattic/jetpack-search/compare/v0.45.7...v0.45.8
[0.45.7]: https://github.com/Automattic/jetpack-search/compare/v0.45.6...v0.45.7
[0.45.6]: https://github.com/Automattic/jetpack-search/compare/v0.45.5...v0.45.6
[0.45.5]: https://github.com/Automattic/jetpack-search/compare/v0.45.4...v0.45.5
[0.45.4]: https://github.com/Automattic/jetpack-search/compare/v0.45.3...v0.45.4
[0.45.3]: https://github.com/Automattic/jetpack-search/compare/v0.45.2...v0.45.3
[0.45.2]: https://github.com/Automattic/jetpack-search/compare/v0.45.1...v0.45.2
[0.45.1]: https://github.com/Automattic/jetpack-search/compare/v0.45.0...v0.45.1
[0.45.0]: https://github.com/Automattic/jetpack-search/compare/v0.44.17...v0.45.0
[0.44.17]: https://github.com/Automattic/jetpack-search/compare/v0.44.16...v0.44.17
[0.44.16]: https://github.com/Automattic/jetpack-search/compare/v0.44.15...v0.44.16
[0.44.15]: https://github.com/Automattic/jetpack-search/compare/v0.44.14...v0.44.15
[0.44.14]: https://github.com/Automattic/jetpack-search/compare/v0.44.13...v0.44.14
[0.44.13]: https://github.com/Automattic/jetpack-search/compare/v0.44.12...v0.44.13
[0.44.12]: https://github.com/Automattic/jetpack-search/compare/v0.44.11...v0.44.12
[0.44.11]: https://github.com/Automattic/jetpack-search/compare/v0.44.10...v0.44.11
[0.44.10]: https://github.com/Automattic/jetpack-search/compare/v0.44.9...v0.44.10
[0.44.9]: https://github.com/Automattic/jetpack-search/compare/v0.44.8...v0.44.9
[0.44.8]: https://github.com/Automattic/jetpack-search/compare/v0.44.7...v0.44.8
[0.44.7]: https://github.com/Automattic/jetpack-search/compare/v0.44.6...v0.44.7
[0.44.6]: https://github.com/Automattic/jetpack-search/compare/v0.44.5...v0.44.6
[0.44.5]: https://github.com/Automattic/jetpack-search/compare/v0.44.4...v0.44.5
[0.44.4]: https://github.com/Automattic/jetpack-search/compare/v0.44.3...v0.44.4
[0.44.3]: https://github.com/Automattic/jetpack-search/compare/v0.44.2...v0.44.3
[0.44.2]: https://github.com/Automattic/jetpack-search/compare/v0.44.1...v0.44.2
[0.44.1]: https://github.com/Automattic/jetpack-search/compare/v0.44.0...v0.44.1
[0.44.0]: https://github.com/Automattic/jetpack-search/compare/v0.43.8...v0.44.0
[0.43.8]: https://github.com/Automattic/jetpack-search/compare/v0.43.7...v0.43.8
[0.43.7]: https://github.com/Automattic/jetpack-search/compare/v0.43.6...v0.43.7
[0.43.6]: https://github.com/Automattic/jetpack-search/compare/v0.43.5...v0.43.6
[0.43.5]: https://github.com/Automattic/jetpack-search/compare/v0.43.4...v0.43.5
[0.43.4]: https://github.com/Automattic/jetpack-search/compare/v0.43.3...v0.43.4
[0.43.3]: https://github.com/Automattic/jetpack-search/compare/v0.43.2...v0.43.3
[0.43.2]: https://github.com/Automattic/jetpack-search/compare/v0.43.1...v0.43.2
[0.43.1]: https://github.com/Automattic/jetpack-search/compare/v0.43.0...v0.43.1
[0.43.0]: https://github.com/Automattic/jetpack-search/compare/v0.42.1...v0.43.0
[0.42.1]: https://github.com/Automattic/jetpack-search/compare/v0.42.0...v0.42.1
[0.42.0]: https://github.com/Automattic/jetpack-search/compare/v0.41.1...v0.42.0
[0.41.1]: https://github.com/Automattic/jetpack-search/compare/v0.41.0...v0.41.1
[0.41.0]: https://github.com/Automattic/jetpack-search/compare/v0.40.4...v0.41.0
[0.40.4]: https://github.com/Automattic/jetpack-search/compare/v0.40.3...v0.40.4
[0.40.3]: https://github.com/Automattic/jetpack-search/compare/v0.40.2...v0.40.3
[0.40.2]: https://github.com/Automattic/jetpack-search/compare/v0.40.1...v0.40.2
[0.40.1]: https://github.com/Automattic/jetpack-search/compare/v0.40.0...v0.40.1
[0.40.0]: https://github.com/Automattic/jetpack-search/compare/v0.39.7...v0.40.0
[0.39.7]: https://github.com/Automattic/jetpack-search/compare/v0.39.6...v0.39.7
[0.39.6]: https://github.com/Automattic/jetpack-search/compare/v0.39.5...v0.39.6
[0.39.5]: https://github.com/Automattic/jetpack-search/compare/v0.39.4...v0.39.5
[0.39.4]: https://github.com/Automattic/jetpack-search/compare/v0.39.3...v0.39.4
[0.39.3]: https://github.com/Automattic/jetpack-search/compare/v0.39.2...v0.39.3
[0.39.2]: https://github.com/Automattic/jetpack-search/compare/v0.39.1...v0.39.2
[0.39.1]: https://github.com/Automattic/jetpack-search/compare/v0.39.0...v0.39.1
[0.39.0]: https://github.com/Automattic/jetpack-search/compare/v0.38.8...v0.39.0
[0.38.8]: https://github.com/Automattic/jetpack-search/compare/v0.38.7...v0.38.8
[0.38.7]: https://github.com/Automattic/jetpack-search/compare/v0.38.6...v0.38.7
[0.38.6]: https://github.com/Automattic/jetpack-search/compare/v0.38.5...v0.38.6
[0.38.5]: https://github.com/Automattic/jetpack-search/compare/v0.38.4...v0.38.5
[0.38.4]: https://github.com/Automattic/jetpack-search/compare/v0.38.3...v0.38.4
[0.38.3]: https://github.com/Automattic/jetpack-search/compare/v0.38.2...v0.38.3
[0.38.2]: https://github.com/Automattic/jetpack-search/compare/v0.38.1...v0.38.2
[0.38.1]: https://github.com/Automattic/jetpack-search/compare/v0.38.0...v0.38.1
[0.38.0]: https://github.com/Automattic/jetpack-search/compare/v0.37.4...v0.38.0
[0.37.4]: https://github.com/Automattic/jetpack-search/compare/v0.37.3...v0.37.4
[0.37.3]: https://github.com/Automattic/jetpack-search/compare/v0.37.2...v0.37.3
[0.37.2]: https://github.com/Automattic/jetpack-search/compare/v0.37.1...v0.37.2
[0.37.1]: https://github.com/Automattic/jetpack-search/compare/v0.37.0...v0.37.1
[0.37.0]: https://github.com/Automattic/jetpack-search/compare/v0.36.3...v0.37.0
[0.36.3]: https://github.com/Automattic/jetpack-search/compare/v0.36.2...v0.36.3
[0.36.2]: https://github.com/Automattic/jetpack-search/compare/v0.36.1...v0.36.2
[0.36.1]: https://github.com/Automattic/jetpack-search/compare/v0.36.0...v0.36.1
[0.36.0]: https://github.com/Automattic/jetpack-search/compare/v0.35.0...v0.36.0
[0.35.0]: https://github.com/Automattic/jetpack-search/compare/v0.34.4...v0.35.0
[0.34.4]: https://github.com/Automattic/jetpack-search/compare/v0.34.3...v0.34.4
[0.34.3]: https://github.com/Automattic/jetpack-search/compare/v0.34.2...v0.34.3
[0.34.2]: https://github.com/Automattic/jetpack-search/compare/v0.34.1...v0.34.2
[0.34.1]: https://github.com/Automattic/jetpack-search/compare/v0.34.0...v0.34.1
[0.34.0]: https://github.com/Automattic/jetpack-search/compare/v0.33.3...v0.34.0
[0.33.3]: https://github.com/Automattic/jetpack-search/compare/v0.33.2...v0.33.3
[0.33.2]: https://github.com/Automattic/jetpack-search/compare/v0.33.1...v0.33.2
[0.33.1]: https://github.com/Automattic/jetpack-search/compare/v0.33.0...v0.33.1
[0.33.0]: https://github.com/Automattic/jetpack-search/compare/v0.32.0...v0.33.0
[0.32.0]: https://github.com/Automattic/jetpack-search/compare/v0.31.7...v0.32.0
[0.31.7]: https://github.com/Automattic/jetpack-search/compare/v0.31.6...v0.31.7
[0.31.6]: https://github.com/Automattic/jetpack-search/compare/v0.31.5...v0.31.6
[0.31.5]: https://github.com/Automattic/jetpack-search/compare/v0.31.4...v0.31.5
[0.31.4]: https://github.com/Automattic/jetpack-search/compare/v0.31.3...v0.31.4
[0.31.3]: https://github.com/Automattic/jetpack-search/compare/v0.31.2...v0.31.3
[0.31.2]: https://github.com/Automattic/jetpack-search/compare/v0.31.1...v0.31.2
[0.31.1]: https://github.com/Automattic/jetpack-search/compare/v0.31.0...v0.31.1
[0.31.0]: https://github.com/Automattic/jetpack-search/compare/v0.30.2...v0.31.0
[0.30.2]: https://github.com/Automattic/jetpack-search/compare/v0.30.1...v0.30.2
[0.30.1]: https://github.com/Automattic/jetpack-search/compare/v0.30.0...v0.30.1
[0.30.0]: https://github.com/Automattic/jetpack-search/compare/v0.29.2...v0.30.0
[0.29.2]: https://github.com/Automattic/jetpack-search/compare/v0.29.1...v0.29.2
[0.29.1]: https://github.com/Automattic/jetpack-search/compare/v0.29.0...v0.29.1
[0.29.0]: https://github.com/Automattic/jetpack-search/compare/v0.28.0...v0.29.0
[0.28.0]: https://github.com/Automattic/jetpack-search/compare/v0.27.0...v0.28.0
[0.27.0]: https://github.com/Automattic/jetpack-search/compare/v0.26.0...v0.27.0
[0.26.0]: https://github.com/Automattic/jetpack-search/compare/v0.25.0...v0.26.0
[0.25.0]: https://github.com/Automattic/jetpack-search/compare/v0.24.0...v0.25.0
[0.24.0]: https://github.com/Automattic/jetpack-search/compare/v0.23.0...v0.24.0
[0.23.0]: https://github.com/Automattic/jetpack-search/compare/v0.22.2...v0.23.0
[0.22.2]: https://github.com/Automattic/jetpack-search/compare/v0.22.1...v0.22.2
[0.22.1]: https://github.com/Automattic/jetpack-search/compare/v0.22.0...v0.22.1
[0.22.0]: https://github.com/Automattic/jetpack-search/compare/v0.21.1...v0.22.0
[0.21.1]: https://github.com/Automattic/jetpack-search/compare/v0.21.0...v0.21.1
[0.21.0]: https://github.com/Automattic/jetpack-search/compare/v0.20.0...v0.21.0
[0.20.0]: https://github.com/Automattic/jetpack-search/compare/v0.19.0...v0.20.0
[0.19.0]: https://github.com/Automattic/jetpack-search/compare/v0.18.0...v0.19.0
[0.18.0]: https://github.com/Automattic/jetpack-search/compare/v0.17.1...v0.18.0
[0.17.1]: https://github.com/Automattic/jetpack-search/compare/v0.17.0...v0.17.1
[0.17.0]: https://github.com/Automattic/jetpack-search/compare/v0.16.2...v0.17.0
[0.16.2]: https://github.com/Automattic/jetpack-search/compare/v0.16.1...v0.16.2
[0.16.1]: https://github.com/Automattic/jetpack-search/compare/v0.16.0...v0.16.1
[0.16.0]: https://github.com/Automattic/jetpack-search/compare/v0.15.4...v0.16.0
[0.15.4]: https://github.com/Automattic/jetpack-search/compare/v0.15.3...v0.15.4
[0.15.3]: https://github.com/Automattic/jetpack-search/compare/v0.15.2...v0.15.3
[0.15.2]: https://github.com/Automattic/jetpack-search/compare/v0.15.1...v0.15.2
[0.15.1]: https://github.com/Automattic/jetpack-search/compare/v0.15.0...v0.15.1
[0.15.0]: https://github.com/Automattic/jetpack-search/compare/v0.14.2...v0.15.0
[0.14.2]: https://github.com/Automattic/jetpack-search/compare/v0.14.1...v0.14.2
[0.14.1]: https://github.com/Automattic/jetpack-search/compare/v0.14.0...v0.14.1
[0.14.0]: https://github.com/Automattic/jetpack-search/compare/v0.13.4...v0.14.0
[0.13.4]: https://github.com/Automattic/jetpack-search/compare/v0.13.3...v0.13.4
[0.13.3]: https://github.com/Automattic/jetpack-search/compare/v0.13.2...v0.13.3
[0.13.2]: https://github.com/Automattic/jetpack-search/compare/v0.13.1...v0.13.2
[0.13.1]: https://github.com/Automattic/jetpack-search/compare/v0.13.0...v0.13.1
[0.13.0]: https://github.com/Automattic/jetpack-search/compare/v0.12.3...v0.13.0
[0.12.3]: https://github.com/Automattic/jetpack-search/compare/v0.12.2...v0.12.3
[0.12.2]: https://github.com/Automattic/jetpack-search/compare/v0.12.1...v0.12.2
[0.12.1]: https://github.com/Automattic/jetpack-search/compare/v0.12.0...v0.12.1
[0.12.0]: https://github.com/Automattic/jetpack-search/compare/v0.11.3...v0.12.0
[0.11.3]: https://github.com/Automattic/jetpack-search/compare/v0.11.2...v0.11.3
[0.11.2]: https://github.com/Automattic/jetpack-search/compare/v0.11.1...v0.11.2
[0.11.1]: https://github.com/Automattic/jetpack-search/compare/v0.11.0...v0.11.1
[0.11.0]: https://github.com/Automattic/jetpack-search/compare/v0.10.0...v0.11.0
[0.10.0]: https://github.com/Automattic/jetpack-search/compare/v0.9.1...v0.10.0
[0.9.1]: https://github.com/Automattic/jetpack-search/compare/v0.9.0...v0.9.1
[0.9.0]: https://github.com/Automattic/jetpack-search/compare/v0.8.0...v0.9.0
[0.8.0]: https://github.com/Automattic/jetpack-search/compare/v0.7.0...v0.8.0
[0.7.0]: https://github.com/Automattic/jetpack-search/compare/v0.6.0...v0.7.0
[0.6.0]: https://github.com/Automattic/jetpack-search/compare/v0.5.4...v0.6.0
[0.5.4]: https://github.com/Automattic/jetpack-search/compare/v0.5.3...v0.5.4
[0.5.3]: https://github.com/Automattic/jetpack-search/compare/v0.5.2...v0.5.3
[0.5.2]: https://github.com/Automattic/jetpack-search/compare/v0.5.1...v0.5.2
[0.5.1]: https://github.com/Automattic/jetpack-search/compare/v0.5.0...v0.5.1
[0.5.0]: https://github.com/Automattic/jetpack-search/compare/v0.4.0...v0.5.0
[0.4.0]: https://github.com/Automattic/jetpack-search/compare/v0.3.0...v0.4.0
[0.3.0]: https://github.com/Automattic/jetpack-search/compare/v0.2.1...v0.3.0
[0.2.1]: https://github.com/Automattic/jetpack-search/compare/v0.2.0...v0.2.1
[0.2.0]: https://github.com/Automattic/jetpack-search/compare/v0.1.0...v0.2.0
