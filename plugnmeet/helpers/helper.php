<?php
/**
 *
 * @since      1.0.0
 * @package    Plugnmeet
 * @subpackage Plugnmeet/helpers
 * @author     Jibon Costa <jibon@mynaparrot.com>
 */

if (!defined('PLUGNMEET_BASE_NAME')) {
    die;
}

class PlugnmeetHelper
{
    private static $allowedHtml = array(
        'select' => array(
            'id' => array(),
            'name' => array(),
            'value' => array(),
            'class' => array(),
        ),
        'option' => array(
            'value' => array(),
            'selected' => array(),
        ),
        'input' => array(
            'type' => array(),
            'id' => array(),
            'name' => array(),
            'value' => array(),
            'class' => array(),
            'style' => array(),
            'autocomplete' => array()
        ),
        'textarea' => array(
            'name' => array(),
            'cols' => array(),
            'rows' => array()
        ),
        'tr' => array(),
        'th' => array(
            'scope' => array()
        ),
        'td' => array(
            'scope' => array()
        )
    );

    public static function secureRandomKey(int $length = 36): string
    {
        $keyspace = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $pieces = [];
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            $pieces [] = $keyspace[random_int(0, $max)];
        }
        return implode('', $pieces);
    }

    private static function formatHtml($items, $fieldName, $data)
    {
        $html = "";
        foreach ($items as $key => $item) {
            if ($item["type"] === "select") {
                $html .= '<tr>';
                $html .= '<th scope="row">' . $item['label'] . '</th>';
                $html .= '<td>';
                $html .= "<select name=\"{$fieldName}[{$key}]\" class=\"list_class\">";

                $value = $item["selected"];
                if (isset($data[$key])) {
                    $value = $data[$key];
                }

                foreach ($item["options"] as $option) {
                    $selected = "";
                    if ($option['value'] == $value) {
                        $selected = "selected";
                    }
                    $html .= '<option value="' . esc_attr($option['value']) . '" ' . $selected . '>' . esc_attr($option['label']) . '</option>';
                }

                $html .= '</select></td></tr>';
            } elseif ($item["type"] === "text" || $item["type"] === "number") {
                $value = $item["default"];
                if (isset($data[$key])) {
                    $value = $data[$key];
                }
                $html .= '<tr>';
                $html .= '<th scope="row">' . esc_attr($item['label']) . '</th>';
                $html .= '<td>';
                $html .= '<input type="' . esc_attr($item["type"]) . '" name="' . $fieldName . '[' . esc_attr($key) . ']" value="' . esc_attr($value) . '"   autocomplete="off">';
                $html .= '</td></tr>';
            } elseif ($item["type"] === "textarea") {
                $value = $item["default"];
                if (isset($data[$key])) {
                    $value = $data[$key];
                }
                $html .= '<tr>';
                $html .= '<th scope="row">' . esc_attr($item['label']) . '</th>';
                $html .= '<td>';
                $html .= '<textarea name="' . esc_attr($fieldName) . '[' . esc_attr($key) . ']">' . esc_attr($value) . '</textarea>';
                $html .= '</td></tr>';
            }
        }

        return wp_kses($html, self::$allowedHtml);
    }

    public static function getRoomFeatures($room_features)
    {
        $roomFeatures = array(
            "allow_webcams" => array(
                "label" => __("Allow webcams", "plugnmeet"),
                "options" => array(
                    array(
                        "label" => __("Yes", "plugnmeet"),
                        "value" => 1
                    ), array(
                        "label" => __("No", "plugnmeet"),
                        "value" => 0
                    )),
                "selected" => 1,
                "type" => "select"
            ),
            "mute_on_start" => array(
                "label" => __("Mute on start", "plugnmeet"),
                "options" => array(
                    array(
                        "label" => __("Yes", "plugnmeet"),
                        "value" => 1
                    ), array(
                        "label" => __("No", "plugnmeet"),
                        "value" => 0
                    )),
                "selected" => 0,
                "type" => "select"
            ),
            "allow_screen_share" => array(
                "label" => __("Allow screen share", "plugnmeet"),
                "options" => array(
                    array(
                        "label" => __("Yes", "plugnmeet"),
                        "value" => 1
                    ), array(
                        "label" => __("No", "plugnmeet"),
                        "value" => 0
                    )),
                "selected" => 1,
                "type" => "select"
            ),
            "allow_recording" => array(
                "label" => __("Allow recording", "plugnmeet"),
                "options" => array(
                    array(
                        "label" => __("Yes", "plugnmeet"),
                        "value" => 1
                    ), array(
                        "label" => __("No", "plugnmeet"),
                        "value" => 0
                    )),
                "selected" => 1,
                "type" => "select"
            ),
            "allow_rtmp" => array(
                "label" => __("Allow rtmp", "plugnmeet"),
                "options" => array(
                    array(
                        "label" => __("Yes", "plugnmeet"),
                        "value" => 1
                    ), array(
                        "label" => __("No", "plugnmeet"),
                        "value" => 0
                    )),
                "selected" => 1,
                "type" => "select"
            ),
            "allow_view_other_webcams" => array(
                "label" => __("Allow view other webcams", "plugnmeet"),
                "options" => array(
                    array(
                        "label" => __("Yes", "plugnmeet"),
                        "value" => 1
                    ), array(
                        "label" => __("No", "plugnmeet"),
                        "value" => 0
                    )),
                "selected" => 1,
                "type" => "select"
            ),
            "allow_view_other_users_list" => array(
                "label" => __("Allow view other users list", "plugnmeet"),
                "options" => array(
                    array(
                        "label" => __("Yes", "plugnmeet"),
                        "value" => 1
                    ), array(
                        "label" => __("No", "plugnmeet"),
                        "value" => 0
                    )),
                "selected" => 1,
                "type" => "select"
            ),
            "admin_only_webcams" => array(
                "label" => __("Admin only webcams", "plugnmeet"),
                "options" => array(
                    array(
                        "label" => __("Yes", "plugnmeet"),
                        "value" => 1
                    ), array(
                        "label" => __("No", "plugnmeet"),
                        "value" => 0
                    )),
                "selected" => 0,
                "type" => "select"
            ),
            "allow_polls" => array(
                "label" => __("Allow polls", "plugnmeet"),
                "options" => array(
                    array(
                        "label" => __("Yes", "plugnmeet"),
                        "value" => 1
                    ), array(
                        "label" => __("No", "plugnmeet"),
                        "value" => 0
                    )),
                "selected" => 1,
                "type" => "select"
            ),
            "room_duration" => array(
                "label" => __("Room duration (In minutes, 0 = unlimited)", "plugnmeet"),
                "default" => 0,
                "type" => "number"
            )
        );

        $data = [];
        if (!empty($room_features)) {
            $data = $room_features;
        }

        return self::formatHtml($roomFeatures, "room_features", $data);
    }

    public static function getChatFeatures($chat_features)
    {
        $chatFeatures = array(
            "allow_chat" => array(
                "label" => __("Allow chat", "plugnmeet"),
                "options" => array(
                    array(
                        "label" => __("Yes", "plugnmeet"),
                        "value" => 1
                    ), array(
                        "label" => __("No", "plugnmeet"),
                        "value" => 0
                    )),
                "selected" => 1,
                "type" => "select"
            ),
            "allow_file_upload" => array(
                "label" => __("Allow file upload", "plugnmeet"),
                "options" => array(
                    array(
                        "label" => __("Yes", "plugnmeet"),
                        "value" => 1
                    ), array(
                        "label" => __("No", "plugnmeet"),
                        "value" => 0
                    )),
                "selected" => 1,
                "type" => "select"
            ),
        );

        $data = [];
        if (!empty($chat_features)) {
            $data = $chat_features;
        }

        return self::formatHtml($chatFeatures, "chat_features", $data);
    }

    public static function getSharedNotePadFeatures($sharedNotePad_features)
    {
        $sharedNotePadFeatures = array(
            "allowed_shared_note_pad" => array(
                "label" => __("Allow shared notepad", "plugnmeet"),
                "options" => array(
                    array(
                        "label" => __("Yes", "plugnmeet"),
                        "value" => 1
                    ), array(
                        "label" => __("No", "plugnmeet"),
                        "value" => 0
                    )),
                "selected" => 1,
                "type" => "select"
            )
        );

        $data = [];
        if (!empty($sharedNotePad_features)) {
            $data = $sharedNotePad_features;
        }

        return self::formatHtml($sharedNotePadFeatures, "shared_note_pad_features", $data);
    }

    public static function getWhiteboardFeatures($whiteboard_features)
    {
        $whiteboardFeatures = array(
            "allowed_whiteboard" => array(
                "label" => __("Allow whiteboard", "plugnmeet"),
                "options" => array(
                    array(
                        "label" => __("Yes", "plugnmeet"),
                        "value" => 1
                    ), array(
                        "label" => __("No", "plugnmeet"),
                        "value" => 0
                    )),
                "selected" => 1,
                "type" => "select"
            )
        );

        $data = [];
        if (!empty($whiteboard_features)) {
            $data = $whiteboard_features;
        }

        return self::formatHtml($whiteboardFeatures, "whiteboard_features", $data);
    }

    public static function getExternalMediaPlayerFeatures($external_media_player_features)
    {
        $externalMediaPlayerFeatures = array(
            "allowed_external_media_player" => array(
                "label" => __("Allow external media player", "plugnmeet"),
                "options" => array(
                    array(
                        "label" => __("Yes", "plugnmeet"),
                        "value" => 1
                    ), array(
                        "label" => __("No", "plugnmeet"),
                        "value" => 0
                    )),
                "selected" => 1,
                "type" => "select"
            )
        );

        $data = [];
        if (!empty($external_media_player_features)) {
            $data = $external_media_player_features;
        }

        return self::formatHtml($externalMediaPlayerFeatures, "external_media_player_features", $data);
    }

    public static function getWaitingRoomFeatures($waiting_room_features)
    {
        $waitingRoomFeatures = array(
            "is_active" => array(
                "label" => __("Activate waiting room", "plugnmeet"),
                "options" => array(
                    array(
                        "label" => __("Yes", "plugnmeet"),
                        "value" => 1
                    ), array(
                        "label" => __("No", "plugnmeet"),
                        "value" => 0
                    )),
                "selected" => 0,
                "type" => "select"
            ),
            "waiting_room_msg" => array(
                "label" => __("Waiting room message", "plugnmeet"),
                "default" => "",
                "type" => "textarea"
            )
        );

        $data = [];
        if (!empty($waiting_room_features)) {
            $data = $waiting_room_features;
        }

        return self::formatHtml($waitingRoomFeatures, "waiting_room_features", $data);
    }

    public static function getBreakoutRoomFeatures($breakout_room_features)
    {
        $breakoutRoomFeatures = array(
            "is_allow" => array(
                "label" => __("Allow breakout rooms", "plugnmeet"),
                "options" => array(
                    array(
                        "label" => __("Yes", "plugnmeet"),
                        "value" => 1
                    ), array(
                        "label" => __("No", "plugnmeet"),
                        "value" => 0
                    )),
                "selected" => 1,
                "type" => "select"
            ),
            "allowed_number_rooms" => array(
                "label" => __("Number of rooms", "plugnmeet"),
                "default" => 6,
                "type" => "number"
            )
        );

        $data = [];
        if (!empty($breakout_room_features)) {
            $data = $breakout_room_features;
        }

        return self::formatHtml($breakoutRoomFeatures, "breakout_room_features", $data);
    }

    public static function getDefaultLockSettings($default_lock_settings)
    {
        $defaultLockSettings = array(
            "lock_microphone" => array(
                "label" => __("Lock microphone", "plugnmeet"),
                "options" => array(
                    array(
                        "label" => __("Yes", "plugnmeet"),
                        "value" => 1
                    ), array(
                        "label" => __("No", "plugnmeet"),
                        "value" => 0
                    )),
                "selected" => 0,
                "type" => "select"
            ),
            "lock_webcam" => array(
                "label" => __("Lock webcam", "plugnmeet"),
                "options" => array(
                    array(
                        "label" => __("Yes", "plugnmeet"),
                        "value" => 1
                    ), array(
                        "label" => __("No", "plugnmeet"),
                        "value" => 0
                    )),
                "selected" => 0,
                "type" => "select"
            ),
            "lock_screen_sharing" => array(
                "label" => __("Lock screen sharing", "plugnmeet"),
                "options" => array(
                    array(
                        "label" => __("Yes", "plugnmeet"),
                        "value" => 1
                    ), array(
                        "label" => __("No", "plugnmeet"),
                        "value" => 0
                    )),
                "selected" => 1,
                "type" => "select"
            ),
            "lock_whiteboard" => array(
                "label" => __("Lock whiteboard", "plugnmeet"),
                "options" => array(
                    array(
                        "label" => __("Yes", "plugnmeet"),
                        "value" => 1
                    ), array(
                        "label" => __("No", "plugnmeet"),
                        "value" => 0
                    )),
                "selected" => 1,
                "type" => "select"
            ),
            "lock_shared_notepad" => array(
                "label" => __("Lock shared notepad", "plugnmeet"),
                "options" => array(
                    array(
                        "label" => __("Yes", "plugnmeet"),
                        "value" => 1
                    ), array(
                        "label" => __("No", "plugnmeet"),
                        "value" => 0
                    )),
                "selected" => 1,
                "type" => "select"
            ),
            "lock_chat" => array(
                "label" => __("Lock chat", "plugnmeet"),
                "options" => array(
                    array(
                        "label" => __("Yes", "plugnmeet"),
                        "value" => 1
                    ), array(
                        "label" => __("No", "plugnmeet"),
                        "value" => 0
                    )),
                "selected" => 0,
                "type" => "select"
            ),
            "lock_chat_send_message" => array(
                "label" => __("Lock chat send message", "plugnmeet"),
                "options" => array(
                    array(
                        "label" => __("Yes", "plugnmeet"),
                        "value" => 1
                    ), array(
                        "label" => __("No", "plugnmeet"),
                        "value" => 0
                    )),
                "selected" => 0,
                "type" => "select"
            ),
            "lock_chat_file_share" => array(
                "label" => __("Lock chat file share", "plugnmeet"),
                "options" => array(
                    array(
                        "label" => __("Yes", "plugnmeet"),
                        "value" => 1
                    ), array(
                        "label" => __("No", "plugnmeet"),
                        "value" => 0
                    )),
                "selected" => 0,
                "type" => "select"
            ),
            "lock_private_chat" => array(
                "label" => __("Lock private chat", "plugnmeet"),
                "options" => array(
                    array(
                        "label" => __("Yes", "plugnmeet"),
                        "value" => 1
                    ), array(
                        "label" => __("No", "plugnmeet"),
                        "value" => 0
                    )),
                "selected" => 0,
                "type" => "select"
            ),
        );

        $data = [];
        if (!empty($default_lock_settings)) {
            $data = (array)$default_lock_settings;
        }

        return self::formatHtml($defaultLockSettings, "default_lock_settings", $data);
    }

    public static function getStatusSettings($published = 1)
    {
        $options = array(
            array(
                "value" => 1,
                "text" => "Published"
            ),
            array(
                "value" => 0,
                "text" => "Unpublished"
            )
        );

        $html = '<tr>';
        $html .= '<th scope="row">' . __("Room Status", "plugnmeet") . '</th>';
        $html .= '<td>';
        $html .= '<select id="published" name="published" >';

        foreach ($options as $option) {
            if ($published == $option['value']) {
                $html .= '<option value="' . $option['value'] . '" selected = "selected">' . $option['text'] . '</option>';
            } else {
                $html .= '<option value="' . $option['value'] . '" >' . $option['text'] . '</option>';
            }
        }

        $html .= '</select></td></tr>';
        return $html;
    }
}
