<?php

namespace NarutoRPG\Repository;

use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Extension\FrontMatter\FrontMatterExtension;
use Symfony\Component\Finder\Finder;

final class PostRepository
{
    public function findAll(): array
    {
        $posts = [];

        $finder = $this->getFinder();
        $converter = $this->getCommonMarkConverter();

        foreach ($finder as $file) {
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

    private function getFinder(): Finder
    {
        $finder = new Finder();
        $finder->files()->in(__DIR__ . '/../../data')->name('*.md')->sort(function (\SplFileInfo $a, \SplFileInfo $b) {
            return $b->getMTime() - $a->getMTime();
        });
        return $finder;
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
