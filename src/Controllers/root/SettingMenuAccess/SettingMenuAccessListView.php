<?php

namespace App\Http\Controllers\root\SettingMenuAccess;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Codeton\DefaultFactoryIndex;
use App\Codeton\PageIndex;
use App\Helpers\ButtonHelper;
use Illuminate\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class SettingMenuAccessListView
 * 
 * Manages the list view for menu access settings
 */
class SettingMenuAccessListView extends SettingMenuAccessController implements DefaultFactoryIndex
{
    use PageIndex;
    
    /**
     * HTTP request instance
     *
     * @var Request
     */
    protected Request $request;
    
    /**
     * Constructor
     *
     * @param Request $request HTTP request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Define searchable columns
     *
     * @return array Column configuration for search
     */
    public function columnSearch(): array
    {
        return [
            'name' => ['column' => 'a.name', 'label' => 'Name'],
            'code' => ['column' => 'a.url', 'label' => 'URL'],
        ];
    }

    /**
     * Define table headers
     *
     * @return array Table header configuration
     */
    public function tableHeader(): array
    {
        return [
            ["col" => "name", "title" => "Name", "first" => 1, "mw" => 200],
            ["col" => "icon", "title" => "Icon", "first" => 0, "mw" => 100],
            ["col" => "parent", "title" => "Parent", "first" => 0, "mw" => 150],
            ["col" => "menu_order", "title" => "Order", "first" => 0, "mw" => 100],
            ["col" => "url", "title" => "Url", "first" => 0, "mw" => 100],
            ["col" => "access", "title" => "Access", "first" => 0, "mw" => 100],
            ["col" => "status", "title" => "Status", "first" => 0]
        ];
    }

    /**
     * Define default order by column
     *
     * @return string Default order by column
     */
    public function defaultOrderBy(): string
    {
        return 'id';
    }
    
    /**
     * Define default order type
     *
     * @return string Default order type (asc/desc)
     */
    public function defaultOrderType(): string
    {
        return 'asc';
    }

    /**
     * Generate table view
     *
     * @return View|JsonResponse View or JSON response
     */
    public function tableView()
    {
        $data = $this->buildIndexView($this->request);
        $this->request->session()->put($this->controllerPath, $this->request->fullUrl());
        
        if (!$this->request->ajax()) {
            $data['fields'] = array_keys($data['list_data'][0] ?? []);
            $data['column_search'] = $this->columnSearch();
            $data['table_header'] = $this->tableHeader();
            $data['btn_create'] = ButtonHelper::button_modal(url($this->controllerPath.'/create'));
            
            return view($this->viewFolder . '.page-index', $data);
        } else {
            return response()->json($data);
        }
    }

    /**
     * Build query for data list
     *
     * @return Builder Query builder instance
     */
    public function queryDataList(): Builder
    {
        $dataList = DB::table($this->mainTable.' as a')
            ->leftJoin($this->mainTable.' as b', 'b.id', 'a.id_parent')
            ->select('a.*', 'b.name as parent');
            
        return $dataList;
    }

    /**
     * Set data before sending to view
     *
     * @param LengthAwarePaginator $dataResults Paginated results
     * @return array Formatted data for view
     */
    public function setDataBeforeSend(LengthAwarePaginator $dataResults): array
    {
        $no = $this->setNumbering($dataResults);
        $listData = [];
        
        foreach ($dataResults as $key) {
            $status = $this->setToggleStatus($key->status);
            $listData[] = [
                'no' => $no,
                'name' => $key->name.'<br><small>'.($key->type ? 'Menu' : 'Access').'</small>',
                'icon' => $key->icon,
                'parent' => $key->parent,
                'menu_order' => $key->menu_order,
                'url' => $key->url,
                'access' => $key->access ? 'User' : 'Root',
                'btn_activation' => ButtonHelper::btn_toggle_activation(
                    $status['color'],
                    $key->id,
                    $key->status,
                    $status['title']
                ),
                'btn_edit' => ButtonHelper::btn_detail_modal(
                    url($this->controllerPath.'/detail/'.$key->id),
                    '#modalForm'
                ),
                'btn_delete' => ButtonHelper::btn_delete($key->id),
            ];
            $no++;
        }
        
        return $listData;
    }

    /**
     * Filter data by columns
     *
     * @param Builder $dataList Query builder instance
     * @return Builder Modified query builder with filters
     */
    public function filterColumn(Builder $dataList): Builder
    {
        $status = $this->request->get('status');
        $parent = $this->request->get('parent');
        
        if (is_numeric($status)) {
            $dataList->where('status', $status);
        }
        
        if (is_numeric($parent)) {
            $dataList->where('a.id_parent', $parent);
        }
        
        return $dataList;
    }
}

