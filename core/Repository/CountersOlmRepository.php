<?php
namespace Core\Repository;

interface CountersOlmRepository {
    /**
     * @param string $_id
     * @param int|bool $randInc
     * @return int
     */
    public function increment($_id, $randInc = true);

    /**
     * Generate new id multiple times until it bears a new unique one, or `maxTries` reached
     * @template T
     * @param string $_id
     * @param \Closure(int $id):T $try
     * @param $maxTries = 10
     * @return T
     */
    public function retry($_id, $try, $maxTries = 10);
}
