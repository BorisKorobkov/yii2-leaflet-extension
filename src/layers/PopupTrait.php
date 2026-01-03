<?php
declare(strict_types=1);

/**
 * PopupTrait.php
 *
 * Date: 15/02/14
 * Time: 11:03
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @link https://www.ramirezcobos.com/
 * @link https://www.2amigos.us/
 */

namespace boriskorobkov\leaflet\layers;

trait PopupTrait
{
    /**
     * @var string the HTML content of the popup to display when clicking on the marker.
     */
    public ?string $popupContent = null;

    /**
     * @var bool whether to open the popup dialog on display.
     */
    public bool $openPopup = false;

    /**
     * Binds popup content (if any) to the js code to register
     *
     * @param string $js
     *
     * @return string
     */
    protected function bindPopupContent(string $js): string
    {
        if (!empty($this->popupContent)) {
            $content = addslashes($this->popupContent);
            $js .= ".bindPopup(\"{$content}\")";
            if ($this->openPopup) {
                $js .= ".openPopup()";
            }
        }
        return $js;
    }
}
