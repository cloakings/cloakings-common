<?php

namespace Cloakings\CloakingsCommon;

class CloakerFactory
{
    public function create(string $strategy): CloakerInterface
    {
        $class = $this->getClass($strategy);
        if (!class_exists($strategy)) {
            throw new \InvalidArgumentException('cloacker_factory_strategy_not_found');
        }

        $result = $this->createFromClass($class);
        if (!($result instanceof CloakerInterface)) {
            throw new \InvalidArgumentException('cloacker_factory_strategy_invalid');
        }

        return $result;
    }

    public function createChain(array $strategies): CloakerInterface
    {
        $cloakers = [];
        foreach ($strategies as $strategy) {
            $cloakers[] = $this->create($strategy);
        }

        return new ChainCloaker($cloakers);
    }

    protected function getClass(string $strategy): string
    {
        return $strategy;
    }

    protected function createFromClass(string $class): mixed
    {
        return new $class();
    }
}
