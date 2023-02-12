<?php

namespace A2Global\A2Platform\Bundle\PlatformBundle\Data\Filter;

use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class PaginationDataFilter extends AbstractDataFilter
{
    const NAME = 'pagination';

    protected int $page = 1;

    protected int $limit = 20;

    /** Other filters hash */
    protected string $hash = '';

    public function getPage(): int
    {
        return $this->page;
    }

    public function setPage($page): self
    {
        $this->page = max((int) $page, 1);

        return $this;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function setLimit(int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }

    public function isEnabled(): bool
    {
        return true;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getHash(): string
    {
        return $this->hash;
    }

    public function setHash(string $hash): self
    {
        $this->hash = $hash;
        return $this;
    }

    public function buildForm(FormBuilderInterface $container): self
    {
        $container->add('page', IntegerType::class, [
            'data' => $this->page,
            'required' => false,
        ]);
        $container->add('limit', IntegerType::class, [
            'data' => $this->limit,
            'required' => false,
        ]);
        $container->add('hash', TextType::class, [
            'data' => $this->hash,
            'required' => false,
        ]);

        return $this;
    }
}