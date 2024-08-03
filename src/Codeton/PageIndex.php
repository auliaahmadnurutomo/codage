<?php
namespace App\Codeton;

trait PageIndex
{
    protected $queryString = [];
    protected $orderBy;
    protected $orderType;
    protected $parameter = array();

    private function set_numbering($results)
    {
        return ($results->currentPage() - 1) * $results->perPage() + 1;
    }
    /**
     * Undocumented function
     *
     * @param [type] $key_status
     * @param array $true
     * @param array $false
     * @return array
     */
    private function set_toggle_status($key_status, $true = ['text' => 'Active', 'title' => 'Inactivate'], $false = ['text' => 'Inactive', 'title' => 'Activate'])
    {
        return ($key_status) ? ['color' => 'active', 'text' => $true['text'], 'title' => $true['title']] : ['color' => '', 'text' => $false['text'], 'title' => $false['title']]; //editable
    }

    private function filterColumn($dataList)
    {
        return $dataList;
    }
    /**
     * Undocumented function
     *
     * @param [type] $request
     * @param array $results
     * @param string $view
     * @param \Illuminate\Contracts\Pagination\Paginator $results
     * @return array|null
     */
    private function buildResponse($data, $results = [])
    {
        if (is_object($results) && $results instanceof \Illuminate\Database\Eloquent\Builder) {
            // Pastikan $results adalah objek Query Builder
            $data['data_pagination'] = (string) $results->appends($this->queryString)->links();
        } else {
            $data['data_pagination'] = '';
        }
        $data['data_pagination'] = (string) $results->appends($this->queryString)->links();
        
        $data['current_url'] = $this->request->fullUrl();
        return $data;
    }

    /**
     * Undocumented function
     *
     * @param [type] $request
     * @return $data
     */
    private function buildIndexView($request)
    {
        $dataList = $this->queryDataList();
        $orderBy = $request->get('orderBy');
        $orderType = $request->get('orderType');
        $type = $request->get('type');
        $data = [];
        if ($type === 'search') {
            $get_column_search = $request->getColumn;
            $data['getColumn'] = $get_column_search;
            $data['stringToSearch'] = $request->stringToSearch;
            if (array_key_exists($get_column_search, $this->columnSearch())) {
                $dataList->where($this->columnSearch()[$get_column_search]['column'], 'LIKE', '%' . $request->stringToSearch . '%');
            }
        } else if ($type === 'filter') {
            $dataList = $this->filterColumn($dataList);
        } else {


        }
        $this->orderBy = $this->defaultOrderBy();
        $this->orderType = $this->defaultOrderType();

        if (isset($orderBy) && $orderBy !== '') { //tambahan untuk order data
            if (in_array($orderBy, array_column($this->tableHeader(), 'col'))) {
                $this->orderBy = $orderBy;
            }
            $this->orderType = ($orderType == 'asc' ? 'asc' : 'desc');
        }
        $this->queryString = $request->query();
        $dataList->orderBy($this->orderBy, $this->orderType);
        $data['total_data'] = $this->getTotalDataList($dataList);
        $results = $dataList->simplePaginate(15);
        $data['list_data'] = $this->set_data_before_send($results);

        return $this->buildResponse(
            data: $data,
            results: $results,
        );
    }

    private function getTotalDataList($dataList)
    {
        return $dataList->count();
    }

}