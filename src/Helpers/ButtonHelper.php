<?php
namespace App\Helpers;

// use Illuminate\Support\Facades\DB;

class ButtonHelper {
    public static function button_modal($target = "#",$text = "New",$icon="fa-plus-circle",$addClass="btn-primary") {
        return '
                <a href="'.$target.'" id="new-data"
                    class="btn '.$addClass.'"
                    data-toggle="modal"
                    data-target="#modalForm"
                    aria-expanded="false"
                    aria-controls="modalForm"
                    data-backdrop="static"
                    data-keyboard="false"
                    oncontextmenu="return false;"
                    title="'.$text.'"><i class="fa '.$icon.'" style="font-size:15px"></i> <span class="d-none d-md-inline">'.$text.'</span>
                </a>';
    }

    public static function btn_detail_modal($target,$modalTarget = '#modalForm'){
    	return '<a
                                        href="'.$target.'"
                                        class="btn btn-sm btn-light border"
                                        data-toggle="modal"
                                        data-target="'.$modalTarget.'"
                                        data-backdrop="static"
                                        data-keyboard="false"
                                        aria-expanded="false"
                                        oncontextmenu="return false;"
                                        aria-controls="modalForm">
                                        <i class="fa fa-expand text-primary"></i>
                                    </a>';
    }

    public static function button_redirect($target){
    	return '<a
            href="'.$target.'"
            class="btn btn-light text-success">
            <i class="fa fa-expand"></i>
        </a>';
    }

    public static function href_redirect($target,$text=null,$btn_class = 'light',$icon = null){
        return '<a
            href="'.$target.'"
            class="btn '.$btn_class.'">
            <i class="fa fa-'.$icon.'"></i>'.($text ? '&nbsp '.$text : '').'
        </a>';
    }

    public static function new_tab($target,$text=null,$class = 'light',$icon = null){
        return '<a href="#" onClick="window.open(\'' . $target . '\'); return false;" class="btn ' . $class . '">
                <i class="fa fa-' . $icon . '"></i>' . ($text ? '&nbsp;' . $text : '') . '
            </a>';
    }

    public static function btn_confirm(
        $color,
        $data_id,
        $status,
        $next_status,
        $url = 'activation'
    ){
        return '<div id="btn-action" class="toggle-btn my-auto '.$color.'" data-id="'.$data_id.'" data-state="'.$status.'" title="'.$next_status.'" data-func="activation">
            <div class="inner-circle"></div>
            </div>';
    }

    public static function btn_action(
        $actionId,
        $actionState,
        $actionTitle,
        $actionFunction,
        $actionIcon = '',
        $actionColor = ''){
        if($actionIcon !== ''){
            $icon = '<i class="fa fa-'.$actionIcon.' '.$actionColor.'"></i>';
        }
        else{
            $icon = '<span class="'.$actionColor.'">'.$actionTitle.'</span>';
        }
        return '
        <button id="btn-action" 
            class="btn btn-sm btn-light border" 
            data-id="'.$actionId.'" 
            data-state="'.$actionState.'" 
            title="'.$actionTitle.' data" 
            data-func="'.$actionFunction.'"
        >
            '.$icon.'
        </button>';
    }

    public static function btn_status($color,$data_id,$status,$next_status,$url){
        return '<div id="btn-action" class="toggle-btn my-auto '.$color.'" data-id="'.$data_id.'" data-state="'.$status.'" title="'.$next_status.'" data-func="activation">
            <div class="inner-circle"></div>
            </div>';
    }



    public static function btn_toggle_activation($color,$data_id,$status,$next_status,$function = 'activation'){
    	return '<div id="btn-action" class="toggle-btn my-auto '.$color.'" data-id="'.$data_id.'" data-state="'.$status.'" title="'.$next_status.'" data-func="'.$function.'">
            <div class="inner-circle"></div>
            </div>';
    }

    public static function btn_delete($id,$target = 'delete'){
    	return '<button id="btn-action" class="btn btn-sm btn-light border" data-id="'.$id.'" data-state="Delete" title="Delete" data-func="'.$target.'">
            <i class="fa fa-trash text-danger"></i></button>';
    }

}
