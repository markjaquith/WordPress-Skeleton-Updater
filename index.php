<?php

use Zttp\Zttp;

require(__DIR__ . '/vendor/autoload.php');

define('WP_VERSIONS_API', 'https://api.wordpress.org/core/stable-check/1.0/');

function getStableVersion(array $versions) {
	foreach ($versions as $version => $status) {
		if ('latest' === $status) {
			return $version;
		}
	}

	return "5.5";
}

$versions = Zttp::get(WP_VERSIONS_API)->json();

$stableVersion = getStableVersion($versions);

$commands = [
	"cd ~/git/WordPress-Skeleton",
	"git fetch origin",
	"git reset --hard origin/master",
	"git submodule update",
	"cd wp",
	"git fetch origin",
	"git fetch origin --tags",
	"git checkout {$stableVersion}",
	"cd ../",
	"git add wp",
	"git commit -m 'WordPress {$stableVersion}'",
	"git push origin master",
];

$commands = implode(' && ', $commands);

passthru($commands);
