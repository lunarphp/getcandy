# Release Schedule

## Overview

Lunar is currently on a `0.x` version. This means in order to get to a stable `1.0.0` release, we need to go through quite a rapid development cycle to ensure we are delivering bug fixes and features you expect from your e-commerce system.

For this reason we have committed to a release schedule that we feel will communicate what new features you can expect, when they will arrive and when important bug fixes will be released. Also, importantly, contributors will know with a certain level of accuracy when the feature they are proposing will be included and at what version.

### Schedule

- Feature Releases `0.x` - Released at the start of every month
- Bug Fixes `0.x.x` - Grouped and released at the end of each working week (Friday)
- Critical/Security Bug Fixes `0.x.x` - Released within the day they are merged.

## Backwards Compatibility and Upgrading

One concern is if we are releasing `0.x` versions frequently, what does this mean for you as the developer in terms of the level of effort required to upgrade for each version, for example `0.2` to `0.3` or `0.2` to `0.5` etc.

For each release there will be a dedicated upgrade section on the [Upgrade Guide](/core/upgrading) page, which will provide the required upgrade steps for you to take.

## Feature Releases (`0.x`)

Minor versions will be released at the start of each month. The `main` branch is considered the "edge" version and will contain features for the next release.

This timeframe will give all contributors enough time to finalise their pull requests and also allow us to test the features to ensure they are ready for release.

Once a new version is released, the previous version will no longer be supported and it will be recommended for all developers to upgrade to the latest version.

:::tip 
If a bug is found on a previous version that is also present on the latest `0.x` release, it will be fixed in the latest release, unless it is a security issue.
:::

### Bug fixes (`0.x.x`)

If there are PR's which have been opened to fix bugs, we will generally group them together and do a patch release at the end of the week, depending on volume and level.

Any fixes which are deemed critical will be released as they are merged to ensure everyone benefits straight away. When a release is tagged it will automatically be broadcast to our discord server.
