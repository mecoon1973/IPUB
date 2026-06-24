<?php
namespace Modules\System\Service\Impl;

use Illuminate\Support\Facades\Auth;

use Modules\System\Service\NhomCanboService;
use Modules\System\Repository\NhomCanboRepository;
use Core\Repository\CountersOlmRepository as CounterRepository;
use Core\Service\BaseConvertTool;
use Core\Service\BaseService;
use Modules\User\Service\UserService;

class NhomCanboServiceImpl extends BaseService implements NhomCanboService
{
    use BaseConvertTool;
    /** @var NhomCanboRepository */
    protected $baseRepo;

    public function __construct(NhomCanboRepository $baseRepo) {
        parent::__construct($baseRepo);
    }

    /**
     * gộp bảng ipub_nhom_canbo -> canbo
     * tìm các cán bộ trong nhóm và gộp các id nhóm đó chuyển thành 1 field là nhom_ids là array trong Canbo
     */
    public function convertDataNhomCanboToNewSystem(){
        $list = $this->list(['_id', 'ID_CANBO', 'ID_NHOM'], ["IsDeleted" => false, "InUsed" => true], [])->toArray();
        $map = convert_array_to_hashtable($list, 'ID_CANBO', ['ID_NHOM']);
        foreach($map as $canboId => $nhomList){
            $map[$canboId] = get_field_from_list($nhomList, 'ID_NHOM', true);
        }

        /** @var UserService $userService */
        $userService = app(UserService::class);
        foreach($map as $canboId => $nhomIds){
            $user = $userService->findOne("no-cache",["_id" => $canboId]);
            if($user) {
                $user->nhom_ids = $nhomIds;
                $user->save();
            }
        }
        dump("done");
    }


}
