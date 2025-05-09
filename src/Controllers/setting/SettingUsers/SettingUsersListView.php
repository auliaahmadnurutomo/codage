<?php

namespace App\Http\Controllers\setting\SettingUsers;

use Illuminate\Support\Facades\DB;
use App\Codeton\DefaultFactoryIndex;
use App\Codeton\PageIndex;
use App\Helpers\ButtonHelper;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Database\Query\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class SettingUsersListView
 * 
 * Handles the list view for user settings
 */
class SettingUsersListView extends SettingUsersController implements DefaultFactoryIndex
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
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Define searchable columns
     *
     * @return array<string, array<string, string>>
     */
    public function columnSearch(): array
    {
        return [
            'name' => ['column' => 'a.name', 'label' => 'Name'],
            'email' => ['column' => 'a.email', 'label' => 'Email'],
        ];
    }

    /**
     * Define table headers
     *
     * @return array<int, array<string, mixed>>
     */
    public function tableHeader(): array
    {
        return [
            ['col' => 'a.name', 'title' => 'Name', 'first' => 1, 'mw' => 200],
            ['col' => 'a.email', 'title' => 'Email', 'first' => 0, 'mw' => 150],
            ['col' => 'e.name', 'title' => 'Role', 'first' => 0],
            ['col' => 'office.name', 'title' => 'Office', 'first' => 0],
            ['col' => 'd.status', 'title' => 'Status', 'first' => 0]
        ];
    }

    /**
     * Define default order by column
     *
     * @return string
     */
    public function defaultOrderBy(): string
    {
        return 'a.name';
    }

    /**
     * Define default order type
     *
     * @return string
     */
    public function defaultOrderType(): string
    {
        return 'asc';
    }

    /**
     * Generate table view
     *
     * @return View|JsonResponse
     */
    public function tableView(): View|JsonResponse
    {
        $data = $this->buildIndexView($this->request);
        $this->request->session()->put($this->controllerPath, $this->request->fullUrl());

        if (!$this->request->ajax()) {
            $data['fields'] = array_keys($data['list_data'][0] ?? []);
            $data['column_search'] = $this->columnSearch();
            $data['table_header'] = $this->tableHeader();
            $data['btn_create'] = ButtonHelper::hrefRedirect(
                target: url($this->controllerPath . '/create'),
                btnClass: 'btn-md btn-primary',
                icon: 'plus',
                text: 'New'
            );

            return view($this->viewFolder . '.page-index', $data);
        }

        return response()->json($data);
    }

    /**
     * Build query for data list
     *
     * @return Builder
     */
    public function queryDataList(): Builder
    {
        return DB::table($this->mainTable . ' as a')
            ->leftJoin('skeleton_users_info as d', 'd.id_user', 'a.id')
            ->leftJoin('skeleton_setting_department as dept', 'dept.id', 'd.id_department')
            ->leftJoin('skeleton_setting_position as pos', 'pos.id', 'd.id_position')
            ->leftJoin('skeleton_setting_office as office', 'office.id', 'd.id_office')
            ->leftJoin('skeleton_setting_menu_template as e', 'e.id', 'd.id_access_template')
            ->selectRaw('a.*, d.status, dept.name as department, pos.name as staff_position, office.name as office, e.name as role');
    }

    /**
     * Set data before sending to view
     *
     * @param LengthAwarePaginator $dataResults
     * @return array
     */
    public function setDataBeforeSend(LengthAwarePaginator $dataResults): array
    {
        $no = $this->setNumbering($dataResults);
        $listData = [];

        foreach ($dataResults as $key) {
            $status = $this->setToggleStatus($key->status);
            $listData[] = [
                'no' => $no,
                'name' => $key->name,
                'email' => $key->email,
                'role' => $key->role,
                'office_name' => $key->office,
                'btn_activation' => ButtonHelper::btnToggleActivation(
                    $status['color'],
                    $key->id,
                    $key->status,
                    $status['title']
                ),
                'btn_edit' => !$this->acl($this->controllerPath) ? '-' :
                    ButtonHelper::hrefRedirect(
                        target: url($this->controllerPath . '/detail/' . $key->id),
                        btnClass: 'btn-sm btn-light border rounded',
                        icon: 'expand'
                    ),
                'btn_delete' => !$this->acl($this->controllerPath) ? '-' :
                    ButtonHelper::btnDelete($key->id),
            ];
            $no++;
        }

        return $listData;
    }

    /**
     * Filter data by columns
     *
     * @param Builder $dataList
     * @return Builder
     */
    public function filterColumn(Builder $dataList): Builder
    {
        $status = $this->request->get('status');
        $officeDetail = $this->request->get('office_detail');

        if (is_numeric($status)) {
            $dataList->where('d.status', $status);
        }

        if ($officeDetail) {
            $dataList->where('office.code', $officeDetail);
        }

        return $dataList;
    }
}

