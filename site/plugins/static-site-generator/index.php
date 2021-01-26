<?php

@include_once __DIR__ . '/vendor/autoload.php';

use D4L\StaticSite\Generator;
use D4L\StaticSite\Media;
use Kirby\Cms\App as Kirby;

Kirby::plugin('d4l/static-site-generator', [
    'api' => [
        'routes' => function ($kirby) {
        $endpoint = $kirby->option('d4l.staticSiteGenerator.endpoint');
        if (!$endpoint) {
            return [];
        }

        return [
            [
            'pattern' => $endpoint,
            'method' => 'POST',
            'action' => function () use ($kirby) {
                $outputFolder = $kirby->option('d4l.staticSiteGenerator.outputFolder', './static');
                $baseUrl = $kirby->option('d4l.staticSiteGenerator.baseUrl', '/');
                $preserve = $kirby->option('d4l.staticSiteGenerator.preserve', []);
                $skipMedia = $kirby->option('d4l.staticSiteGenerator.skipMedia', false);
                $skipTemplates = array_diff(
                    $kirby->option('d4l.staticSiteGenerator.skipTemplates', []),
                    ['home']
                );

                $pages = $kirby->site()->index()->filterBy('intendedTemplate', 'not in', $skipTemplates);
                $staticSiteGenerator = new Generator($kirby, null, $pages);
                $staticSiteGenerator->skipMedia($skipMedia);
                $list = $staticSiteGenerator->generate($outputFolder, $baseUrl, $preserve);

                return [
                    'success' => true,
                    'files' => $list,
                    'message' => count($list) . ' files generated/copied'
                ];
            }
            ]
        ];
        }
    ],

    'components' => [
        'file::version' => function (Kirby $kirby, $file, array $options = []) {
            $nativeVersion = $kirby->nativeComponent('file::version');
            $version = $nativeVersion($kirby, $file, $options);

            if (!Media::isActive()) {
                return $version;
            }

            if (!$version->exists()) {
                $version->save();
            }

            Media::register($version->root(), $version->url());
            return $version;
        },
        'file::url' => function (Kirby $kirby, $file, array $options = []) {
            $nativeUrl = $kirby->nativeComponent('file::url');
            $url = $nativeUrl($kirby, $file, $options);

            if (!Media::isActive()) {
                return $url;
            }

            Media::register($file->root(), $url);
            return $url;
        }
    ],

    'fields' => [
        'staticSiteGenerator' => [
        'props' => [
            'endpoint' => function () {
            return $this->kirby()->option('d4l.staticSiteGenerator.endpoint');
            }
        ]
        ]
    ]
]);
