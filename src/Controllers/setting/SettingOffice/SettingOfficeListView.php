<?php

namespace App\Http\Controllers\setting\SettingOffice;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Codeton\DefaultFactoryIndex;
use App\Codeton\PageIndex;
use App\Helpers\ButtonHelper;
use Illuminate\View\View;
use Illuminate\Database\Query\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class SettingOfficeListView
 * 
 * Handles the list view for office settings
 */
class SettingOfficeListView extends SettingOfficeController implements DefaultFactoryIndex
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
            'name' => ['column' => 'name', 'label' => 'Name'],
            'code' => ['column' => 'code', 'label' => 'Code'],
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
            ['col' => 'name', 'title' => 'Name', 'first' => 1, 'mw' => 200],
            ['col' => 'code', 'title' => 'Code', 'first' => 0, 'mw' => 150],
            ['col' => 'status', 'title' => 'Status', 'first' => 0]
        ];
    }

    /**
     * Define default order by column
     *
     * @return string
     */
    public function defaultOrderBy(): string
    {
        return 'id';
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
            $data['btn_create'] = ButtonHelper::buttonModal(
                url($this->controllerPath . '/create')
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
        return DB::table($this->mainTable);
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
                'code' => $key->code,
                'btn_activation' => ButtonHelper::btnToggleActivation(
                    $status['color'],
                    $key->id,
                    $key->status,
                    $status['title']
                ),
                'btn_edit' => !$this->acl($this->controllerPath) ? '-' :
                    ButtonHelper::btnDetailModal(
                        url($this->controllerPath . '/detail/' . $key->id),
                        '#modalForm'
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
        
        if (is_numeric($status)) {
            $dataList->where('status', $status);
        }

        return $dataList;
    }
}

