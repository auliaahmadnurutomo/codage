<?php

namespace App\Helpers;

/**
 * Class ButtonHelper
 * 
 * Helper class for generating HTML button elements
 */
class ButtonHelper
{
    /**
     * Generate a modal button
     *
     * @param string $target Modal target URL
     * @param string $text Button text
     * @param string $icon Font Awesome icon class
     * @param string $addClass Additional CSS classes
     * @return string HTML button markup
     */
    public static function buttonModal(
        string $target = "#",
        string $text = "New",
        string $icon = "fa-plus-circle",
        string $addClass = "btn-primary"
    ): string {
        return sprintf(
            '<a href="%s" id="new-data" 
                class="btn %s" 
                data-toggle="modal"
                data-target="#modalForm"
                aria-expanded="false"
                aria-controls="modalForm"
                data-backdrop="static"
                data-keyboard="false"
                oncontextmenu="return false;"
                title="%s">
                <i class="fa %s" style="font-size:15px"></i>
                <span class="d-none d-md-inline">%s</span>
            </a>',
            $target,
            $addClass,
            $text,
            $icon,
            $text
        );
    }

    /**
     * Generate a detail modal button
     *
     * @param string $target Modal target URL
     * @param string $modalTarget Modal container ID
     * @return string HTML button markup
     */
    public static function btnDetailModal(string $target, string $modalTarget = '#modalForm'): string
    {
        return sprintf(
            '<a href="%s"
                class="btn btn-sm btn-light border"
                data-toggle="modal"
                data-target="%s"
                data-backdrop="static"
                data-keyboard="false"
                aria-expanded="false"
                oncontextmenu="return false;"
                aria-controls="modalForm">
                <i class="fa fa-expand text-primary"></i>
            </a>',
            $target,
            $modalTarget
        );
    }

    /**
     * Generate a redirect button
     *
     * @param string $target Redirect URL
     * @return string HTML button markup
     */
    public static function buttonRedirect(string $target): string
    {
        return sprintf(
            '<a href="%s" class="btn btn-light text-success">
                <i class="fa fa-expand"></i>
            </a>',
            $target
        );
    }

    /**
     * Generate a redirect link
     *
     * @param string $target Redirect URL
     * @param string|null $text Button text
     * @param string $btnClass Button CSS class
     * @param string|null $icon Font Awesome icon class
     * @return string HTML button markup
     */
    public static function hrefRedirect(
        string $target,
        ?string $text = null,
        string $btnClass = 'btn-light',
        ?string $icon = null
    ): string {
        return sprintf(
            '<a href="%s" class="btn %s">
                %s%s
            </a>',
            $target,
            $btnClass,
            $icon ? sprintf('<i class="fa fa-%s"></i>', $icon) : '',
            $text ? '&nbsp;' . $text : ''
        );
    }

    /**
     * Generate a new tab link
     *
     * @param string $target Target URL
     * @param string|null $text Link text
     * @param string $class CSS class
     * @param string|null $icon Font Awesome icon class
     * @return string HTML link markup
     */
    public static function newTab(
        string $target,
        ?string $text = null,
        string $class = 'light',
        ?string $icon = null
    ): string {
        return sprintf(
            '<a href="#" onClick="window.open(\'%s\'); return false;" class="btn %s">
                <i class="fa fa-%s"></i>%s
            </a>',
            $target,
            $class,
            $icon,
            $text ? '&nbsp;' . $text : ''
        );
    }

    /**
     * Generate a confirm button
     *
     * @param string $color Button color class
     * @param string|int $dataId Record ID
     * @param int $status Current status
     * @param string $nextStatus Next status text
     * @param string $function JavaScript function name
     * @return string HTML button markup
     */
    public static function btnConfirm(
        string $color,
        string|int $dataId,
        int $status,
        string $nextStatus,
        string $function = 'activation'
    ): string {
        return sprintf(
            '<div id="btn-action" 
                class="toggle-btn my-auto %s" 
                data-id="%s" 
                data-state="%d" 
                title="%s" 
                data-func="%s">
                <div class="inner-circle"></div>
            </div>',
            $color,
            $dataId,
            $status,
            $nextStatus,
            $function
        );
    }

    /**
     * Generate an action button
     *
     * @param string|int $actionId Action ID
     * @param int $actionState Action state
     * @param string $actionTitle Action title
     * @param string $actionFunction JavaScript function name
     * @param string $actionIcon Font Awesome icon class
     * @param string $actionColor Button color class
     * @return string HTML button markup
     */
    public static function btnAction(
        string|int $actionId,
        int $actionState,
        string $actionTitle,
        string $actionFunction,
        string $actionIcon = '',
        string $actionColor = ''
    ): string {
        $icon = $actionIcon !== ''
            ? sprintf('<i class="fa fa-%s %s"></i>', $actionIcon, $actionColor)
            : sprintf('<span class="%s">%s</span>', $actionColor, $actionTitle);

        return sprintf(
            '<button id="btn-action" 
                class="btn btn-sm btn-light border" 
                data-id="%s" 
                data-state="%d" 
                title="%s" 
                data-func="%s">
                %s
            </button>',
            $actionId,
            $actionState,
            $actionTitle,
            $actionFunction,
            $icon
        );
    }

    /**
     * Generate a status button
     *
     * @param string $color Button color class
     * @param string|int $dataId Record ID
     * @param int $status Current status
     * @param string $nextStatus Next status text
     * @param string $function JavaScript function name
     * @return string HTML button markup
     */
    public static function btnStatus(
        string $color,
        string|int $dataId,
        int $status,
        string $nextStatus,
        string $function = 'activation'
    ): string {
        return sprintf(
            '<div id="btn-action" 
                class="toggle-btn my-auto %s" 
                data-id="%s" 
                data-state="%d" 
                title="%s" 
                data-func="%s">
                <div class="inner-circle"></div>
            </div>',
            $color,
            $dataId,
            $status,
            $nextStatus,
            $function
        );
    }

    /**
     * Generate a toggle activation button
     *
     * @param string $color Button color class
     * @param string|int $dataId Record ID
     * @param int $status Current status
     * @param string $nextStatus Next status text
     * @param string $function JavaScript function name
     * @return string HTML button markup
     */
    public static function btnToggleActivation(
        string $color,
        string|int $dataId,
        int $status,
        string $nextStatus,
        string $function = 'activation'
    ): string {
        return sprintf(
            '<div id="btn-action" 
                class="toggle-btn my-auto %s" 
                data-id="%s" 
                data-state="%d" 
                title="%s" 
                data-func="%s">
                <div class="inner-circle"></div>
            </div>',
            $color,
            $dataId,
            $status,
            $nextStatus,
            $function
        );
    }

    /**
     * Generate a delete button
     *
     * @param string|int $id Record ID
     * @param string $target Target function name
     * @return string HTML button markup
     */
    public static function btnDelete(string|int $id, string $target = 'delete'): string
    {
        return sprintf(
            '<button id="btn-action" 
                class="btn btn-sm btn-light border" 
                data-id="%s" 
                data-state="Delete" 
                title="Delete" 
                data-func="%s">
                <i class="fa fa-trash text-danger"></i>
            </button>',
            $id,
            $target
        );
    }
}
