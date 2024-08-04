<?php

namespace App\Codeton;
interface DefaultFactoryIndex
{
    function tableHeader();
	function TableView();
    function columnSearch();
	function set_data_before_send($dataResults);
    function queryDataList();
    function defaultOrderBy();
    function defaultOrderType();
}

