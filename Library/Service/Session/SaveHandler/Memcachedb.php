<?php

namespace Service;

class Session_SaveHandler_Memcachedb implements \Zend_Session_SaveHandler_Interface
{
	public function open($save_path, $name)
	{
		return true;
	}

	public function read($id)
	{
		$data = \Service\Cache\Server_MemcacheDB::get($id);
		return $data[1];
	}

	public function write($id, $data)
	{
		$data = array(time(), $data);
		\Service\Cache\Server_MemcacheDB::set($id, $data);
	}

	public function destroy($id)
	{
		\Service\Cache\Server_MemcacheDB::clean($id);
	}

	public function gc($maxlifetime)
	{
		return true;
	}

	public function close()
	{
		return true;
	}
}
