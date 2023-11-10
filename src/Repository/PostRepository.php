<?php

namespace NarutoRPG\Repository;

use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Extension\FrontMatter\FrontMatterExtension;

final class PostRepository
{
    public function findAll(): array
    {
        $posts = [];
        $files = glob(__DIR__ . '/../../data/*.md');
        rsort($files);

        $converter = $this->getCommonMarkConverter();

        foreach ($files as $file) {
            $result = $converter->convert(file_get_contents($file));
            $frontMatter = $result->getFrontMatter();

            $posts[] = [
                'date' => $frontMatter['date'] ?? 'unbekannt',
                'title' => $frontMatter['title'] ?? 'Kein Titel',
                'type' => $frontMatter['type'] ?? 'Neuigkeiten',
                'content' => $result->getContent(),
            ];
        }

        return $posts;
    }

    public function findOneBySlug(string $slug): ?array
    {
        $files = glob(__DIR__ . '/../../data/*.md');

        foreach ($files as $file) {
            if (!str_contains($file, $slug)) {
                continue;
            }

            $converter = $this->getCommonMarkConverter();

            $result = $converter->convert(file_get_contents($file));
            $frontMatter = $result->getFrontMatter();

            return [
                'date' => $frontMatter['date'] ?? 'unbekannt',
                'title' => $frontMatter['title'] ?? 'Kein Titel',
                'type' => $frontMatter['type'] ?? 'Neuigkeiten',
                'content' => $result->getContent(),
            ];
        }

        return null;
    }

    private function getCommonMarkConverter(): CommonMarkConverter
    {
        $converter = new CommonMarkConverter([
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);
        $converter->getEnvironment()->addExtension(new FrontMatterExtension());
        return $converter;
    }
}
