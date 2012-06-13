<?php

namespace DataSource\Mysql\DbCommand;

class UserSetter extends DbCommand
{
    public function UserSetter($params = array())
    {
        $result = array();
        foreach ($params AS $data)
        {
            if (is_object($data))
            {
                $data = $data->toArray();
            }
            if (!empty($data['id']))
            {
                try
                {
                    $id = $data['id'];
                    $result['update'][$id] = false;
                    if ($this->update("delivery_user", $data, "id = {$id}"))
                    {
                        $result['update'][$id] = $data;
                    }
                }
                catch (\PDOException $e)
                {
                }
            }
            else
            {
                try
                {
                    if (empty($data['snob_user_id']) && empty($data['activate']))
                    {
                        $data['status'] = 'pending';
                        $data['activate_code'] = self::activateCodeGen();
                    }
                    unset($data['activate']);
                    if ($this->insert("delivery_user", $data))
                    {
                        $id = $data['id'] = $this->lastInsertId("delivery_user");
                        $result['create'][$id] = $data;
                    }
                }
                catch (\PDOException $e)
                {
                }
            }
        }

        if ($result)
        {
            foreach ($result AS &$w)
            {
                foreach ($w AS &$u)
                {
                    $t = $u;
                    $u = new \Domain\Entity\User();
                    $u->fill($t);
                }
            }
            reset($result);
        }

        return $result;
    }

    protected static function activateCodeGen()
    {
        return md5(rand(0, time()));
    }
}
