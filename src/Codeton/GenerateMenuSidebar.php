<?php

namespace App\Codeton;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

/**
 * Class GenerateMenuSidebar
 * 
 * Handles menu and sidebar generation for the application
 */
class GenerateMenuSidebar
{
    /**
     * Get menu access HTML
     *
     * @param array $menuAccess The menu access data
     * @return string HTML representation of menu
     */
    public function getMenuAccess(array $menuAccess): string
    {
        return $this->htmlOrderedMenu($menuAccess, 0);
    }

    /**
     * Generate access control list tree
     *
     * @param array $selectedAccess Selected access array
     * @return string HTML representation of ACL tree
     */
    public function aclTree(array $selectedAccess = []): string
    {
        $menuAccess = DB::table('skeleton_setting_menu_access')
            ->where('status', 1)
            ->orderBy('id')
            ->get();
            
        return $this->mapMenuAccess($menuAccess, 0, $selectedAccess);
    }

    /**
     * Generate HTML ordered menu
     *
     * @param array $array Menu items array
     * @param int $parentId Parent ID
     * @param string $id List ID
     * @param string $class List class
     * @return string HTML representation of ordered menu
     */
    private function htmlOrderedMenu(array $array, int $parentId = 0, string $id = "sitri", string $class = "tree"): string
    {
        $menuHtml = '<ul>';
        
        foreach ($array as $key) {
            if ($key->id_parent == $parentId) {
                if ($key->type) {
                    $menuHtml .= '<li class="nav-item">';
                    $menuHtml .= '<a class="nav-link" href="' . url($key->url) . '" data-active="' . $key->url . '">';
                    $menuHtml .= '<i class="' . $key->icon . '"></i> &nbsp;' . $key->name . '</a>';
                    $menuHtml .= $this->htmlOrderedMenu($array, $key->id);
                    $menuHtml .= '</li>';
                }
            }
        }
        
        $menuHtml .= '</ul>';
        
        return $menuHtml;
    }

    /**
     * Map menu access to HTML
     *
     * @param array $array Menu items array
     * @param int $parentId Parent ID
     * @param array $selectedAccess Selected access array
     * @return string HTML representation of access mapping
     */
    public function mapMenuAccess(array $array, int $parentId = 0, array $selectedAccess = []): string
    {
        $menuHtml = '<ul>';
        
        foreach ($array as $key) {
            if ($key->id_parent == $parentId) {
                $checked = '';
                
                if (count($selectedAccess)) {
                    foreach ($selectedAccess as $curr) {
                        if ($curr->id_menu_access == $key->id) {
                            $checked = 'checked';
                            break;
                        }
                    }
                }
                
                $menuHtml .= '<li>';
                $menuHtml .= '<input name="authorization[]" class="form-check-input tis" type="checkbox" ';
                $menuHtml .= 'value="' . $key->id . '" id="oft_' . $key->id . '" ' . $checked . '>';
                $menuHtml .= $key->name;
                $menuHtml .= $this->mapMenuAccess($array, $key->id, $selectedAccess);
                $menuHtml .= '</li>';
            }
        }
        
        $menuHtml .= '</ul>';
        
        return $menuHtml;
    }
}