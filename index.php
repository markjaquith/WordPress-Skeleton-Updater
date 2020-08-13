<?php

define('WP_VERSIONS_API', 'https://api.wordpress.org/core/stable-check/1.0/');

function getStableVersion(array $versions) : string {
	$stableVersion = "5.5";
	
	foreach ($versions as $version => $status) {
		if ('latest' === $status) {
			$stableVersion = $version;
		}
	}

	$stableVersion = preg_replace('#[^\d.]#', '', $stableVersion);

	return $stableVersion;
}

$versions = json_decode(file_get_contents(WP_VERSIONS_API), JSON_OBJECT_AS_ARRAY);

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
