<?php
/**
 * Singleton for the Database connection
 *
 * @copyright 2015 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
namespace Application\Models;

class Database
{
	private static $connection;

	/**
	 * @param boolean $reconnect If true, drops the connection and reconnects
	 * @return resource
	 */
	public static function getConnection($reconnect=false)
	{
		if ($reconnect) {
			self::$connection=null;
		}
		if (!self::$connection) {
			try {
                self::$connection = new \PDO(DB_DSN, DB_USER, DB_PASS);
			}
			catch (Exception $e) {
				die($e->getMessage());
			}
		}
		return self::$connection;
	}
}
