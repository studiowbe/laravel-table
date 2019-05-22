<?php

namespace Studiow\Laravel\Table\Behaviour;

use Illuminate\Contracts\Pagination\LengthAwarePaginator as LengthAwarePaginatorInterface;
use Illuminate\Http\Request;

trait CanPaginate
{
    private $paginator;
    private $perPage;

    public function paginate(int $perPage)
    {
        $this->perPage = $perPage;

        return $this;
    }

    public function isPaginated(): bool
    {
        return ! is_null($this->perPage);
    }

    public function hasPages(): bool
    {
        if (! $this->isPaginated()) {
            return false;
        }

        return  $this->paginator()->hasPages();
    }

    public function paginator(?Request $request = null): ?LengthAwarePaginatorInterface
    {
        $request = $request ?? $this->request;

        if (! $this->isPaginated()) {
            return null;
        }
        if (is_null($this->paginator)) {
            $this->paginator = $this->results($request)
                ->paginate($this->perPage)
                ->setPath('')
                ->appends(
                    $request->query()
                );
        }

        return $this->paginator;
    }

    public function links(string $view = 'pagination::simple-bootstrap-4'): ?string
    {
        if (! $this->isPaginated()) {
            return null;
        }

        return $this->paginator()->setPath('')->links($view);
    }
}
