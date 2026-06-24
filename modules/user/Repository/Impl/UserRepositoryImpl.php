<?php

namespace Modules\User\Repository\Impl;

use Core\Repository\BaseRepository;
use Modules\User\Model\User;
use Modules\User\Repository\UserRepository;

class UserRepositoryImpl extends BaseRepository implements UserRepository {
    public function getModel() {
        return User::class;
    }
}
