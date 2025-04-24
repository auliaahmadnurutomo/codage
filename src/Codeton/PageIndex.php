<?php

namespace App\Codeton;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Trait PageIndex
 * 
 * Provides common functionality for index pages with pagination, ordering, and filtering
 */
trait PageIndex
{
    /**
     * @var array
     */
    protected array $queryString = [];
    
    /**
     * @var string
     */
    protected string $orderBy;
    
    /**
     * @var string
     */
    protected string $orderType;
    
    /**
     * @var array
     */
    protected array $parameter = [];

    /**
     * Calculate the starting number for current page
     *
     * @param LengthAwarePaginator $results Paginated results
     * @return int Starting number for current page
     */
    private function setNumbering(LengthAwarePaginator $results): int
    {
        return ($results->currentPage() - 1) * $results->perPage() + 1;
    }

    /**
     * Set toggle status with appropriate text and style
     *
     * @param bool $keyStatus Current status
     * @param array $trueState Labels for active state
     * @param array $falseState Labels for inactive state
     * @return array Status configuration
     */
    private function setToggleStatus(
        bool $keyStatus, 
        array $trueState = ['text' => 'Active', 'title' => 'Inactivate'],
        array $falseState = ['text' => 'Inactive', 'title' => 'Activate']
    ): array {
        return $keyStatus 
            ? ['color' => 'active', 'text' => $trueState['text'], 'title' => $trueState['title']] 
            : ['color' => '', 'text' => $falseState['text'], 'title' => $falseState['title']];
    }

    /**
     * Apply filters to query
     *
     * @param Builder $dataList Query builder instance
     * @return Builder Modified query builder with filters
     */
    private function filterColumn(Builder $dataList): Builder
    {
        return $dataList;
    }

    /**
     * Build response data for view
     *
     * @param array $data View data
     * @param LengthAwarePaginator|Builder $results Paginated results
     * @return array Complete view data with pagination
     */
    private function buildResponse(array $data, $results): array
    {
        if ($results instanceof Builder) {
            $data['data_pagination'] = '';
        } else {
            $data['data_pagination'] = (string) $results->appends($this->queryString)->links();
        }
        
        $data['current_url'] = $this->request->fullUrl();
        return $data;
    }

    /**
     * Build index view with data
     *
     * @param Request $request Current request
     * @return array View data
     */
    private function buildIndexView(Request $request): array
    {
        $dataList = $this->queryDataList();
        $orderBy = $request->get('orderBy');
        $orderType = $request->get('orderType');
        $type = $request->get('type');
        $data = [];
        
        if ($type === 'search') {
            $getColumnSearch = $request->getColumn;
            $data['getColumn'] = $getColumnSearch;
            $data['stringToSearch'] = $request->stringToSearch;
            if (array_key_exists($getColumnSearch, $this->columnSearch())) {
                $dataList->where(
                    $this->columnSearch()[$getColumnSearch]['column'], 
                    'LIKE', 
                    '%' . $request->stringToSearch . '%'
                );
            }
        } elseif ($type === 'filter') {
            $dataList = $this->filterColumn($dataList);
        }
        
        $this->orderBy = $this->defaultOrderBy();
        $this->orderType = $this->defaultOrderType();

        if (isset($orderBy) && $orderBy !== '') {
            $headers = $this->tableHeader();
            $matchingHeader = array_filter($headers, function ($header) use ($orderBy) {
                return isset($header['orderBy']) && $header['orderBy'] === $orderBy;
            });

            if (!empty($matchingHeader)) {
                $header = reset($matchingHeader);
                $this->orderBy = $header['col'];
            }
            $this->orderType = ($orderType == 'asc') ? 'asc' : 'desc';
        }
        
        $this->queryString = $request->query();
        $dataList->orderBy($this->orderBy, $this->orderType);
        $data['total_data'] = $this->getTotalDataList($dataList);
        $results = $dataList->simplePaginate(15);
        $data['list_data'] = $this->setDataBeforeSend($results);

        return $this->buildResponse(
            $data,
            $results
        );
    }

    /**
     * Get total count of data list
     *
     * @param Builder $dataList Query builder instance
     * @return int Total count
     */
    private function getTotalDataList(Builder $dataList): int
    {
        return $dataList->count();
    }
    
    /**
     * Prepare data before sending to view
     * 
     * @param LengthAwarePaginator $results Paginated results
     * @return array Formatted data
     */
    private function setDataBeforeSend(LengthAwarePaginator $results): array
    {
        // This method should be implemented by the class using this trait
        return [];
    }
    
    /**
     * Get query data list
     * 
     * @return Builder Query builder instance
     */
    protected function queryDataList(): Builder
    {
        // This method should be implemented by the class using this trait
        return new Builder();
    }
    
    /**
     * Get default order by column
     * 
     * @return string Default column name
     */
    protected function defaultOrderBy(): string
    {
        return 'id';
    }
    
    /**
     * Get default order type
     * 
     * @return string Default order direction (asc/desc)
     */
    protected function defaultOrderType(): string
    {
        return 'desc';
    }
    
    /**
     * Get column search configuration
     * 
     * @return array Column search configuration
     */
    protected function columnSearch(): array
    {
        return [];
    }
    
    /**
     * Get table header configuration
     * 
     * @return array Table header configuration
     */
    protected function tableHeader(): array
    {
        return [];
    }
}