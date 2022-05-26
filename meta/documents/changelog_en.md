# Release Notes for Elastic Export BeezUp

## v1.1.10 (2022-05-26)

### Changed
- UPDATE - Additional updates to ensure compatibility with PHP 8.


## v1.1.9 (2019-12-19)

### Fixed
- It was possible to generate multiple colums with the same label through properties.

## v1.1.8 (2019-10-09)

### Changed
- The user guide was updated (changed form of address, corrected broken links).

## v1.1.7 (2019-01-21)

### Changed
- An incorrect link in the user guide was corrected.

## v1.1.6 (2018-07-11)

### Changed
- An incorrect link in the user guide was corrected.

## v1.1.5 (2018-06-05)

### Changed
- The table in the user guide which contains information about format settings was extended.

## v1.1.4 (2018-04-30)

### Changed
- Laravel 5.5 update.

## v1.1.3 (2018-03-28)

### Changed
- The class FiltrationService is responsible for the filtration of all variations.
- Preview images updated.

## v1.1.2 (2018-03-08)

### Fixed
- Tables were adjusted.

## v1.1.1 (2018-02-16)

### Changed
- Updated plugin short description.

## v1.1.0 (2017-12-28)

### Added
- The StockHelper takes the new fields "Stockbuffer", "Stock for variations without stock limitation" and "Stock for variations with not stock administration" into account.

## 1.0.18 (2017-11-08)

### Fixed
- An issue was fixed which caused the connection to elasticsearch to break.

## v1.0.17 (2017-11-03)

### Fixed
- An issue was fixed which caused properties of type selection not to be exported.

## v1.0.16 (2017-09-18)

### Fixed
- An issue was fixed which caused additional properties not correctly to be exported.
 
## v1.0.15 (2017-09-18)

### Fixed
- An issue was fixed which caused multiple header rows to be exported.

## v1.0.14 (2017-09-12) 

### Fixed
- In some cases, property values were exported in the wrong field.

## v1.0.13 (2017-08-25)

### Changed
- The format plugin is now based on Elastic Search only.
- The performance has been improved.

## v1.0.12 (2017-05-30)

### Changed
- The plugin Elastic Export is now required to use the plugin format BeezUp.

## v1.0.11 (2017-05-05)

### Fixed
- An issue was fixed which caused errors while loading the export format.

## v1.0.10 (2017-05-02)

### Changed
- Outsourced the stock filter logic to the Elastic Export plugin.

## v1.0.9 (2017-04-18)

### Fixed
- An issue was fixed which caused the plugin to fail at the build productive.

## v1.0.8 (2017-04-06)

### Fixed
- An issue was fixed which caused the item filter "stock" to not work properly.

## v1.0.7 (2017-04-04)

### Added
- Added a new mutator so we will prevent trying to get access to an array key which not exists.

### Changed
- Performance has been improved.

## v1.0.6 (2017-03-29)

### Fixed
- The image-data will now be received properly.

## v1.0.5 (2017-03-22)

### Fixed
- We now use a different value to get the image URLs for plugins working with elastic search.

## v1.0.4 (2017-03-21)

### Fixed
- Fixed the recommended retail price and how it was exported.

## v1.0.3 (2017-03-20)

### Fixed
- Fixed the price and how it was exported.
- Adjusted the load image function.

## v1.0.2 (2017-03-13)

### Added
- Added marketplace name

## v1.0.1 (2017-03-07)

### Added
- Extended plugin documentation files

## v1.0.0 (2017-03-06)
 
### Added
- Added initial plugin files
