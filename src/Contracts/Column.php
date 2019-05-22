<?php

namespace Studiow\Laravel\Table\Contracts;

interface Column
{
    public function getId(): string;

    public function getLabel(): string;

    public function getIndex(): int;

    public function isSortable(): bool;

    public function sortable(bool $isSortable = true);

    public function label(string $label);

    public function index(int $index);

    public function resolveWith(?callable $resolver = null);

    public function resolve($row);
}
