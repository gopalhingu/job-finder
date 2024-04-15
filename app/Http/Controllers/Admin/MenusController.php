<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\Admin\Menu;
use App\Models\Admin\News;
use App\Models\Admin\Page;

class MenusController extends Controller
{
    /**
     * Function (for ajax) to display menus list
     *
     * @return view
     */
    public function listView()
    {
        if (!allowedTo('menu_settings')) {
            die(__('message.not_allowed'));
        }
        $data['page'] = __('message.menus');
        $data['menu'] = 'menus';
        return view('admin.menus.main', $data);
    }

    /**
     * Function (for ajax) to display menus list data
     *
     * @return view
     */
    public function listData(Request $request, $alignment = NULL)
    {
        $data['items'] = Menu::getAllForMenu($alignment);
        echo view('admin.menus.list', $data)->render();
    }

    /**
     * Function (for ajax) to display menus list data for delete
     *
     * @return view
     */
    public function listDataForDeleteMenu(Request $request, $alignment = NULL)
    {
        $data['items'] = Menu::getAll($alignment);
        echo view('admin.menus.list-for-delete', $data)->render();
    }

    /**
     * Function (for ajax) to get sub menu
     *
     * @param integer $menu_item_id
     * @return void
     */
    public function getSubMenu(Request $request, $menu_item_id)
    {
        if ($menu_item_id == 'select_page') {
            $pages = Page::getAll();
            return $pages ? $pages : array();
        } else if ($menu_item_id == 'select_news') {
            $news = News::getAll(true, '', $request);
            return $news ? $news->toArray()['data'] : array();
        }
    }    

    /**
     * Function (for ajax) to process menu save request
     *
     * @return redirect
     */
    public function save(Request $request)
    {
        $this->checkIfDemo();

        $data = json_decode($request->input('data'));
        $menu_item_id = $data->menu_item_id;
        $sub_item_id = $data->sub_item_id;
        $title = $data->title;
        $link = $data->link;
        $alignment = $data->alignment;

        if ($menu_item_id == 'select_page' || $menu_item_id == 'select_news') {
            if ($sub_item_id == '') {
                die(json_encode(array(
                    'success' => 'false',
                    'messages' => $this->ajaxErrorMessage(array('error' => __('message.sub_item_is_required')))
                )));
            }
        }

        if ($menu_item_id == 'static_external') {
            if ($title == '' || $link == '') {
                die(json_encode(array(
                    'success' => 'false',
                    'messages' => $this->ajaxErrorMessage(array('error' => __('message.link_is_required')))
                )));
            }
        }

        Menu::storeMenu($menu_item_id, $sub_item_id, $title, $link, $alignment);
        echo json_encode(array(
            'success' => 'true',
            'messages' => $this->ajaxErrorMessage(array('success' => __('message.menu').' '.__('message.updated'))),
        ));
    }

    /**
     * Function (for ajax) to process menu order update request
     *
     * @return void
     */
    public function orderUpdate(Request $request)
    {
        $this->checkIfDemo();
        Menu::orderUpdate($request);
    }

    /**
     * Function (for ajax) to process menu delete request
     *
     * @param integer $menu_item_id
     * @return void
     */
    public function delete($menu_item_id)
    {
        $this->checkIfDemo();
        Menu::remove($menu_item_id);
    }
}
