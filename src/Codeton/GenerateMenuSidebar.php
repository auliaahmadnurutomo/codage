<?php
namespace App\Codeton;
use DB;
use Auth;
class GenerateMenuSidebar {

	public function get_menu_access($id_access){
        if($id_access){
            $menu_access = DB::table('skeleton_setting_menu_access as a')
            ->join('skeleton_setting_template_access as b','b.id_menu_access','a.id')
            ->where('b.id_menu_template',$id_access)
            ->select('a.*')
            ->orderBy('a.menu_order')
            ->get();
        }
        else{
            $menu_access = DB::table('skeleton_setting_menu_access')->orderBy('menu_order')->get();
        }
		return $this->html_ordered_menu($menu_access,0);
	}

    public function acl_tree($selected_access = []){
        // $menu_access = DB::table('setting_menu_access')->where('access',0)->orderBy('id')->get(); 
        $menu_access = DB::table('skeleton_setting_menu_access')->where('status',1)->orderBy('id')->get();
        return $this->map_menu_access($menu_access,0,$selected_access);
    }

	private function html_ordered_menu($array, $parent_id = 0,$id="sitri",$class="tree"){
        $menu_html = '<ul>';
        foreach ($array  as $key) {
            if($key->id_parent == $parent_id){
            	if($key->type){
            		$menu_html .= '<li class="nav-item"><a class="nav-link" href = '.url($key->url).' data-active='.$key->url.'><i class= "'.$key->icon.'"></i> &nbsp'.$key->name.'</a>';
	                $menu_html .= $this->html_ordered_menu($array,$key->id);
	                $menu_html .= '</li>';
            	}
            }
        }
        $menu_html .='</ul>';
        return $menu_html;
    }

    public function map_menu_access($array, $parent_id = 0,$selected_access){
        $menu_html = '<ul>';
        
        foreach ($array  as $key) {
            $checked = '';
            if($key->id_parent == $parent_id){
                if(count($selected_access)){
                    foreach ($selected_access as $curr) {
                        if($curr->id_menu_access == $key->id){
                            $checked = 'checked';
                        }
                    }
                }
                $menu_html .= '<li><input name="authorization[]" class="form-check-input tis" type="checkbox" value='.$key->id.' id="oft_'.$key->id.'" '.$checked.'>'.$key->name.'';
                $menu_html .= $this->map_menu_access($array,$key->id,$selected_access);
                $menu_html .= '</li>';
            // }
            }
        }
        $menu_html .='</ul>';
        return $menu_html;
    }
}