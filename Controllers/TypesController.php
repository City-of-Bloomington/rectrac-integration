<?php
/**
 * @copyright 2012-2013 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
namespace Application\Controllers;

use Application\Models\TypeGateway;
use Blossom\Classes\Block;
use Blossom\Classes\Controller;

class TypesController extends Controller
{
	public function index()
	{
        $list = TypeGateway::find($_GET);

        $this->template->blocks[] = new Block('types/list.inc', ['types'=>$list]);
	}
}
