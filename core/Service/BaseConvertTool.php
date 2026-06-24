<?php
namespace Core\Service;
use Core\Logger;
use Core\Repository\IBaseRepository;

trait BaseConvertTool {
    /**
     * @param string $nameConvert
     * @param IBaseRepository $repo
     * @param array $conditions
     * @param callable $callBack
     * @param int $skip
     * @param int $limit
     * @param bool $fixedSkip
     * @return void
     * */
    public function baseConvert(string $nameConvert, mixed $repo,array $conditions,callable $callBack, int $skip = 0, int $limit = 1000, bool $fixedSkip = false) {
        $total = $repo->count($conditions);
        $logger = Logger::getInstance($nameConvert);
        $logger->info("Total: ".$total);
        $count = $skip;
        while ($count <= $total) {
            $listItem = $repo->list([], $conditions, [
                "skip" => $skip,
                "limit" => $limit
            ]);
            foreach ($listItem as $item) {
                $callBack($item);
            }
            $count += $limit;
            if(!$fixedSkip) {
                $skip += $limit;
            }
            $logger->info("Count: ".$count." - Skip: ".$skip);
        }
        $logger->info("FINISH");
    }

}
