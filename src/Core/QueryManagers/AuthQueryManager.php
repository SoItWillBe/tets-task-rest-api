<?php

namespace App\Core\QueryManagers;

use App\Helpers\ResponseMessage;
use App\Helpers\ResponseStatusesEnums;

class UserSessionQueryManager extends QueryManager
{
    protected \PDO $pdo;

    protected string $table;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->table = 'users_token';
    }

    public function query(array $rows = null, array $where = null, string $table = null)
    {
        $select = $this->select($rows, $table);
        if (null !== $where)
        {
            $select->where($where);
        }

        return $select->execute();
    }

    public function storeSessionToken(int $user_id, string $token): bool
    {
        $activeSession = $this->select()->where(['user_id' => $user_id])->execute();

        if (!empty($activeSession))
        {
            $this->killSession($user_id);
        }
        
        /**
         * TODO:
         * - fetch to check existing session for current user.
         * - if exists - kill and save new.
         */

        return $this->insert(['user_id' => $user_id, 'token' => $token]);
    }

    public function getSessionToken($token): array
    {
        return $this
            ->select()
            ->where(['token' => $token])
            ->execute();
    }

    public function killSession($user_id)
    {
        return $this->delete(['user_id' => $user_id]) ?
            ResponseMessage::response(ResponseStatusesEnums::Success, 'Session token killed'):
            ResponseMessage::response(ResponseStatusesEnums::Error, 'Error killing session token');
    }
    
}